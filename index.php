<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Visualisation</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // const myChart = new Chart(ctx, {...});
    </script>
</head>
<body>
<style>

    .container {
        display: flex;
        justify-content: space-between; /* Arrange charts side by side */
    }

    .chart {
        width: 400px; /* Adjust chart width as needed */
        height: 300px;
        max-width: 500px;
        margin: 30px;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 100vh;
    }

    .form-container {
        max-width: 500px;
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-weight: bold;
    }

    input[type="text"] {
        width: 100%;
        padding: 10px;
        border-radius: 4px;
        border: 1px solid #ccc;
        box-sizing: border-box;
        font-size: 16px;
    }

    input[type="submit"] {
        background-color: #4caf50;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 12px 20px;
        cursor: pointer;
        font-size: 16px;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    h1{
        text-align: center;
    }

    /* Limit chart's max width to 70vw */
</style>

<div class="form-container">
    <h2>Enter City Name</h2>
    <form id="cityForm" action="index.php" method="get">
        <div class="form-group">
            <label for="cityName">City Name:</label>
            <input type="text" id="cityName" name="cityName" placeholder="Enter city name" required>
        </div>
        <input type="submit" value="Submit">
    </form>
</div>
<!-- <h1>This is the rain and temperature data for your chosen city, enjoy : )</h1> -->


<?php 
$search = $_GET["cityName"];
$URLsearch = urlencode($search);
$apiendpoint = "http://api.weatherapi.com/v1/forecast.json?key=0a97a0b9c7bd418bb02144144241403&q=".$URLsearch."&days=10&aqi=no&alerts=no";
$response = file_get_contents($apiendpoint);

echo '<h1>This is the rain and temperature data for '.$search.' enjoy : )</h1>';

if ($response === false) {
    echo '<p class="error">Error retrieving data from the API.</p>';
} else {
    $data = json_decode($response, true);
    $arrayOfRain = array();
    $arrayOfTemperatures = array();

    foreach($data['forecast']['forecastday'] as $value){
        $arrayOfRain[] = $value['day']['totalprecip_mm'];
    }

    foreach($data['forecast']['forecastday'] as $value){
        $arrayOfTemperatures[] = $value['day']['avgtemp_c'];
    }
}
?>

<div class="container" id="chart-container">
    <canvas id="rainChart" class="chart"></canvas>
    <canvas id="tempChart" class="chart"></canvas>
</div>

<script>
    var ctx = document.getElementById('rainChart').getContext('2d');
    var rainValues = <?php echo json_encode($arrayOfRain); ?>;
    var labels = [];
    for(var i = 0; i < rainValues.length; i++) {
        labels.push("Day " + (i + 1));
    }
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Rainfall (mm)',
                data: rainValues,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>

<script>
    var ctx = document.getElementById('tempChart').getContext('2d');
    var tempVals = <?php echo json_encode($arrayOfTemperatures); ?>;
    var labels = [];
    for(var i = 0; i < tempVals.length; i++) {
        labels.push("Day " + (i + 1));
    }
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Temperature (°C)',
                data: tempVals,
                backgroundColor: 'rgba(255, 165, 0, 1)',
                borderColor: 'rgba(255, 0, 0, 1)',
                borderWidth: 2
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>

</body>
</html>
