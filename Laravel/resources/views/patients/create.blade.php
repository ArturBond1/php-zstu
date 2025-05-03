<!DOCTYPE html>
<html>
<head>
    <title>Додати нового пацієнта</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Додати нового пацієнта</h1>

    <form action="{{ route('patients.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="first_name" class="form-label">Ім'я:</label>
            <input type="text" name="first_name" id="first_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Прізвище:</label>
            <input type="text" name="last_name" id="last_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="date_of_birth" class="form-label">Дата народження:</label>
            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control">
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Адреса:</label>
            <textarea name="address" id="address" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="phone_number" class="form-label">Телефон:</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Зберегти пацієнта</button>
        <a href="{{ route('patients.index') }}" class="btn btn-secondary">Назад</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
