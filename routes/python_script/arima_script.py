import pandas as pd
import statsmodels.api as sm
import sys
import json

# Mengambil data dari argumen
data = json.loads(sys.argv[1])

# Membuat DataFrame dari data
df = pd.DataFrame(data)

# Memastikan kolom date menjadi indeks datetime
df['date'] = pd.to_datetime(df['date'])
df.set_index('date', inplace=True)

# Membuat model ARIMA
model = sm.tsa.ARIMA(df['quantity'], order=(5, 1, 0))
results = model.fit()

# Prediksi untuk n periode ke depan
forecast = results.forecast(steps=10)

# Mengembalikan hasil prediksi sebagai JSON
print(forecast.to_json())
