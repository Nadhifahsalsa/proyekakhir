import pandas as pd
from statsmodels.tsa.statespace.sarimax import SARIMAX
import json
import sys

# Membaca data dari tabel barang_keluar
# Misal, data disimpan dalam file CSV
data = json.loads(sys.argv[1])
print("Data JSON: ", data)
df = pd.DataFrame(data)

# Convert 'jumlah_barang' to numeric
df['jumlah_barang'] = pd.to_numeric(df['jumlah_barang'])

# Memastikan kolom tgl_keluar menjadi indeks datetime
df['tgl_keluar'] = pd.to_datetime(df['tgl_keluar'])
df.set_index('tgl_keluar', inplace=True)

# Mengelompokkan data berdasarkan nama_barang
grouped_data = data.groupby('barang')

# Membuat seri waktu untuk setiap barang
time_series_data = {name: group['jumlah_barang'].resample('M').sum() for name, group in grouped_data}

from statsmodels.tsa.arima.model import ARIMA

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

# Memprediksi jumlah keluaran untuk bulan depan untuk setiap barang
forecasts = {name: forecast_arima(series, steps=1) for name, series in time_series_data.items() if len(series.dropna()) > 1}

# Menghapus barang yang tidak memiliki prediksi
forecasts = {name: forecast for name, forecast in forecasts.items() if forecast is not None}

# Menentukan barang yang diprediksi memiliki permintaan tertinggi
predictions = pd.DataFrame.from_dict(forecasts, orient='index', columns=['forecast'])
predictions.sort_values(by='forecast', ascending=False, inplace=True)

# Menampilkan barang yang harus disiapkan untuk bulan depan
# print("Barang yang harus disiapkan untuk bulan depan:")
print(json.dumps(predictions))