<!DOCTYPE html>
<html>
<head>
    <title>Редагувати прийом</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Редагувати прийом</h1>

    <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="patient_id" class="form-label">Пацієнт:</label>
            <select name="patient_id" id="patient_id" class="form-select" required>
                <option value="">-- Виберіть пацієнта --</option>
                @foreach ($patients as $patient)
                    <option value="{{ $patient->id }}" {{ $appointment->patient_id == $patient->id ? 'selected' : '' }}>
                        {{ $patient->first_name }} {{ $patient->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="doctor_id" class="form-label">Лікар:</label>
            <select name="doctor_id" id="doctor_id" class="form-select" required>
                <option value="">-- Виберіть лікаря --</option>
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>
                        {{ $doctor->first_name }} {{ $doctor->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="diagnosis_id" class="form-label">Діагноз:</label>
            <select name="diagnosis_id" id="diagnosis_id" class="form-select">
                <option value="">-- Виберіть діагноз (необов'язково) --</option>
                @foreach ($diagnoses as $diagnosis)
                    <option value="{{ $diagnosis->id }}" {{ $appointment->diagnosis_id == $diagnosis->id ? 'selected' : '' }}>
                        {{ $diagnosis->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="appointment_time" class="form-label">Час прийому:</label>
            <input type="datetime-local" name="appointment_time" id="appointment_time" class="form-control" value="{{ $appointment->appointment_time }}" required>
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Примітки:</label>
            <textarea name="notes" id="notes" class="form-control">{{ $appointment->notes }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Зберегти зміни</button>
        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Назад</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
