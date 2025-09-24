<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard IoT Realtime</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Inter', 'sans-serif']
                },
                backdropBlur: {
                    xs: '2px',
                },
            },
        },
    };
    </script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet" />

    <!-- jQuery + Chart.js -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0"></script>
</head>

<body class="bg-gradient-to-br from-slate-900 via-indigo-950 to-gray-900 text-white font-sans">

    <!-- Header -->
    <header class="sticky top-0 z-50 bg-slate-800/90 backdrop-blur-sm shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 text-center">
            <h1 class="text-3xl font-bold tracking-wide">📡 Dashboard IoT Realtime</h1>
            <p id="tanggal" class="text-indigo-300 text-sm mt-1"></p>
        </div>
    </header>

    <!-- Info Cards -->
    <section class="max-w-7xl mx-auto px-4 pt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl shadow-lg transition hover:shadow-xl">
            <h2 class="text-sm uppercase text-indigo-300">Suhu Ruangan Saat Ini</h2>
            <div id="suhuNow" class="text-5xl font-extrabold text-red-400 mt-2">-- °C</div>
        </div>
        <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl shadow-lg transition hover:shadow-xl">
            <h2 class="text-sm uppercase text-indigo-300">Kondisi Pencahayaan</h2>
            <div id="statusLumen" class="text-5xl font-extrabold text-yellow-300 mt-2">--</div>
        </div>
    </section>

    <!-- Charts -->
    <section class="max-w-7xl mx-auto px-4 py-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl shadow-lg">
            <h2 class="text-xl font-semibold mb-4 text-indigo-300">Grafik Suhu</h2>
            <canvas id="chartSuhu" height="250"></canvas>
        </div>
        <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl shadow-lg">
            <h2 class="text-xl font-semibold mb-4 text-indigo-300">Grafik LDR</h2>
            <canvas id="chartLumen" height="250"></canvas>
        </div>
    </section>

    <script>
    const ctxSuhu = document.getElementById('chartSuhu').getContext('2d');
    const chartSuhu = new Chart(ctxSuhu, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Suhu (°C)',
                data: [],
                borderColor: '#f87171',
                backgroundColor: 'rgba(248, 113, 113, 0.2)',
                fill: true
            }]
        },
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'minute',
                        displayFormats: {
                            minute: 'HH:mm'
                        }
                    },
                    title: {
                        display: true,
                        text: 'Waktu'
                    },
                    ticks: {
                        color: '#ddd'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Suhu (°C)'
                    },
                    // min: 0,
                    // max: 50,
                    ticks: {
                        color: '#ddd'
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#fff'
                    }
                }
            }
        }
    });

    const ctxLumen = document.getElementById('chartLumen').getContext('2d');
    const chartLumen = new Chart(ctxLumen, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Nilai LDR',
                data: [],
                borderColor: '#fde047',
                backgroundColor: 'rgba(253, 224, 71, 0.2)',
                fill: true
            }]
        },
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'minute',
                        displayFormats: {
                            minute: 'HH:mm'
                        }
                    },
                    title: {
                        display: true,
                        text: 'Waktu'
                    },
                    ticks: {
                        color: '#ddd'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Nilai LDR'
                    },
                    // min: 0,
                    // max: 4096,
                    ticks: {
                        color: '#ddd'
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#fff'
                    }
                }
            }
        }
    });

    function updateDate() {
        const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
            'Oktober', 'November', 'Desember'
        ];
        const today = new Date();
        const str = `${hari[today.getDay()]}, ${today.getDate()} ${bulan[today.getMonth()]} ${today.getFullYear()}`;
        $('#tanggal').text(str);
    }

    function loadData() {
        $.getJSON('data_suhu.php', function(data) {
            const waktu = data.map(d => new Date(d.waktu));
            const suhu = data.map(d => d.suhu);
            chartSuhu.data.labels = waktu;
            chartSuhu.data.datasets[0].data = suhu;
            chartSuhu.update();

            if (data.length > 0) {
                $('#suhuNow').text(`${suhu[suhu.length - 1]} °C`);
            }
        });

        $.getJSON('data_lumen.php', function(data) {
            const waktu = data.map(d => new Date(d.waktu));
            const lumen = data.map(d => parseFloat(d.lumen));
            chartLumen.data.labels = waktu;
            chartLumen.data.datasets[0].data = lumen;
            chartLumen.update();

            if (data.length > 0) {
                const latest = data[data.length - 1];
                $('#statusLumen').text(latest.status_ruangan || "--");
            }
        });
    }

    updateDate();
    loadData();
    setInterval(loadData, 3000);
    </script>
</body>

</html>