<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Group::with('supervisor')->latest()->get();
        return view('admin.groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('admin.groups.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'supervisor_id' => 'nullable|exists:users,id',
        ]);

        Group::create($request->all());

        return redirect()->route('admin.groups.index')->with('success', 'تم إنشاء المجموعة بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        $users = User::all();
        return view('admin.groups.edit', compact('group', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'supervisor_id' => 'nullable|exists:users,id',
        ]);

        $group->update($request->all());

        return redirect()->route('admin.groups.index')->with('success', 'تم تحديث المجموعة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('admin.groups.index')->with('success', 'تم حذف المجموعة بنجاح');
    }
}
