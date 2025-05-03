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
    protected $perPageOptions = [10, 25, 50, 100];
    protected $defaultPerPage = 10;

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', $this->defaultPerPage);
        $appointments = Appointment::with(['patient', 'doctor', 'diagnosis'])
            ->when($request->filled('appointment_date'), function ($query) use ($request) {
                $query->where('appointment_date', $request->input('appointment_date'));
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->when($request->filled('patient_id'), function ($query) use ($request) {
                $query->where('patient_id', $request->input('patient_id'));
            })
            ->when($request->filled('doctor_id'), function ($query) use ($request) {
                $query->where('doctor_id', $request->input('doctor_id'));
            })
            ->when($request->filled('diagnosis_id'), function ($query) use ($request) {
                $query->where('diagnosis_id', $request->input('diagnosis_id'));
            })
            ->paginate($perPage)
            ->appends($request->query());

        $perPageOptions = $this->perPageOptions;
        $defaultPerPage = $this->defaultPerPage;

        return view('appointments.index', compact('appointments', 'perPageOptions', 'defaultPerPage')); // And add $defaultPerPage here
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
