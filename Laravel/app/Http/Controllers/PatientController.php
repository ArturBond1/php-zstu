<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PatientController extends Controller
{
    protected $perPageOptions = [10, 25, 50, 100];
    protected $defaultPerPage = 10;

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', $this->defaultPerPage);
        $patients = Patient::query()
            ->when($request->filled('first_name'), function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->input('first_name') . '%');
            })
            ->when($request->filled('last_name'), function ($query) use ($request) {
                $query->where('last_name', 'like', '%' . $request->input('last_name') . '%');
            })
            ->when($request->filled('date_of_birth'), function ($query) use ($request) {
                $query->where('date_of_birth', $request->input('date_of_birth'));
            })
            ->when($request->filled('address'), function ($query) use ($request) {
                $query->where('address', 'like', '%' . $request->input('address') . '%');
            })
            ->when($request->filled('phone_number'), function ($query) use ($request) {
                $query->where('phone_number', 'like', '%' . $request->input('phone_number') . '%');
            })
            ->when($request->filled('email'), function ($query) use ($request) {
                $query->where('email', 'like', '%' . $request->input('email') . '%');
            })
            ->paginate($perPage)
            ->appends($request->query());

        $perPageOptions = $this->perPageOptions;
        $defaultPerPage = $this->defaultPerPage;

        return view('patients.index', compact('patients', 'perPageOptions', 'defaultPerPage')); // Додайте 'defaultPerPage' до compact
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        Patient::create($request->all());
        return Redirect::route('patients.index')->with('success', 'Пацієнта додано.');
    }

    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $patient->update($request->all());
        return Redirect::route('patients.index')->with('success', 'Інформацію про пацієнта оновлено.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return Redirect::route('patients.index')->with('success', 'Пацієнта видалено.');
    }
}
