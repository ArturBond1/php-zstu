<!DOCTYPE html>
<html>
<head>
    <title>Редагувати діагноз</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Редагувати діагноз</h1>

    <form action="{{ route('diagnoses.update', $diagnosis->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Назва:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $diagnosis->name }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Опис:</label>
            <textarea name="description" id="description" class="form-control">{{ $diagnosis->description }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Зберегти зміни</button>
        <a href="{{ route('diagnoses.index') }}" class="btn btn-secondary">Назад</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
