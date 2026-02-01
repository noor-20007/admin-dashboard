<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Client::with('group');

        // Filters
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('identity_num', 'like', "%{$search}%");
            });
        }

        if ($request->has('group_id') && $request->group_id != '') {
            $query->where('group_id', $request->group_id);
        }

        if ($request->has('gender') && $request->gender != '') {
            $query->where('gender', $request->gender);
        }

        $clients = $query->latest()->get();
        $groups = Group::all();
        
        // Statistics (Based on total data or filtered data? Usually total is more useful for dashboard stats, but filtered for table stats. Let's do Total Context Stats)
        $totalClients = Client::count();
        $totalMales = Client::where('gender', 'Male')->count();
        $totalFemales = Client::where('gender', 'Female')->count();
        $newThisMonth = Client::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();

        $stats = [
            'total' => $totalClients,
            'males' => $totalMales,
            'females' => $totalFemales,
            'new_month' => $newThisMonth,
        ];

        return view('admin.clients.index', compact('clients', 'groups', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = Group::all();
        return view('admin.clients.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'gender' => 'nullable|string',
            'age' => 'nullable|integer',
            'identity_num' => 'nullable|string',
            'phone' => 'nullable|string',
            'region' => 'nullable|string',
            'type' => 'nullable|string',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $imageName = 'client_' . time() . '.' . $request->image->extension();
            $request->image->move(base_path('../public_html/images/clients'), $imageName);
            $data['image'] = 'images/clients/' . $imageName;
        }

        Client::create($data);

        return redirect()->route('admin.clients.index')->with('success', 'تم إضافة العميل بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        $groups = Group::all();
        return view('admin.clients.edit', compact('client', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'gender' => 'nullable|string',
            'age' => 'nullable|integer',
            'identity_num' => 'nullable|string',
            'phone' => 'nullable|string',
            'region' => 'nullable|string',
            'type' => 'nullable|string',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($client->image && file_exists(base_path('../public_html/' . $client->image))) {
                unlink(base_path('../public_html/' . $client->image));
            }

            $imageName = 'client_' . time() . '.' . $request->image->extension();
            // Create directory if not exists
            if (!file_exists(base_path('../public_html/images/clients'))) {
                mkdir(base_path('../public_html/images/clients'), 0777, true);
            }
            
            $request->image->move(base_path('../public_html/images/clients'), $imageName);
            $data['image'] = 'images/clients/' . $imageName;
        }

        $client->update($data);

        return redirect()->route('admin.clients.index')->with('success', 'تم تحديث بيانات العميل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        if ($client->image && file_exists(base_path('../public_html/' . $client->image))) {
            unlink(base_path('../public_html/' . $client->image));
        }
        
        $client->delete();

        return redirect()->route('admin.clients.index')->with('success', 'تم حذف العميل بنجاح');
    }
}
