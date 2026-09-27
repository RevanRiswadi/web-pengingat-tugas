<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_tugas'     => 'required|string|max:255',
            'mata_pelajaran'  => 'required|string|max:255',
            'tenggat_waktu'   => 'required|date|after_or_equal:today',
            'status'          => 'nullable|in:belum,selesai',
        ]);

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'judul_tugas'     => 'required|string|max:255',
            'mata_pelajaran'  => 'required|string|max:255',
            'tenggat_waktu'   => 'required|date',
            'status'          => 'nullable|in:belum,selesai',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil diupdate.');
    }

    public function destroy(Task $task)
{
    $task->delete();
    return redirect()->route('tasks.index')->with('success', 'Tugas berhasil dihapus.');
}

}