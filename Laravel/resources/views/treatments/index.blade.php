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
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
