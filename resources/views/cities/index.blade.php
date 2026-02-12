<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Városok</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .form-inline {
            display: inline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Városok</h1>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif

        <a href="{{ route('cities.create') }}" class="btn btn-primary">+ Új város hozzáadása</a>
        <a href="{{ route('dashboard') }}" class="btn btn-primary" style="margin-left: 10px;">
            Dashboard
        </a>

        @if ($cities->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Város neve</th>
                        <th>Ország</th>
                        <th>Szélesség</th>
                        <th>Hosszúság</th>
                        <th>Műveletek</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cities as $city)
                        <tr>
                            <td>{{ $city->name }}</td>
                            <td>{{ $city->country }}</td>
                            <td>{{ number_format($city->latitude, 6) }}</td>
                            <td>{{ number_format($city->longitude, 6) }}</td>
                            <td>
                                <form action="{{ route('cities.destroy', $city->id) }}" method="POST" class="form-inline" onsubmit="return confirm('Biztosan törli ezt a várost?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Törlés</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Nincs még város a listában. <a href="{{ route('cities.create') }}">Adjon hozzá egyet!</a></p>
        @endif
    </div>
</body>
</html>
