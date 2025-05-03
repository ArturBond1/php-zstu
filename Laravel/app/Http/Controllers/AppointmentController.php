<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Diagnosis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AppointmentController extends Controller
{

    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor', 'diagnosis'])->get();
        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $diagnoses = Diagnosis::all();
        return view('appointments.create', compact('patients', 'doctors', 'diagnoses'));
    }

    public function store(Request $request)
    {
        Appointment::create($request->all());
        return Redirect::route('appointments.index')->with('success', 'Прийом додано.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'diagnosis', 'treatments']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $diagnoses = Diagnosis::all();
        return view('appointments.edit', compact('appointment', 'patients', 'doctors', 'diagnoses'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $appointment->update($request->all());
        return Redirect::route('appointments.index')->with('success', 'Інформацію про прийом оновлено.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return Redirect::route('appointments.index')->with('success', 'Прийом видалено.');
    }
}
