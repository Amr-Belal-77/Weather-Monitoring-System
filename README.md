# 🌦️ Weather Monitoring System (ESP32 + PHP + MySQL + Python ML)

An end-to-end **IoT Weather Monitoring** project where an **ESP32** reads sensor data (including **DHT22**) and sends it to a **PHP server** via HTTP POST. The backend stores readings in **MySQL**, visualizes them in a **web dashboard** (Plotly charts), and supports **Manual control** or **AI mode** (Python ML) to output a decision: **PLAY / NOT PLAY**.

---

## ✅ Key Features
- **Real-time ingestion** from ESP32 → PHP endpoint (`ESP32.php`)
- **MySQL persistence** for historical tracking
- **Dashboard UI** (`index.php`) with:
  - Auto refresh (every ~10 seconds)
  - Plotly charts + status indicators
  - Section switching: **IOT Data** / **IOT Control**
- **Two Modes**
  - **Manual Mode**: select RGB LED color (RED/GREEN/BLUE)
  - **AI Mode**: Python ML predicts **Play / Not Play** and updates DB decision

---

## 🧱 Architecture (High-Level Flow)

ESP32 (Sensors)
→ HTTP POST (form-url-encoded)
→ PHP Backend (ESP32.php)
→ MySQL (WeatherDataset)
→ Dashboard (index.php + JS + Plotly)
→ ESP32 Response: `Control,Value`

**Response examples**
- `0,RED`  → Manual mode, set RGB LED to RED
- `1,Play` → AI mode, use model decision = Play

---

## 🔌 Hardware
**Microcontroller**
- ESP32 Dev Module

**Sensors (minimum)**
- DHT22 (Temperature + Humidity)
- LDR (Light) — sends `HIGH/LOW`
- Rain sensor
- Wind input (sensor or simulated value)

**Actuator**
- RGB LED (Manual mode)

> Pin mapping depends on your ESP32 firmware wiring. Keep pin definitions in the ESP32 `.ino` file.

---

## 🌐 Backend (PHP)
The backend is **pure PHP** (no Node/Flask). You can run it using the built-in PHP server.

### Main Files (web/)
- `ESP32.php` → Receives ESP32 POST data, triggers Python ML, and returns `Control,Value`
- `index.php` → Dashboard UI (charts + status + controls)
- `IOTData.php` → Reads latest DB data and provides values for charts/status
- `IOTControl.php` → Updates mode + RGB selection (Manual/AI)
- `script.js` → UI toggling (IOT Data / IOT Control)
- `style.css` → UI styling

---

## 📡 API Contract (ESP32 → PHP)

### Endpoint
`POST /ESP32.php`

### Content-Type
`application/x-www-form-urlencoded`

### Required Fields
| Field | Example | Notes |
|------|---------|------|
| `Wind_Data` | `12` | integer |
| `LDR_Data` | `HIGH` | `HIGH` / `LOW` |
| `Rain_Data` | `0` | integer |
| `Temp_Data` | `25.3` | float |
| `Humd_Data` | `60.1` | float |

### Response Format
`<Control>,<Value>`

- If `Control = 0` → `Value = RGBLED` (`RED/GREEN/BLUE`)
- If `Control = 1` → `Value = AIpred` (`Play/Not Play`)

---

## 🗄️ Database (MySQL)

### Database Name
`WeatherDataset`

### Schema (exact)
```sql
CREATE DATABASE IF NOT EXISTS WeatherDataset;
USE WeatherDataset;

CREATE TABLE IF NOT EXISTS Temperature(
  MyTime datetime,
  Wind int(4),
  LDR varbinary(6),
  Rain int(4),
  Temp float(3,1),
  Humidity float(3,1)
);

CREATE TABLE IF NOT EXISTS IOTControl(
  Control varchar(1),
  RGBLED varchar(5),
  AIpred varchar(10)
);

INSERT INTO IOTControl VALUES ("0", "RED", "Not Play");
```

