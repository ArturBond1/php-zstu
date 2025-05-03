<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TreatmentController extends Controller
{
    public function index()
    {
        $treatments = Treatment::with('appointment')->get();
        return view('treatments.index', compact('treatments'));
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
