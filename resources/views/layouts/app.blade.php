<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f7f7f7; }
        .container { max-width: 960px; margin: 24px auto; padding: 16px; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .header { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 16px; }
        .header a { color: #1a202c; text-decoration: none; padding: 8px 12px; background: #eef2ff; border-radius: 6px; }
        .header a:hover { background: #ddd6fe; }
        .alert { padding: 12px 16px; margin-bottom: 16px; border-radius: 6px; background: #def7ec; color: #064e3b; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 10px; border: 1px solid #e5e7eb; text-align: left; }
        th { background: #eef2ff; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; margin-top: 4px; }
        button, .button { display: inline-block; padding: 8px 14px; color: #fff; background: #4338ca; border: none; border-radius: 6px; text-decoration: none; cursor: pointer; }
        button:hover, .button:hover { background: #3730a3; }
        .button-secondary { background: #64748b; }
        form { margin: 0; }
        .grid { display: grid; gap: 16px; }
        .field { margin-bottom: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ route('tenaga-kerja.index') }}">Tenaga Kerja</a>
            <a href="{{ route('pemagangan.index') }}">Pemagangan</a>
            <a href="{{ route('job-fair.index') }}">Job Fair</a>
            <a href="{{ route('job-fair-perusahaan.index') }}">Job Fair Perusahaan</a>
            <a href="{{ route('laporan.index') }}">Laporan</a>
        </div>

        @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
