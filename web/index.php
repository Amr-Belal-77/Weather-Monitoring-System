<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="A web-based weather monitoring system that provides real-time weather data and analytics.">
        <meta name="keywords" content="weather, monitoring, system, real-time, data, analytics">
        <meta name="author" content="Amr Belal, Mostafa Ibrahim, Mohamed Khaled, Mohamed Gamal, Mazen Khaled, Nora Mohamed">
        <!-- <meta http-equiv="refresh" content="10"> -->

        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="script.js" defer></script>

        
        <title>Weather Monitoring System</title>
        
        <!-- <?php
            // insert database connection
            require 'ConnectDB.php';
            
            $Current_time = date('Y-m-d H:i:s');
            $sql_insert = "INSERT INTO Temperature
                        VALUES('$Current_time','1234', 'True', '01', '25.5', '60.4')";
            $SubmetData = $ourconn->query($sql_insert);
            if ($SubmetData === TRUE) {
            } 
            else {
                echo $ourconn->error;
            }
            
            $sql_insert = "Select * from Temperature";
            $result = $ourconn->query($sql_insert);
            if ($result ->num_rows >0){
                echo "<h2>Temperature Table:</h2>";
                echo "<table border='1' cellpadding='8'>";
                echo " <tr><th>Time</th><th>Wind</th><th>LDT</th><th>Rain</th><th>Temperature (°C)</th><th>Humidity (%)</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row["MyTime"]."</td>";
                    echo "<td>".$row["Wind"]."</td>";
                    echo "<td>".$row["LDT"]."</td>";
                    echo "<td>".$row["Rain"]."</td>";
                    echo "<td>".$row["Temp"]."</td>";
                    echo "<td>".$row["Humidity"]."</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } 
            else {
                echo "No data found!";
            }
            $ourconn->close();
            ?> -->
    
    </head>
    <body>
    
    <dev class ="main-header">
        <div class="header">
            <h1 style="text-align:center;">Weather Monitoring System</h1>
        </div>
        <div class = "videobackground">
            <video autoplay muted loop id="myVideo">
                <source src="morning.mp4" type="video/mp4">
            </video>
            <div class ="titleOnVideo">
                <p >The weather Now is </p>
                <p id ="actual-temperature">22C</p>
            </div>

            <div class="weekly-forecast">
                <div class="daily-item">
                    <span id="day-label-1">Saturday </span>
                    <span id="temp-value-1">29&deg;C</span>
                </div>
                <div class="daily-item">
                    <span class="day-label-2">Sunday </span>
                    <span id="temp-value-2">30&deg;C</span>
                </div>
                <div class="daily-item">
                    <span id="day-label-3">Monday </span>
                    <span id="temp-value-3">31&deg;C</span>
                </div>
                <div class="daily-item">
                    <span id="day-label-4">Tuesday </span>
                    <span id="temp-value-4">25&deg;C</span>
                </div>
                <div class="daily-item">
                    <span id="day-label-5">Wednesday </span>
                    <span id="temp-value-5">33&deg;C</span>
                </div>
                <div class="daily-item">
                    <span id="day-label-6">Thursday </span>
                    <span id="temp-value-6">28&deg;C</span>
                </div>
                <div class="daily-item">
                    <span id="day-label-7">Friday </span>
                    <span id="temp-value-7">30&deg;C</span>
                </div>
            </div>    
        </div>
    </dev>

    <div class="control-box">

            <div class="title-control-box">
                <h2 id="title-control-box">IOT Data</h2>
            </div>

            <div class ="button-control-box">
                <div class="button-data">
                    <button class="iot-section-button" id ="button-data" style="background-color: #fcf8e3">IOT Data</button>
                </div>
                <div class="button-control">
                    <button class="iot-section-button" id="button-control" style="background-color: #d4edda;">IOT Control</button>
                </div>
            </div>
    </div>
    
    <div class="content-section" id="iot-data-content">
        <h3>Data Dashboard</h3>
        <p>This section shows live sensor data.</p>
    </div>

    <div class="content-section hidden" id="iot-control-content">
        <h3>Control Panel</h3>
        <p>This section allows you to send commands to devices.</p>
    </div>

    
    </body>
    </html>