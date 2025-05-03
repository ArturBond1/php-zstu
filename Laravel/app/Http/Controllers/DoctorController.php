<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DoctorController extends Controller
{
    protected $perPageOptions = [10, 25, 50, 100];
    protected $defaultPerPage = 10;

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', $this->defaultPerPage);
        $doctors = Doctor::query()
            ->when($request->filled('first_name'), function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->input('first_name') . '%');
            })
            ->when($request->filled('last_name'), function ($query) use ($request) {
                $query->where('last_name', 'like', '%' . $request->input('last_name') . '%');
            })
            ->when($request->filled('specialization'), function ($query) use ($request) {
                $query->where('specialization', 'like', '%' . $request->input('specialization') . '%');
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

        return view('doctors.index', compact('doctors', 'perPageOptions', 'defaultPerPage')); // Додайте $defaultPerPage в compact()
    }

    public function create()
    {
        return view('doctors.create');
    }

    public function store(Request $request)
    {
        Doctor::create($request->all());
        return Redirect::route('doctors.index')->with('success', 'Лікаря додано.');
    }

    public function show(Doctor $doctor)
    {
        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        return view('doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $doctor->update($request->all());
        return Redirect::route('doctors.index')->with('success', 'Інформацію про лікаря оновлено.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return Redirect::route('doctors.index')->with('success', 'Лікаря видалено.');
    }
}
