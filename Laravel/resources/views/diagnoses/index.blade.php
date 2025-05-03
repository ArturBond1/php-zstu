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
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
