# ------ Load The Model -----
import joblib

filename = "Mymodel.joblib"
Loaded_model = joblib.load(filename)

A = {
    'Outlook': ['Overcast', 'Rainy', 'Sunny'], 
    'Temp': ['Cool', 'Hot', 'Mild'], 
    'Humidity': ['High', 'Normal'], 
    'Windy': ['False', 'True'], 
    'Play': ['No', 'Yes']
}


# ------ Receive Data from ESP32.php File -----
import sys

data_wind = int(sys.argv[1])
data_ldr = int(sys.argv[2])
data_rain = int(sys.argv[3])
data_temp = float(sys.argv[4])
data_humidity = float(sys.argv[5])

# Outlook
MyOutlook = ""
if data_ldr == "0":
    MyOutlook = "Sunny"
elif data_ldr<3000: 
    MyOutlook = "Rainy"
else:
    MyOutlook = "Overcast"

# Temperature
MyTemp = ""
if data_temp <= 5:
    MyTemp = "Cool"
elif data_temp <= 35:
    MyTemp = "Mild"
else:
    MyTemp = "Hot"

# Humidity
MyHumidity = ""
if data_humidity <= 60:
    MyHumidity = "Normal"
else:
    MyHumidity = "High"

# Windy
MyWindy = ""
if data_wind <= 1500:
    MyWindy = "False"
else:
    MyWindy = "True"

# ------ AI Model Prediction -----
z1 = A['Outlook'].index(MyOutlook)
z2 = A['Temp'].index(MyTemp)
z3 = A['Humidity'].index(MyHumidity)
z4 = A['Windy'].index(MyWindy)

Mypredict = Loaded_model.predict([[z1,z2,z3,z4]])


Pred = A['Play'][Mypredict[0]]
PreValue = "Play" if Pred=="Yes" else "Not Play"
print(PreValue)

