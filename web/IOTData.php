
<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>


<?php
require 'ConnectDB.php';

// ========== Load the data from the Database ==========
 $Cairo = "SELECT * FROM Temperature;";
 $Egypt = $ourconn->query($Cairo);
 if ($Egypt->num_rows>0) {
 $T = array();
 $W = array();
 $L = array();
 $R = array();
 $Temp = array();
 $H = array();

 while($row = $Egypt->fetch_assoc()) {
        array_push($T, $row["MyTime"]);
        array_push($W, (int)$row["Wind"]);
        array_push($L, $row["LDR"]);
        array_push($R, (int)$row["Rain"]);
        array_push($Temp, (float)$row["Temp"]);
        array_push($H, (float)$row["Humidity"]);
 }
    $Lth = count($T);
 } 

    // Read latest value from IOTControl
    $golf = "Maybe";

    $Q = "SELECT AIpred FROM IOTControl LIMIT 1;";
    $res = $ourconn->query($Q);

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $golf = $row["AIpred"];   // Play / Not Play
    }
?>

<script>
    //  Read one value from PHP Code to JavaScript Code
    var Lg = <?=$Lth?>;


var Lg = <?=$Lth ?? 0?>; // Use null coalescing to handle $Lth not being set (if num_rows is 0)
    var T = <?= json_encode($T ?? []) ?>;
    var W = <?= json_encode($W ?? []) ?>;
    var L = <?= json_encode($L ?? []) ?>;
    var R = <?= json_encode($R ?? []) ?>;
    var Temp = <?= json_encode($Temp ?? []) ?>;
    var H = <?= json_encode($H ?? []) ?>;

var MyTime = T.slice(-1);
var Wind =  W.slice(-1);
var LDR =  L.slice(-1);
var Rain =  R.slice(-1);
var Tm =  Temp.slice(-1);
var Hum =  H.slice(-1);

 // ----------- Text Display -------------
// Outlook
 Outlook = "";
 if (LDR == "LOW"){
 Outlook = "Sunny";}
 else if (Rain<3000){ 
Outlook = "Rainy";
 }
 else {
 Outlook = "Overcast";
 }

  // Temperature
 Temperature = "";
 if (Tm <= 28){
 Temperature = "Cool";
 }
 else if (Tm <= 35){
 Temperature = "Mild";
 }
 else {
 Temperature = "Hot";
 }

 // Humidity
 Humidity = ""
 if (Hum <= 60){
 Humidity = "Normal";
 }
 else {
 Humidity = "High";
 }
 // Windy
 Windy = ""
 if (Wind <= 1500){
 Windy = "False";
 }
 else {
 Windy = "True";
 }

// -----------------------------------------------------------------------------

var PlayGolf = "<?= $golf ?>"; 

// -----------------------------------------------------------------------------
     //  ----------- Display in HTML -------------
    document.getElementById("outlook-value").innerText = Outlook;
    document.getElementById("temp-value").innerText = Temperature;
    document.getElementById("humidity-value").innerText = Humidity;
    document.getElementById("wind-value").innerText = Windy;
    document.getElementById("playgolf-value").innerText = PlayGolf;

    // document.getElementById("playgolf-data").innerText = `Play Golf\n `;

// ----------- Data Visualization -------------
// Wind Sensor (Variable Sensor)
var data = [{
            x: T,
            y: W,
            mode:"line",
        line: { color: '#3498db' }}];
var layout = {  
            xaxis: {title: "Time"},
            yaxis: {title: "Speed"},
            title: "Wind Speed",
            plot_bgcolor: '#f9f9f9',
            paper_bgcolor: '#ffffff'};

Plotly.newPlot("wind-chart", data, layout);


// Temperature Sensor (DH11 or DH22)
var data = [{
            x: T,
            y: Temp,
            mode:"line",
            line: { color: '#e74c3c' }}];
var layout = {  
            xaxis: {title: "Time"},
            yaxis: {title: "Celsius (°C)"},
            title: "Temperature",
            plot_bgcolor: '#f9f9f9',
            paper_bgcolor: '#ffffff'};
Plotly.newPlot("temp-chart", data, layout);


// Rain drop Sensor
var data = [{
            x: T,
            y: R,
            mode:"line",
            line: { color: '#9b59b6'}}];
var layout = {  
            xaxis: {title: "Time"},
            yaxis: {title: "(Data)"},
            title: "Rain Drop Sensor",
            plot_bgcolor: '#f9f9f9',
            paper_bgcolor: '#ffffff'};
Plotly.newPlot("rain-chart", data, layout);


// Humidity Sensor (DH11 or DH22)
var data = [{
            x: T,
            y: H,
            mode:"line",
        line: { color: '#2ecc71' }}];
var layout = {  
            xaxis: {title: "Time"},
            yaxis: {title: "(%)"},
            title: "Humidity",
            plot_bgcolor: '#f9f9f9',
            paper_bgcolor: '#ffffff'};
Plotly.newPlot("humidity-chart", data, layout);


// LDR Sensor 
var data = [{
            x: T,
            y: L.map(value => (value === "HIGH" ? 1 : 0)),
            mode:"line",
            line: { color: '#f39c12' }}];
var layout = {  
            xaxis: {title: "Time"},
            yaxis: {title: "(Data)"},
            title: "LDR Sensor",
        plot_bgcolor: '#f9f9f9',
        paper_bgcolor: '#ffffff'};
Plotly.newPlot("ldr-chart", data, layout);

</script>