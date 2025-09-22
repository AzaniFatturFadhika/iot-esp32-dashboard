_
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard IoT</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/chart.js"></script>
    <style>
        body {
            font-family: Arial;
            margin: 40px;
        }

        canvas {
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <h2>📈 Dashboard Kecerahan & Suhu Ruangan</h2>

    <canvas id="chartSuhu" width="600" height="300"></canvas>
    <canvas id="chartLumen" width="600" height="300"></canvas>

    <script>
        const ctxSuhu = document.getElementById('chartSuhu').getContext('2d');
        const chartSuhu = new Chart(ctxSuhu, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Suhu (°C)',
                    data: [],
                    borderColor: 'red',
                    fill: false
                }]
            },
        });

        const ctxLumen = document.getElementById('chartLumen').getContext('2d');
        const chartLumen = new Chart(ctxLumen, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Lumen (Lux)',
                    data: [],
                    borderColor: 'orange',
                    fill: false
                }]
            },
        });

        function loadData() {
            $.getJSON('./data_suhu.php', function(data) {
                chartSuhu.data.labels = data.map(d => d.waktu);
                chartSuhu.data.datasets[0].data = data.map(d => d.suhu);
                chartSuhu.update();
            });

            $.getJSON('./data_lumen.php', function(data) {
                chartLumen.data.labels = data.map(d => d.waktu);
                chartLumen.data.datasets[0].data = data.map(d => parseFloat(d.lumen));
                chartLumen.update();
            });
        }

        setInterval(loadData, 3000); // Refresh setiap 3 detik
        loadData(); // Load pertama
    </script>
</body>

</html>