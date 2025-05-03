<!DOCTYPE html>
<html>
<head>
    <title>Перегляд прийому</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Інформація про прийом</h1>

    <div class="mb-3">
        <strong>ID:</strong> {{ $appointment->id }}
    </div>
    <div class="mb-3">
        <strong>Пацієнт:</strong> <a href="{{ route('patients.show', $appointment->patient->id) }}">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</a>
    </div>
    <div class="mb-3">
        <strong>Лікар:</strong> <a href="{{ route('doctors.show', $appointment->doctor->id) }}">{{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</a>
    </div>
    <div class="mb-3">
        <strong>Діагноз:</strong> {{ $appointment->diagnosis ? $appointment->diagnosis->name : 'Немає' }}
    </div>
    <div class="mb-3">
        <strong>Час прийому:</strong> {{ $appointment->appointment_time }}
    </div>
    <div class="mb-3">
        <strong>Примітки:</strong> {{ $appointment->notes }}
    </div>

    @if ($appointment->treatments->isNotEmpty())
        <h2 class="mt-4">Лікування:</h2>
        <ul class="list-group">
            @foreach ($appointment->treatments as $treatment)
                <li class="list-group-item">
                    {{ $treatment->description }}
                    @if ($treatment->medication) - Медикамент: {{ $treatment->medication }} @endif
                    @if ($treatment->dosage) - Дозування: {{ $treatment->dosage }} @endif
                    <a href="{{ route('treatments.show', $treatment->id) }}" class="btn btn-info btn-sm ms-2">Деталі лікування</a>
                </li>
            @endforeach
        </ul>
    @else
        <p class="mt-3">Для цього прийому ще не призначено лікування.</p>
    @endif

    <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning">Редагувати</a>
    <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Назад</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
