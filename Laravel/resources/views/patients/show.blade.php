<!DOCTYPE html>
<html>
<head>
    <title>Інформація про пацієнта</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Інформація про пацієнта</h1>

    <div class="mb-3">
        <strong>ID:</strong> {{ $patient->id }}
    </div>
    <div class="mb-3">
        <strong>Ім'я:</strong> {{ $patient->first_name }}
    </div>
    <div class="mb-3">
        <strong>Прізвище:</strong> {{ $patient->last_name }}
    </div>
    <div class="mb-3">
        <strong>Дата народження:</strong> {{ $patient->date_of_birth }}
    </div>
    <div class="mb-3">
        <strong>Адреса:</strong> {{ $patient->address }}
    </div>
    <div class="mb-3">
        <strong>Телефон:</strong> {{ $patient->phone_number }}
    </div>
    <div class="mb-3">
        <strong>Email:</strong> {{ $patient->email }}
    </div>

    <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning">Редагувати</a>
    <a href="{{ route('patients.index') }}" class="btn btn-secondary">Назад</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
