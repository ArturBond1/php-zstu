<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TreatmentController extends Controller
{
    protected $perPageOptions = [10, 25, 50, 100];
    protected $defaultPerPage = 10;

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', $this->defaultPerPage);
        $treatments = Treatment::with('appointment')
            ->when($request->filled('appointment_id'), function ($query) use ($request) {
                $query->where('appointment_id', $request->input('appointment_id'));
            })
            ->when($request->filled('description'), function ($query) use ($request) {
                $query->where('description', 'like', '%' . $request->input('description') . '%');
            })
            ->when($request->filled('medication'), function ($query) use ($request) {
                $query->where('medication', 'like', '%' . $request->input('medication') . '%');
            })
            ->when($request->filled('dosage'), function ($query) use ($request) {
                $query->where('dosage', 'like', '%' . $request->input('dosage') . '%');
            })
            ->paginate($perPage)
            ->appends($request->query());

        $perPageOptions = $this->perPageOptions;
        $defaultPerPage = $this->defaultPerPage;
        $appointments = Appointment::all();

        return view('treatments.index', compact('treatments', 'perPageOptions', 'defaultPerPage', 'appointments'));
    }

    public function create()
    {
        $appointments = Appointment::all();
        return view('treatments.create', compact('appointments'));
    }

    public function store(Request $request)
    {
        Treatment::create($request->all());
        return Redirect::route('treatments.index')->with('success', 'Лікування додано.');
    }

    public function show(Treatment $treatment)
    {
        $treatment->load('appointment');
        return view('treatments.show', compact('treatment'));
    }

    public function edit(Treatment $treatment)
    {
        $appointments = Appointment::all();
        return view('treatments.edit', compact('treatment', 'appointments'));
    }

    public function update(Request $request, Treatment $treatment)
    {
        $treatment->update($request->all());
        return Redirect::route('treatments.index')->with('success', 'Інформацію про лікування оновлено.');
    }

    public function destroy(Treatment $treatment)
    {
        $treatment->delete();
        return Redirect::route('treatments.index')->with('success', 'Лікування видалено.');
    }
}
