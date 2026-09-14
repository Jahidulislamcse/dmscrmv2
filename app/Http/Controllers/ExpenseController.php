<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Expense, ExpenseCategory};
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['category', 'createdBy']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('month')) {
            $query->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$request->month]);
        }

        $expenses = $query->latest('date')->paginate(12);
        $categories = ExpenseCategory::all();
        $totalExpenses = Expense::sum('amount');

        return view('expenses.index', compact('expenses', 'categories', 'totalExpenses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:expense_categories,id',
            'payment_method' => 'required|string|max:100',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        Expense::create(array_merge($validated, ['created_by' => Auth::id()]));

        return redirect()->route('expenses.index')->with('success', "Expense logged successfully!");
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', "Expense deleted successfully!");
    }
}
