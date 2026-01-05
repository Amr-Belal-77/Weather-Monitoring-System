# System Architecture — Weather Monitoring System (ESP32 + PHP + MySQL + Python ML)

This document explains the full system architecture, data flow, and how each component interacts.

---

## 1) High-Level Overview

The system collects real-time sensor data using **ESP32**, sends it to a local **PHP server**, stores it in **MySQL**, and visualizes it in a web dashboard.  
In **AI Mode**, a Python ML pipeline predicts **Play / Not Play** and updates the database decision.

---

## 2) Components

### 2.1 ESP32 (Edge Device)
Responsibilities:
- Reads sensors:
  - **DHT22** (Temperature, Humidity)
  - LDR (HIGH/LOW)
  - Rain sensor
  - Wind value (sensor or simulated)
- Sends readings to backend using **HTTP POST**
- Receives backend response `Control,Value`
- Applies action:
  - Manual: RGB LED color
  - AI: decision output (Play/Not Play)

### 2.2 PHP Backend (Middleware)
Files:
- `web/ESP32.php`:
  - Receives POST from ESP32
  - Triggers Python script for AI (AI Mode)
  - Inserts sensor row into DB (depending on your final design choice)
  - Reads current control state from DB (`IOTControl`)
  - Responds to ESP32 with `Control,Value`

- `web/IOTControl.php`:
  - Updates `IOTControl` table based on UI selection (Manual/AI + RGB)

### 2.3 MySQL Database (Storage + Control State)
Database: `WeatherDataset`

Tables:
- `Temperature`: stores sensor readings over time
- `IOTControl`: stores current mode + latest command / AI decision

### 2.4 Dashboard (Visualization)
- `web/index.php` loads the UI and refreshes periodically.
- `web/IOTData.php` provides latest values and time-series arrays.
- Plotly charts show:
  - Wind, Temp, Rain, Humidity, LDR

### 2.5 Python ML (AI Mode)
- `PythonESP32.py`:
  - Converts sensor values into categorical features
  - Loads model (`joblib`)
  - Predicts: `Play` or `Not Play`
  - Updates `IOTControl.AIpred` so ESP32 & dashboard can read it

---

## 3) Data Flow (Mermaid Diagram)

```mermaid
flowchart LR
  A[ESP32 Sensors\n(DHT22, LDR, Rain, Wind)] -->|HTTP POST| B[PHP Endpoint\nESP32.php]
  B --> C[(MySQL\nWeatherDataset)]
  C --> D[Dashboard\nindex.php + Plotly]
  B -->|AI Call| E[Python ML\nPythonESP32.py]
  E --> C
  C --> B
  B -->|Response: Control,Value| A
