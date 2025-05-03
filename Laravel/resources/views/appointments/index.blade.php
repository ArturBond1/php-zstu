<!DOCTYPE html>
<html>
<head>
    <title>Список прийомів</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Список прийомів</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('appointments.create') }}" class="btn btn-primary mb-3">Додати новий прийом</a>

    @if ($appointments->isEmpty())
        <p>Немає жодного прийому.</p>
    @else
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Пацієнт</th>
                <th>Лікар</th>
                <th>Діагноз</th>
                <th>Час прийому</th>
                <th>Примітки</th>
                <th>Дії</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->id }}</td>
                    <td><a href="{{ route('patients.show', $appointment->patient->id) }}">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</a></td>
                    <td><a href="{{ route('doctors.show', $appointment->doctor->id) }}">{{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</a></td>
                    <td>{{ $appointment->diagnosis ? $appointment->diagnosis->name : 'Немає' }}</td>
                    <td>{{ $appointment->appointment_time }}</td>
                    <td>{{ $appointment->notes }}</td>
                    <td>
                        <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-info btn-sm">Переглянути</a>
                        <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning btn-sm">Редагувати</a>
                        <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Ви впевнені, що хочете видалити цей прийом?')">Видалити</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
