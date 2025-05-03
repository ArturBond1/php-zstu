<!DOCTYPE html>
<html>
<head>
    <title>Список лікувань</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Список лікувань</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <form action="{{ route('treatments.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="appointment_id" class="form-label">Прийом:</label>
                <select name="appointment_id" id="appointment_id" class="form-select">
                    <option value="">Всі прийоми</option>
                    @foreach ($appointments as $appointment)
                        <option value="{{ $appointment->id }}" {{ request('appointment_id') == $appointment->id ? 'selected' : '' }}>
                            Прийом #{{ $appointment->id }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label for="description" class="form-label">Опис:</label>
                <input type="text" name="description" id="description" class="form-control" value="{{ request('description') }}">
            </div>
            <div class="col-auto">
                <label for="medication" class="form-label">Медикамент:</label>
                <input type="text" name="medication" id="medication" class="form-control" value="{{ request('medication') }}">
            </div>
            <div class="col-auto">
                <label for="dosage" class="form-label">Дозування:</label>
                <input type="text" name="dosage" id="dosage" class="form-control" value="{{ request('dosage') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Фільтрувати</button>
                <a href="{{ route('treatments.index') }}" class="btn btn-secondary">Скинути</a>
            </div>
        </form>
    </div>

    <a href="{{ route('treatments.create') }}" class="btn btn-primary mb-3">Додати нове лікування</a>

    @if ($treatments->isEmpty())
        <p>Немає жодного лікування.</p>
    @else
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Прийом</th>
                <th>Опис</th>
                <th>Медикамент</th>
                <th>Дозування</th>
                <th>Дії</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($treatments as $treatment)
                <tr>
                    <td>{{ $treatment->id }}</td>
                    <td><a href="{{ route('appointments.show', $treatment->appointment->id) }}">Прийом #{{ $treatment->appointment->id }}</a></td>
                    <td>{{ $treatment->description }}</td>
                    <td>{{ $treatment->medication }}</td>
                    <td>{{ $treatment->dosage }}</td>
                    <td>
                        <a href="{{ route('treatments.show', $treatment->id) }}" class="btn btn-info btn-sm">Переглянути</a>
                        <a href="{{ route('treatments.edit', $treatment->id) }}" class="btn btn-warning btn-sm">Редагувати</a>
                        <form action="{{ route('treatments.destroy', $treatment->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Ви впевнені, що хочете видалити це лікування?')">Видалити</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $treatments->links() }}

        <div class="mt-3">
            <form action="{{ route('treatments.index') }}" method="GET" class="d-inline">
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