## 🔌 Hardware Connections (Pin Mapping Used in This Project)

> The following wiring reflects the exact connections used in this project (ESP32 + sensors + RGB LED).

### ✅ ESP32 Pin Mapping
| Component | ESP32 Pin | Type / Notes |
|---|---:|---|
| **DHT22 (Temperature & Humidity) – DATA** | **GPIO 22** | Digital |
| **LDR (Light Sensor)** | **GPIO 27** | Analog (ADC) via voltage divider |
| **Rain Sensor (AO)** | **GPIO 35** | Analog (ADC) |
| **Wind (Variable Resistor / Potentiometer)** | **GPIO 34** | Analog (ADC) |
| **RGB LED – Red** | **GPIO 19** | PWM output (use 220Ω resistor) |
| **RGB LED – Green** | **GPIO 18** | PWM output (use 220Ω resistor) |
| **RGB LED – Blue** | **GPIO 5** | PWM output (use 220Ω resistor) |

---

### 🧷 Wiring Details

#### 1) DHT22
- **VCC → 3.3V**
- **GND → GND**
- **DATA → GPIO 22**
- ✅ Recommended: add a **10kΩ pull-up resistor** between **DATA** and **VCC** if your DHT22 board does not already include it.

---

#### 2) LDR (Light Sensor) — Voltage Divider
LDR must be connected as a voltage divider to read an analog value:

- **3.3V → LDR → (Signal Node) → GPIO 27**
- **(Signal Node) → 10kΩ resistor → GND**

> GPIO 27 reads the light level as an analog value.

---

#### 3) Rain Sensor (Analog Output)
- **VCC → 3.3V or 5V** (depends on your sensor module)
- **GND → GND**
- **AO → GPIO 35** (Analog input)

---

#### 4) Wind Input (Variable Resistor / Potentiometer)
Wind is simulated using a potentiometer / variable resistor:

- One side → **3.3V**
- Other side → **GND**
- Middle pin (wiper) → **GPIO 34** (Analog input)

---

#### 5) RGB LED
Use series resistors for each channel (recommended **220Ω**):

- **R → GPIO 19** (with 220Ω resistor)
- **G → GPIO 18** (with 220Ω resistor)
- **B → GPIO 5** (with 220Ω resistor)

Common pin:
- If **Common Cathode** → connect common pin to **GND**
- If **Common Anode** → connect common pin to **3.3V** (LED logic may be inverted in code)

---


## 🤖 AI Mode (Python ML)

The project uses a Python script (e.g., `PythonESP32.py`) to:

1. Convert sensor readings into categorical features:

   * Outlook (Sunny/Rainy/Overcast)
   * Temp (Cool/Mild/Hot)
   * Humidity (Normal/High)
   * Windy (True/False)
2. Load the trained model (`*.joblib`)
3. Predict: **Play / Not Play**
4. Update `IOTControl.AIpred` in MySQL (so ESP32 can read the latest decision)

---

## ▶️ Run Locally (Recommended Setup)

### 1) Requirements

* PHP 8+
* MySQL Server + MySQL Workbench (or CLI)
* Python 3.x (for AI mode)
* ESP32 Arduino IDE / PlatformIO

---

### 2) MySQL Setup

1. Start MySQL server
2. Run the schema SQL (create DB + tables)

---

### 3) Python Setup (AI Mode)

use the existing `requirements.txt` (recommended):



Install:

```bash
pip install -r requirements.txt
```

---

### 4) Start PHP Server

From the `web/` folder:

```bash
php -S 0.0.0.0:8000
```

Open dashboard:

* [http://localhost:8000/index.php](http://localhost:8000/index.php)

---

### 5) Configure ESP32 URL

In your ESP32 firmware:

```
String URL = "http://<YOUR_PC_LAN_IP>:8000/ESP32.php";
```


