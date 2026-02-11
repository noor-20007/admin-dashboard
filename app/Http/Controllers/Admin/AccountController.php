<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Group;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Account::with('group');

        // Filters
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }

        if ($request->has('group_id') && $request->group_id != '') {
            $query->where('group_id', $request->group_id);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $accounts = $query->latest()->get();
        $groups = Group::all();

        // Statistics
        $totalAmount = Account::sum('amount');
        $totalPaid = Account::where('status', 'Paid')->sum('amount');
        $totalPending = Account::where('status', 'Pending')->sum('amount');
        $totalCanceled = Account::where('status', 'Canceled')->sum('amount');
        
        $stats = [
            'total_amount' => $totalAmount,
            'total_paid' => $totalPaid,
            'total_pending' => $totalPending,
            'total_canceled' => $totalCanceled,
            'count' => Account::count(),
        ];

        $clients = \App\Models\Client::all();
        
        // Calculate pending balance for each client name
        $clientBalances = Account::where('status', 'Pending')
            ->selectRaw('name, SUM(amount) as balance')
            ->groupBy('name')
            ->pluck('balance', 'name');

        return view('admin.accounts.index', compact('accounts', 'groups', 'stats', 'clients', 'clientBalances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = Group::all();
        $clients = \App\Models\Client::all();
        
        // Calculate pending balance for each client name
        // We use the client name as the key because the accounts table currently stores 'name' string
        $clientBalances = Account::where('status', 'Pending')
            ->selectRaw('name, SUM(amount) as balance')
            ->groupBy('name')
            ->pluck('balance', 'name');

        return view('admin.accounts.create', compact('groups', 'clients', 'clientBalances'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'group_id' => 'nullable|exists:groups,id',
            'date' => 'nullable|date',
            'amount' => 'nullable|numeric',
            'reference_number' => 'required|string|unique:accounts,reference_number',
            'status' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Account::create($request->all());

        return redirect()->route('admin.accounts.index')->with('success', 'تم إضافة الحساب بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        $groups = Group::all();
        return view('admin.accounts.edit', compact('account', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'group_id' => 'nullable|exists:groups,id',
            'date' => 'nullable|date',
            'amount' => 'nullable|numeric',
            'reference_number' => 'required|string|unique:accounts,reference_number,' . $account->id,
            'status' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $account->update($request->all());

        return redirect()->route('admin.accounts.index')->with('success', 'تم تحديث الحساب بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->route('admin.accounts.index')->with('success', 'تم حذف الحساب بنجاح');
    }
}
