<!DOCTYPE html>
<html>
<head>
    <title>Редагувати інформацію про лікаря</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Редагувати інформацію про лікаря</h1>

    <form action="{{ route('doctors.update', $doctor->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="first_name" class="form-label">Ім'я:</label>
            <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $doctor->first_name }}" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Прізвище:</label>
            <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $doctor->last_name }}" required>
        </div>

        <div class="mb-3">
            <label for="specialization" class="form-label">Спеціалізація:</label>
            <input type="text" name="specialization" id="specialization" class="form-control" value="{{ $doctor->specialization }}">
        </div>

        <div class="mb-3">
            <label for="phone_number" class="form-label">Телефон:</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ $doctor->phone_number }}">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $doctor->email }}">
        </div>

        <button type="submit" class="btn btn-primary">Зберегти зміни</button>
        <a href="{{ route('doctors.index') }}" class="btn btn-secondary">Назад</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
