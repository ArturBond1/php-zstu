<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PatientController extends Controller
{

    public function index()
    {
        $patients = Patient::all();
        return view('patients.index', compact('patients'));
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
