<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::withCount(['schedules', 'grades', 'modules'])->orderBy('name')->get();
        return view('subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:subjects,code',
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'weekly_hours' => 'required|integer|min:1',
            'semester' => 'required|string',
        ]);

        Subject::create($request->all());
        return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil ditambahkan!');
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'code' => 'required|string|unique:subjects,code,' . $subject->id,
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'weekly_hours' => 'required|integer|min:1',
            'semester' => 'required|string',
        ]);

        $subject->update($request->all());
        return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil diperbarui!');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Mata pelajaran berhasil dihapus!');
    }
}
