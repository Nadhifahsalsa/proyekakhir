from flask import Flask, request, jsonify
import json
import os
import tempfile
import traceback

import pandas as pd
from statsmodels.tsa.statespace.sarimax import SARIMAX
from statsmodels.tsa.arima.model import ARIMA

app = Flask(__name__)

# Fungsi untuk melakukan peramalan dengan ARIMA
def forecast_arima(series, steps=1):
    try:
        if len(series) > 1:  # Memastikan ada cukup data untuk membangun model
            model = ARIMA(series, order=(1, 1, 1))
            model_fit = model.fit()
            forecast = model_fit.forecast(steps=steps)
            return forecast[0]  # Mengembalikan prediksi
        else:
            return None  # Tidak cukup data untuk peramalan
    except Exception as e:
        print(f"Error processing {series.name}: {e}")
        return None

@app.route('/run-script', methods=['POST'])
def run_script():
    if 'file' not in request.files:
        return "No file part", 400

    file = request.files['file']
    if file.filename == '':
        return "No selected file", 400

    # Create a temporary file using tempfile
    with tempfile.NamedTemporaryFile(delete=False, suffix='.csv') as temp_file:
        temp_file_path = temp_file.name
        file.save(temp_file_path)

    try:
        # Membaca data dari tabel barang_keluar
        # Misal, data disimpan dalam file CSV
        data = pd.read_csv(temp_file_path, parse_dates=['tgl_keluar'], index_col='tgl_keluar')

        # Mengelompokkan data berdasarkan nama_barang
        grouped_data = data.groupby('barang')

        # Membuat seri waktu untuk setiap barang
        time_series_data = {name: group['jumlah_barang'].resample('M').sum() for name, group in grouped_data}

        # Memprediksi jumlah keluaran untuk bulan depan untuk setiap barang
        forecasts = {name: forecast_arima(series, steps=1) for name, series in time_series_data.items() if len(series.dropna()) > 1}

        # Menghapus barang yang tidak memiliki prediksi
        forecasts = {name: forecast for name, forecast in forecasts.items() if forecast is not None}

        # Menentukan barang yang diprediksi memiliki permintaan tertinggi
        predictions = pd.DataFrame.from_dict(forecasts, orient='index', columns=['forecast'])
        predictions.sort_values(by='forecast', ascending=False, inplace=True)

        # ////////////////////////////////////////////////

        # Get the first 5 indexes of the DataFrame
        predictions = predictions.head(5)

        # Rename the first column to 'id_barang_keluar'
        predictions.index.name = 'barang'
        predictions.reset_index(inplace=True)

        # Convert DataFrame to JSON
        predictions_json = predictions.to_json(orient='records')

        # Clean up the temporary file
        os.remove(temp_file_path)

        # Return predictions as JSON
        return predictions_json, 200
    except Exception as e:
        # Capture the full traceback
        error_trace = traceback.format_exc()
        
        # Clean up the temporary file in case of error
        if os.path.exists(temp_file_path):
            os.remove(temp_file_path)
        return {"error": str(e), "traceback": error_trace}, 500

if __name__ == '__main__':
    app.run(debug=True, port=5000)
