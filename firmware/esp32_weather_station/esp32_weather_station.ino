#include <WiFi.h>                 // Wi-Fi library for ESP32
#include <DHT.h>                  // Library for DHT Temperature and Humidity Sensor
#include <HTTPClient.h>           // Library for HTTP POST requests


// -------------------- DHT Sensor Type --------------------
#define DHT_TYPE DHT11            // Choose DHT22 or DHT11

// -------------------- Network Settings --------------------
IPAddress MyIP(10,17,208,130);       // write esp ip
IPAddress MyGateway(10,17,208,138);  // write gateway
IPAddress MySubnet(255,255,255,0);


const char* ssid = "Dark";            // write wi-fi name
const char* password ="22446688";     // write wi-fi password

// Target URL for sending sensor data
String URL = "http://10.17.208.89:8000/ESP32.php";  // replace this ip with your device ip

// -------------------- RGB LED Pins --------------------
#define LED_R_PIN 19
#define LED_G_PIN 18
#define LED_B_PIN 5

// -------------------- Sensor Pins --------------------
#define POT_PIN 34               // Variable Resistor Pin (0-4095)
#define RAIN_SENSOR_PIN 35       // Rain Drop Sensor Pin (0=Wet, 4095=Dry)
#define DHT_PIN 22               // Temp. & Humidity Sensor Pin
#define LDR_PIN 27               // LDR Sensor Pin (0 or 1)

// -------------------- PWM Settings for RGB LED --------------------
const int freq = 5000;           // PWM frequency (Hz)
const int resolution = 8;        // 8-bit resolution (0–255)
const int redChannel = 0;        // PWM channel for Red LED
const int greenChannel = 1;      // PWM channel for Green LED
const int blueChannel = 2;       // PWM channel for Blue LED

// Create a DHT sensor object
DHT dht(DHT_PIN, DHT_TYPE);

// -------------------- Helper: Set RGB LED Color --------------------
void set_rgb_color(int r, int g, int b) {
  ledcWrite(redChannel, r);
  ledcWrite(greenChannel, g);
  ledcWrite(blueChannel, b);
}
// void set_rgb_color(int r, int g, int b) {
//   analogWrite(LED_R_PIN, r);
//   analogWrite(LED_G_PIN, g);
//   analogWrite(LED_B_PIN, b);
// }

// -------------------- Setup Function --------------------
void setup() {
  Serial.begin(115200);
  Serial.println(WiFi.localIP());

  dht.begin();     // Initialize DHT sensor

  // --- RGB LED PWM Setup ---
  ledcSetup(redChannel, freq, resolution);
  ledcSetup(greenChannel, freq, resolution);
  ledcSetup(blueChannel, freq, resolution);

  ledcAttachPin(LED_R_PIN, redChannel);
  ledcAttachPin(LED_G_PIN, greenChannel);
  ledcAttachPin(LED_B_PIN, blueChannel);

  pinMode(LDR_PIN, INPUT);

  // Configure Static IP BEFORE WiFi.begin
  WiFi.begin(ssid, password);
  WiFi.config(MyIP, MyGateway, MySubnet);

  // Wait for WiFi Connection
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.println("Connecting to WiFi...");
  }

  Serial.println("Connected!");
  Serial.print("ESP32 IP: ");
  Serial.println(WiFi.localIP());}

// -------------------- Main Loop --------------------
void loop() {

  // Read Variable Resistor (0–4095)
  int potValue = analogRead(POT_PIN);

  // Read LDR State (0 = Dark, 1 = Light)
  String LDRValue = (digitalRead(LDR_PIN) == HIGH) ? "HIGH" : "LOW";

  // Read Rain Drop Sensor (0=Wet, 4095=Dry)
  int rainValue = analogRead(RAIN_SENSOR_PIN);

  // Read Temperature and Humidity
  float h = dht.readHumidity();
  float t = dht.readTemperature();

  // Control RGB based on Rain Sensor
  if (rainValue < 3000) {
    // Rainy → Red Color
    set_rgb_color(255, 0, 0);
  } 


  // Build Data String (POST)
  String Data = 
    "Wind_Data=" + String(potValue) +
    "&LDR_Data=" + LDRValue +
    "&Rain_Data=" + String(rainValue) +
    "&Temp_Data=" + String(t) +
    "&Humd_Data=" + String(h);

  // Send Data to Server
  HTTPClient http;
  http.begin(URL);
  http.addHeader("Content-Type","application/x-www-form-urlencoded");

  int httpCode = http.POST(Data);

  Serial.print("httpCode: ");
  Serial.println(httpCode);

  String payload = http.getString();
  Serial.print("payload : ");
  Serial.println(payload);

  Serial.println("--------------------------------");

  // -------- Parse payload: "mode,action" --------
  int commaIndex = payload.indexOf(',');
  if (commaIndex == -1) {
    delay(2000);
    return;
  }

  String mode = payload.substring(0, commaIndex);       // 0 or 1
  String action = payload.substring(commaIndex + 1);    // RED / GREEN / BLUE / Play / Not Play

  set_rgb_color(0, 0, 0);

// ----------- Manual Mode (Control = 0) ------------
  if (mode == "0") {

    if (action == "RED") {
      set_rgb_color(255, 0, 0);
    }
    else if (action == "GREEN") {
      set_rgb_color(0, 255, 0);
    }
    else if (action == "BLUE") {
      set_rgb_color(0, 0, 255);
    }
  }


 // ----------- AI Mode (Control = 1) ------------
  else if (mode == "1") {

    if (action == "Play") {
      // Green → delay → Blue
      set_rgb_color(0, 255, 0);
      delay(900);
      set_rgb_color(0, 0, 255);
      delay(400);
      set_rgb_color(0, 255, 0);
    }

    else if (action == "Not Play") {
      // Red → delay → Blue
      set_rgb_color(255, 0, 0);
      delay(900);
      set_rgb_color(0, 0, 255);
      delay(400);
      set_rgb_color(255, 0, 0);
    }
  }

  delay(2000);
}

