<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="A web-based weather monitoring system that provides real-time weather data and analytics.">
        <meta name="keywords" content="weather, monitoring, system, real-time, data, analytics">
        <meta name="author" content="Amr Belal, Mostafa Ibrahim, Mohamed Khaled, Mohamed Gamal, Mazen Khaled, Nora Mohamed">
        <meta http-equiv="refresh" content="10">

        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" type="text/css" href="style-iot-data.css">
        <script src="script.js" defer></script>
        <script src="script-iot-data.js" defer></script>

        
        <title>Weather Monitoring System</title>
        
        <?php
            // insert database connection
            // require 'ConnectDB.php';
            require 'IOTData.php';

            
            
            $Current_time = date('Y-m-d H:i:s');
            
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
                    $last_temp = $Temp ? end($Temp) : 0;
                    $last_humidity = $H ? end($H) : 0;
                    $last_wind = $W ? end($W) : 0;
                    $last_rain = $R ? end($R) : 0;
                    $last_ldr = $L ? end($L) : "LOW";


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
                    <span class="card-value decision-value maybe" id="outlook-value"></span>
                </td>
                
                <!-- TEMPERATURE DATA -->
                <td id="temp-data" class="data-card">
                    <span class="card-label">Temperature</span>
                    <span class="card-value" id="temp-value"><?php echo $last_temp . " °C"; ?></span>
                </td>
                
                <!-- HUMIDITY DATA -->
                <td id="humidity-data" class="data-card">
                    <span class="card-label">Humidity</span>
                    <span class="card-value" id="humidity-value"></span>
                </td>
                
                <!-- WIND DATA -->
                <td id="wind-data" class="data-card">
                    <span class="card-label">Wind (Raw)</span>
                    <span class="card-value" id="wind-value"></span>
                </td>
                
                <!-- PLAY GOLF DECISION -->
                <td id="playgolf-data" class="data-card decision-card">
                    <span class="card-label">Play Golf?</span>
                    <!-- The value and color class will be set by JavaScript -->
                    <span class="card-value decision-value maybe" id="playgolf-value"></span>
                </td>
                </tr>
            </table>
        </div>


        <!-- <div> -->
                <div class="chart-container">
                    <div id="wind-chart" class="chart"></div>
                    <div id="temp-chart" class="chart"></div>
                    <div id="rain-chart" class="chart"></div>
                    <div id="humidity-chart" class="chart"></div>
                    <div id="ldr-chart" class="chart"></div>
                </div>


        <!-- </div> -->
    </div>

    <div class="content-section hidden" id="iot-control-content">
        <form method="POST" class="mode-form">

            <div class="mode-strip">
                <button type="submit" name="Switch" value="RED" class="mode-btn mode-red">
                    Red
                </button>

                <button type="submit" name="Switch" value="GREEN" class="mode-btn mode-green">
                    Green
                </button>

                <button type="submit" name="Switch" value="BLUE" class="mode-btn mode-blue">
                    Blue
                </button>

                <button type="submit" name="Switch" value="AI Model" class="mode-btn mode-ai">
                    AI Model
                </button>
            </div>
        </form>
    </div>

        <?php require 'IOTData.php'; ?>   
        <?php require 'IOTControl.php'; ?>   

    </body>
    </html>