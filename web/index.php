<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="A web-based weather monitoring system that provides real-time weather data and analytics.">
        <meta name="keywords" content="weather, monitoring, system, real-time, data, analytics">
        <meta name="author" content="Amr Belal, Mostafa Ibrahim, Mohamed Khaled, Mohamed Gamal, Mazen Khaled, Nora Mohamed">
        <!-- <meta http-equiv="refresh" content="10"> -->

        <script src="..\ChartFile\plotly-latest.min.js"></script>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" type="text/css" href="style-iot-data.css">
        <script src="script.js" defer></script>
        <!-- <script src="script-iot-data.js" defer></script> -->

        
        <title>Weather Monitoring System</title>
        
        <?php
            // insert database connection
            require 'ConnectDB.php';
            
            
            $Current_time = date('Y-m-d H:i:s');
            // $sql_insert = "INSERT INTO Temperature
            //             VALUES('$Current_time','1234', False, '01', '25.5', '60.4')";
            // $SubmetData = $ourconn->query($sql_insert);
            // if ($SubmetData === TRUE) {
            // } 
            // else {
            //     echo $ourconn->error;
            // }
            
            $sql_insert = "Select * from Temperature";
            $result = $ourconn->query($sql_insert);
            if ($result ->num_rows >0){
                echo "<h2>Temperature Table:</h2>";
                echo "<table border='1' cellpadding='8'>";
                echo " <tr><th>Time</th><th>Wind</th><th>LDR</th><th>Rain</th><th>Temperature (°C)</th><th>Humidity (%)</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row["MyTime"]."</td>";
                    echo "<td>".$row["Wind"]."</td>";
                    echo "<td>".$row["LDR"]."</td>";
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
            require 'IOTData.php';
            echo "The last recorded time (from array T) is: " . end($Temp) . "<br>";            // $ourconn->close();
            // $outlook = $Outlook;
            ?> 
    
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
                <p id ="actual-temperature"><?php echo end($Temp); ?></p>
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
        <div>
            <table class="table-iot-data">
                <tr>
                <?php
                    $last_temp = end($Temp);
                    $last_humidity = end($H);
                    $last_wind = end($W);
                    $last_rain = end($R);
                    $last_ldr = end($L);

                    $temp_status = "";
                    if ($last_temp <= 5) {
                        $temp_status = "Cool";
                    } elseif ($last_temp <= 35) {
                        $temp_status = "Mild";
                    } else {
                        $temp_status = "Hot";
                    }
                ?>
                <td id="outlook-data" class="data-card">
                    <span class="card-label">Outlook</span>
                    <!-- PHP initial value (will be overwritten by JS) -->
                    <span class="card-value decision-value maybe"><?php echo $last_ldr; ?></span>
                </td>
                
                <!-- TEMPERATURE DATA -->
                <td id="temp-data" class="data-card">
                    <span class="card-label">Temperature</span>
                    <span class="card-value"><?php echo $last_temp . " °C"; ?></span>
                </td>
                
                <!-- HUMIDITY DATA -->
                <td id="humidity-data" class="data-card">
                    <span class="card-label">Humidity</span>
                    <span class="card-value"><?php echo $last_humidity . " %"; ?></span>
                </td>
                
                <!-- WIND DATA -->
                <td id="wind-data" class="data-card">
                    <span class="card-label">Wind (Raw)</span>
                    <span class="card-value"><?php echo $last_wind; ?></span>
                </td>
                
                <!-- PLAY GOLF DECISION -->
                <td id="playgolf-data" class="data-card decision-card">
                    <span class="card-label">Play Golf?</span>
                    <!-- The value and color class will be set by JavaScript -->
                    <span class="card-value decision-value maybe">Calculating...</span>
                </td>
                </tr>
            </table>
        </div>


        <div>
            <table class="chart-container">
                <tr>
                    <td id='outlook-chart' class="chart"></td>
                    <td id='temp-chart' class="chart"></td>
                </tr>
                <tr>
                    <td id='rain-chart' class="chart"></td>
                    <td id='humidity-chart' class="chart"></td>
                </tr>
                <tr>
                    <td id='ldr-chart' class="chart"></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="content-section hidden" id="iot-control-content">
        <h3>Control Panel</h3>
        <p>This section allows you to send commands to devices.</p>
    </div>

    
    </body>
    </html>