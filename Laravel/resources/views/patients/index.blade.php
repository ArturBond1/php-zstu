<!DOCTYPE html>
<html>
<head>
    <title>Список пацієнтів</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Список пацієнтів</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <form action="{{ route('patients.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="first_name" class="form-label">Ім'я:</label>
                <input type="text" name="first_name" id="first_name" class="form-control" value="{{ request('first_name') }}">
            </div>
            <div class="col-auto">
                <label for="last_name" class="form-label">Прізвище:</label>
                <input type="text" name="last_name" id="last_name" class="form-control" value="{{ request('last_name') }}">
            </div>
            <div class="col-auto">
                <label for="date_of_birth" class="form-label">Дата народження:</label>
                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ request('date_of_birth') }}">
            </div>
            <div class="col-auto">
                <label for="address" class="form-label">Адреса:</label>
                <input type="text" name="address" id="address" class="form-control" value="{{ request('address') }}">
            </div>
            <div class="col-auto">
                <label for="phone_number" class="form-label">Телефон:</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ request('phone_number') }}">
            </div>
            <div class="col-auto">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ request('email') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Фільтрувати</button>
                <a href="{{ route('patients.index') }}" class="btn btn-secondary">Скинути</a>
            </div>
        </form>
    </div>

    <a href="{{ route('patients.create') }}" class="btn btn-primary mb-3">Додати нового пацієнта</a>

    @if ($patients->isEmpty())
        <p>Немає жодного пацієнта.</p>
    @else
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Ім'я</th>
                <th>Прізвище</th>
                <th>Дата народження</th>
                <th>Адреса</th>
                <th>Телефон</th>
                <th>Email</th>
                <th>Дії</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($patients as $patient)
                <tr>
                    <td>{{ $patient->id }}</td>
                    <td>{{ $patient->first_name }}</td>
                    <td>{{ $patient->last_name }}</td>
                    <td>{{ $patient->date_of_birth }}</td>
                    <td>{{ $patient->address }}</td>
                    <td>{{ $patient->phone_number }}</td>
                    <td>{{ $patient->email }}</td>
                    <td>
                        <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-info btn-sm">Переглянути</a>
                        <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning btn-sm">Редагувати</a>
                        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Ви впевнені, що хочете видалити цього пацієнта?')">Видалити</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $patients->links() }}

        <div class="mt-3">
            <form action="{{ route('patients.index') }}" method="GET" class="d-inline">
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
