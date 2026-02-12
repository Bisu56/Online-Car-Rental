import pandas as pd
from sklearn.linear_model import LinearRegression

# Load booking data CSV (must be in same folder)
df = pd.read_csv('booking_data.csv')

# Clean data: remove missing values & non-positive days
df = df.dropna()
df = df[df['days'] > 0]

# Features and target variable
X = df[['car_id', 'days']]
y = df['total_amount']

# Train linear regression model
model = LinearRegression()
model.fit(X, y)

# Predict dynamic price for each car for 3-day rental
car_ids = df['car_id'].unique()
predicted_prices = []

for car_id in car_ids:
    pred_price = model.predict([[car_id, 3]])[0]
    predicted_prices.append({'car_id': car_id, 'dynamic_price': round(pred_price, 2)})

# Save predictions to CSV
output = pd.DataFrame(predicted_prices)
output.to_csv('predicted_prices.csv', index=False)
print("Model trained and saved to predicted_prices.csv")
