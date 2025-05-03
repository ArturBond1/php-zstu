<!DOCTYPE html>
<html>
<head>
    <title>Список діагнозів</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Список діагнозів</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <form action="{{ route('diagnoses.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="name" class="form-label">Назва:</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ request('name') }}">
            </div>
            <div class="col-auto">
                <label for="description" class="form-label">Опис:</label>
                <input type="text" name="description" id="description" class="form-control" value="{{ request('description') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Фільтрувати</button>
                <a href="{{ route('diagnoses.index') }}" class="btn btn-secondary">Скинути</a>
            </div>
        </form>
    </div>

    <a href="{{ route('diagnoses.create') }}" class="btn btn-primary mb-3">Додати новий діагноз</a>

    @if ($diagnoses->isEmpty())
        <p>Немає жодного діагнозу.</p>
    @else
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Назва</th>
                <th>Опис</th>
                <th>Дії</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($diagnoses as $diagnosis)
                <tr>
                    <td>{{ $diagnosis->id }}</td>
                    <td>{{ $diagnosis->name }}</td>
                    <td>{{ $diagnosis->description }}</td>
                    <td>
                        <a href="{{ route('diagnoses.show', $diagnosis->id) }}" class="btn btn-info btn-sm">Переглянути</a>
                        <a href="{{ route('diagnoses.edit', $diagnosis->id) }}" class="btn btn-warning btn-sm">Редагувати</a>
                        <form action="{{ route('diagnoses.destroy', $diagnosis->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Ви впевнені, що хочете видалити цей діагноз?')">Видалити</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $diagnoses->links() }}

        <div class="mt-3">
            <form action="{{ route('diagnoses.index') }}" method="GET" class="d-inline">
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
