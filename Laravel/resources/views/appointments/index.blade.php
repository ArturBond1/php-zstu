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

    <div class="mb-3">
        <form action="{{ route('appointments.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="appointment_date" class="form-label">Дата прийому:</label>
                <input type="date" name="appointment_date" id="appointment_date" class="form-control" value="{{ request('appointment_date') }}">
            </div>
            <div class="col-auto">
                <label for="status" class="form-label">Статус:</label>
                <input type="text" name="status" id="status" class="form-control" value="{{ request('status') }}">
            </div>
            <div class="col-auto">
                <label for="patient_id" class="form-label">ID пацієнта:</label>
                <input type="number" name="patient_id" id="patient_id" class="form-control" value="{{ request('patient_id') }}">
            </div>
            <div class="col-auto">
                <label for="doctor_id" class="form-label">ID лікаря:</label>
                <input type="number" name="doctor_id" id="doctor_id" class="form-control" value="{{ request('doctor_id') }}">
            </div>
            <div class="col-auto">
                <label for="diagnosis_id" class="form-label">ID діагнозу:</label>
                <input type="number" name="diagnosis_id" id="diagnosis_id" class="form-control" value="{{ request('diagnosis_id') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Фільтрувати</button>
                <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Скинути</a>
            </div>
        </form>
    </div>

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

        {{ $appointments->links() }}

        <div class="mt-3">
            <form action="{{ route('appointments.index') }}" method="GET" class="d-inline">
                <label for="perPage">Показати на сторінці:</label>
                <select name="perPage" id="perPage" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                    @foreach ($perPageOptions as $option)
                        <option value="{{ $option }}" {{ request('perPage', $defaultPerPage) == $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
