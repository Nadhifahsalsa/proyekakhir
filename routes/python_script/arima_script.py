import sys
import json
import pandas as pd
import statsmodels.api as sm

# Debugging: Print argumen yang diterima
print("Arguments passed to the script:", sys.argv)
print(sys.argv[1]);

if len(sys.argv) < 2:
    print("No arguments provided!")
    sys.exit(0)

# Mengambil data dari argumen
data = json.loads(sys.argv[1])
print("Data JSON: ", data)

# Membuat DataFrame dari data
df = pd.DataFrame(data)

# Convert 'jumlah_barang' to numeric
# df['jumlah_barang'] = pd.to_numeric(df['jumlah_barang'])

# Memastikan kolom tgl_keluar menjadi indeks datetime
df['tgl_keluar'] = pd.to_datetime(df['tgl_keluar'])
df.set_index('tgl_keluar', inplace=True)

# Pisahkan nama_barang agar dapat dimasukkan kembali ke hasil prediksi
barang = df['barang'].iloc[0]

# Membuat model ARIMA
model = sm.tsa.ARIMA(df['jumlah_barang'], order=(5, 1, 0))
results = model.fit()

# # Prediksi untuk n periode ke depan
# forecast = results.forecast(steps=10)
# forecast = forecast.tolist()

# # Mengembalikan hasil prediksi sebagai JSON dengan nama_barang
# output = {
#     'barang': barang,
#     'forecast': forecast
# }
# print(json.dumps(output))


results_dict = {}
for barang, group in df.groupby('barang'):
    model = sm.tsa.ARIMA(group['jumlah_barang'], order=(5, 1, 0))
    results = model.fit()
    forecast = results.forecast(steps=1)  # Predict next month's value
    results_dict[barang] = forecast.tolist()

print(json.dumps(results_dict))


# # Mengembalikan hasil prediksi sebagai JSON
# print(forecast.to_json())
# # print("HALO GAN")

