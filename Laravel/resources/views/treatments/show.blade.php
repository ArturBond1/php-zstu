<!DOCTYPE html>
<html>
<head>
    <title>Перегляд лікування</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Інформація про лікування</h1>

    <div class="mb-3">
        <strong>ID:</strong> {{ $treatment->id }}
    </div>
    <div class="mb-3">
        <strong>Прийом:</strong> <a href="{{ route('appointments.show', $treatment->appointment->id) }}">Прийом #{{ $treatment->appointment->id }}</a>
    </div>
    <div class="mb-3">
        <strong>Опис:</strong> {{ $treatment->description }}
    </div>
    <div class="mb-3">
        <strong>Медикамент:</strong> {{ $treatment->medication }}
    </div>
    <div class="mb-3">
        <strong>Дозування:</strong> {{ $treatment->dosage }}
    </div>

    <a href="{{ route('treatments.edit', $treatment->id) }}" class="btn btn-warning">Редагувати</a>
    <a href="{{ route('treatments.index') }}" class="btn btn-secondary">Назад</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
