<!DOCTYPE html>
<html>
<head>
    <title>Перегляд діагнозу</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Інформація про діагноз</h1>

    <div class="mb-3">
        <strong>ID:</strong> {{ $diagnosis->id }}
    </div>
    <div class="mb-3">
        <strong>Назва:</strong> {{ $diagnosis->name }}
    </div>
    <div class="mb-3">
        <strong>Опис:</strong> {{ $diagnosis->description }}
    </div>

    <a href="{{ route('diagnoses.edit', $diagnosis->id) }}" class="btn btn-warning">Редагувати</a>
    <a href="{{ route('diagnoses.index') }}" class="btn btn-secondary">Назад</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
