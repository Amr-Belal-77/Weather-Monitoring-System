import pandas as pd


# ------------------- Data Set--------------------------
data_set = pd.DataFrame({
    'Outlook': ['Rainy', 'Rainy', 'Overcast', 'Sunny', 'Sunny', 'Sunny', 'Overcast', 
                'Rainy', 'Rainy', 'Sunny', 'Rainy', 'Overcast', 'Overcast', 'Sunny'], 
    'Temp': ['Hot', 'Hot', 'Hot', 'Mild', 'Cool', 'Cool', 'Cool', 'Mild', 'Cool', 'Mild', 
                'Mild', 'Mild', 'Hot', 'Mild'],
    'Humidity': ['High', 'High', 'High', 'High', 'Normal', 'Normal', 'Normal', 'High', 
                'Normal', 'Normal', 'Normal', 'High', 'Normal', 'High'],
    'Windy': ['False', 'True', 'False', 'False', 'False', 'True', 'True', 'False', 'False', 
                'False', 'True', 'True', 'False', 'True'],
    'Play': ['No', 'No', 'Yes', 'Yes', 'Yes', 'No', 'Yes', 'No', 'Yes', 'Yes', 'Yes', 
                'Yes', 'Yes', 'No']
 })

# ------------------- Encoding --------------------------
from sklearn.preprocessing import LabelEncoder
le = LabelEncoder()

A = {'Outlook':'', 'Temp':'', 'Humidity':'', 'Windy':'', 'Play':''}

H = data_set.head(0).columns

for i in range(data_set.shape[1]):
    if data_set.dtypes.iloc[i] == 'object':
        data_set[data_set.columns[i]] = le.fit_transform(data_set[data_set.columns[i]])
        A[H[i]] = list(le.classes_)

#  --------------- Learning ------------------------
from sklearn.naive_bayes import CategoricalNB
X = data_set.iloc[:,:-1].values 
Y = data_set.iloc[:,-1].values
model=CategoricalNB()
model.fit(X,Y)

# --------------- Save The Model -------------------
import joblib
filename = "Mymodel.joblib"
joblib.dump(model, filename)
print("A =", A)