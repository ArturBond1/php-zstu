<!DOCTYPE html>
<html>
<head>
    <title>Інформація про лікаря</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Інформація про лікаря</h1>

    <div class="mb-3">
        <strong>ID:</strong> {{ $doctor->id }}
    </div>
    <div class="mb-3">
        <strong>Ім'я:</strong> {{ $doctor->first_name }}
    </div>
    <div class="mb-3">
        <strong>Прізвище:</strong> {{ $doctor->last_name }}
    </div>
    <div class="mb-3">
        <strong>Спеціалізація:</strong> {{ $doctor->specialization }}
    </div>
    <div class="mb-3">
        <strong>Телефон:</strong> {{ $doctor->phone_number }}
    </div>
    <div class="mb-3">
        <strong>Email:</strong> {{ $doctor->email }}
    </div>

    <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-warning">Редагувати</a>
    <a href="{{ route('doctors.index') }}" class="btn btn-secondary">Назад</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
