<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = \App\Models\Currency::latest()->get();
        return view('admin.currencies.index', compact('currencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'code' => 'nullable|string|max:10',
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->is_default) {
            \App\Models\Currency::where('is_default', true)->update(['is_default' => false]);
        }

        \App\Models\Currency::create([
            'name' => $request->name,
            'symbol' => $request->symbol,
            'code' => $request->code,
            'is_default' => $request->is_default ?? false,
        ]);

        return redirect()->route('admin.currencies.index')->with('success', 'تم إضافة العملة بنجاح');
    }

    public function update(Request $request, \App\Models\Currency $currency)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'code' => 'nullable|string|max:10',
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->is_default) {
            \App\Models\Currency::where('id', '!=', $currency->id)->update(['is_default' => false]);
        }

        $currency->update([
            'name' => $request->name,
            'symbol' => $request->symbol,
            'code' => $request->code,
            'is_default' => $request->is_default ?? 0,
        ]);

        return redirect()->route('admin.currencies.index')->with('success', 'تم تحديث العملة بنجاح');
    }

    public function destroy(\App\Models\Currency $currency)
    {
        if ($currency->is_default) {
             return redirect()->route('admin.currencies.index')->with('error', 'لا يمكن حذف العملة الافتراضية');
        }
        $currency->delete();
        return redirect()->route('admin.currencies.index')->with('success', 'تم حذف العملة بنجاح');
    }
}
