<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hőmérséklet Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 40px;
        }
        h2 {
            color: #555;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background-color: #f8f9fa;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            font-weight: bold;
            color: #333;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .temperature {
            font-size: 16px;
            font-weight: bold;
            color: #dc3545;
        }
        .chart-wrapper {
            position: relative;
            height: 300px;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        .no-data {
            padding: 20px;
            text-align: center;
            color: #999;
            font-style: italic;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            background-color: #007bff;
            color: white;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard</h1>

        <div class="section">
            <h2>Legfrissebb Hőmérséklet</h2>
            @if (count($latestTemperatures) > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Város</th>
                            <th>Hőmérséklet</th>
                            <th>Mérés ideje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestTemperatures as $cityId => $data)
                            <tr>
                                <td>{{ $data['name'] }}</td>
                                <td class="temperature">{{ $data['temperature'] }}°C</td>
                                <td>{{ $data['measured_at']->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">Nincs még mérési adat.</div>
            @endif
        </div>

        <!-- Grafikonok városonként -->
        <div class="section">
            <h2>Hőmérséklet Grafikonok (Utolsó 10 Mérés)</h2>
            @foreach ($cities as $city)
                @if ($city->weatherMeasurements->count() > 0)
                    <h3>{{ $city->name }}</h3>
                    <div class="chart-wrapper">
                        <canvas id="chart-{{ $city->id }}"></canvas>
                    </div>
                    <script>
                        const ctx{{ $city->id }} = document.getElementById('chart-{{ $city->id }}').getContext('2d');
                        const measurements{{ $city->id }} = {!! json_encode($city->weatherMeasurements->reverse()->values()) !!};

                        new Chart(ctx{{ $city->id }}, {
                            type: 'line',
                            data: {
                                labels: measurements{{ $city->id }}.map(m => new Date(m.measured_at).toLocaleString('hu-HU', {
                                    year: 'numeric',
                                    month: '2-digit',
                                    day: '2-digit',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })),
                                datasets: [{
                                    label: '{{ $city->name }} - Hőmérséklet (°C)',
                                    data: measurements{{ $city->id }}.map(m => m.temperature),
                                    borderColor: '#007bff',
                                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                                    borderWidth: 2,
                                    tension: 0.4,
                                    fill: true,
                                    pointRadius: 5,
                                    pointBackgroundColor: '#007bff',
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: false,
                                        title: {
                                            display: true,
                                            text: 'Hőmérséklet (°C)'
                                        }
                                    }
                                }
                            }
                        });
                    </script>
                @endif
            @endforeach
        </div>

        <a href="{{ route('cities.index') }}" class="btn">← Vissza a várostlistához</a>
    </div>
</body>
</html>
