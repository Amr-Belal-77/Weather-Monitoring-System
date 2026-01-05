import sys
import mysql.connector
import datetime
import joblib

try:
    # ============ Read Arguments from PHP ============    
    Wind_Data = int(sys.argv[1])
    LDR_Data  = sys.argv[2]              # HIGH / LOW
    Rain_Data = int(sys.argv[3])
    Temp_Data = float(sys.argv[4])
    Humd_Data = float(sys.argv[5])

    # ============ Insert Sensor Data into MySQL ============   
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="Root",
        database="WeatherDataset"
    )
    cursor = conn.cursor()

    mytime = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    
    sql = "INSERT INTO Temperature VALUES (%s, %s, %s, %s, %s, %s)"
    data = (mytime, Wind_Data, LDR_Data, Rain_Data, Temp_Data, Humd_Data)

    cursor.execute(sql, data)
    conn.commit()

    # ============ AI Model Prediction ============  
    filename = "Mymodel.joblib"
    Loaded_model = joblib.load(filename)

    A = {
        'Outlook': ['Overcast', 'Rainy', 'Sunny'], 
        'Temp': ['Cool', 'Hot', 'Mild'], 
        'Humidity': ['High', 'Normal'], 
        'Windy': ['False', 'True'], 
        'Play': ['No', 'Yes']
    }

    # --- Outlook Logic ---
    if Rain_Data < 3000:
        MyOutlook = "Rainy"
    elif LDR_Data == "LOW":
        MyOutlook = "Sunny"
    else:
        MyOutlook = "Overcast"

    # --- Temperature ---
    if Temp_Data <= 25:
        MyTemp = "Cool"
    elif Temp_Data <= 38:
        MyTemp = "Mild"
    else:
        MyTemp = "Hot"

    # --- Humidity ---
    MyHumidity = "Normal" if Humd_Data <= 60 else "High"

    # --- Windy ---
    MyWindy = "False" if Wind_Data <= 1500 else "True"

    # Convert Categorical to Numerical  
    z1 = A['Outlook'].index(MyOutlook)
    z2 = A['Temp'].index(MyTemp)
    z3 = A['Humidity'].index(MyHumidity)
    z4 = A['Windy'].index(MyWindy)

    Mypredict = Loaded_model.predict([[z1,z2,z3,z4]])
    Pred = A['Play'][Mypredict[0]]
    PreValue = "Play" if Pred == "Yes" else "Not Play"

    # ============ Update AI Prediction in DB ============  
    cursor = conn.cursor()
    sql = "UPDATE IOTControl SET AIpred = %s;"
    data = (PreValue,)
    cursor.execute(sql, data)
    conn.commit()

    # ============ Read Output to return to ESP32 ============  
    Msg = "SELECT * FROM IOTControl;"
    cursor.execute(Msg)
    row = cursor.fetchall()[0]

    output = row[1] if row[0] == "0" else row[2]

    print(output)

except Exception as e:
    print(f"ERROR: {e}")

finally:
    try:
        cursor.close()
        conn.close()
    except:
        pass
