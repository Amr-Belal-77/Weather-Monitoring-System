<?php
// ----------- Connect to the Database -------------
require 'ConnectDB.php';

// ----------- Insert Data into the Database IOT ===>> MySQL -------------
if (isset($_POST["Wind_Data"])) {

    $data_wind      = $_POST["Wind_Data"];
    $data_ldr       = $_POST["LDR_Data"];     // HIGH / LOW
    $data_rain      = $_POST["Rain_Data"];
    $data_temp      = $_POST["Temp_Data"];
    $data_humidity  = $_POST["Humd_Data"];

    // ============================================
    // 1) Call PythonESP32.py instead of ModelPred.py
    // ============================================

    // Full path to Python interpreter
    $python = "C:\\Users\\LOQ\\AppData\\Local\\Programs\\Python\\Python311\\python.exe";

    // Full path to PythonESP32.py
    $script = __DIR__ . DIRECTORY_SEPARATOR . "PythonESP32.py";

    // Build the command
    $cmd = "\"$python\" \"$script\" $data_wind $data_ldr $data_rain $data_temp $data_humidity 2>&1";

    // Execute Python script
    $result = shell_exec($cmd);

    // Remove spaces/newline
    $Rec_Data = trim($result);

    // ============================================
    // 2) Insert Raw Sensor Data into MySQL (same as before)
    // ============================================
    $Current_time = date('Y-m-d H:i:s');

    $sql = "INSERT INTO Temperature VALUES ('$Current_time', '$data_wind', '$data_ldr', '$data_rain', '$data_temp', '$data_humidity')";
    $ourconn->query($sql);

    // ============================================
    // 3) Decide What Value to Return to ESP32
    // ============================================
    // ========== MySQL to AI Model Integration ==========
    $query = "SELECT * FROM IOTControl;";
    $data  = $ourconn->query($query);

    $V = "";
    $ControlValue = "";  // قيمة التحكم

    if ($data->num_rows > 0) {

        // ناخذ أول صف فقط
        $row = $data->fetch_assoc();

        // نخزن Control مرة واحدة
        $ControlValue = $row["Control"];

        if ($ControlValue == "0") {
            $V = $row["RGBLED"];
        } 
        else if ($ControlValue == "1") {
            $V = $row["AIpred"];
        }
    }

    // نطبع القيمة للـ ESP32
    echo $ControlValue . "," . $V;

    $ourconn->close();

}
?>
