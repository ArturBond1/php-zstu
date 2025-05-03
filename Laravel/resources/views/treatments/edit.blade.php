<!DOCTYPE html>
<html>
<head>
    <title>Редагувати лікування</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Редагувати лікування</h1>

    <form action="{{ route('treatments.update', $treatment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="appointment_id" class="form-label">Прийом:</label>
            <select name="appointment_id" id="appointment_id" class="form-select" required>
                <option value="">-- Виберіть прийом --</option>
                @foreach ($appointments as $appointment)
                    <option value="{{ $appointment->id }}" {{ $treatment->appointment_id == $appointment->id ? 'selected' : '' }}>
                        Прийом #{{ $appointment->id }} (Пацієнт: {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}, Лікар: {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Опис:</label>
            <textarea name="description" id="description" class="form-control" required>{{ $treatment->description }}</textarea>
        </div>

        <div class="mb-3">
            <label for="medication" class="form-label">Медикамент:</label>
            <input type="text" name="medication" id="medication" class="form-control" value="{{ $treatment->medication }}">
        </div>

        <div class="mb-3">
            <label for="dosage" class="form-label">Дозування:</label>
            <input type="text" name="dosage" id="dosage" class="form-control" value="{{ $treatment->dosage }}">
        </div>

        <button type="submit" class="btn btn-primary">Зберегти зміни</button>
        <a href="{{ route('treatments.index') }}" class="btn btn-secondary">Назад</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
