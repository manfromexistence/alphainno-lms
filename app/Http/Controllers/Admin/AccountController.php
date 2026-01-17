<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Income;
use App\Services\AccountService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AccountController extends Controller
{
    public function __construct(protected AccountService $accountService)
    {}

    public function index()
    {
        $overview = $this->accountService->getOverviewData();
        $chartData = $this->accountService->getChartData();
        
        return view('dashboard.accounts.index', [
            'overview' => $overview,
            'chartData' => $chartData,
        ]);
    }

    public function income(Request $request)
    {
        $incomes = $this->accountService->getIncome($request->all());
        $categories = Income::CATEGORIES;
        
        return view('dashboard.accounts.income', [
            'incomes' => $incomes,
            'categories' => $categories,
            'filters' => $request->all(),
        ]);
    }

    public function storeIncome(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'income_date' => 'required|date',
            'student_id' => 'nullable|exists:students,id',
            'reference' => 'nullable|string',
        ]);

        $this->accountService->recordIncome($validated);

        return redirect()->route('dashboard.accounts.income')
            ->with('success', 'Income recorded successfully.');
    }

    public function destroyIncome(Income $income)
    {
        $income->delete();
        return redirect()->route('dashboard.accounts.income')
            ->with('success', 'Income deleted successfully.');
    }

    public function expenses(Request $request)
    {
        $expenses = $this->accountService->getExpenses($request->all());
        $categories = Expense::CATEGORIES;
        
        return view('dashboard.accounts.expenses', [
            'expenses' => $expenses,
            'categories' => $categories,
            'filters' => $request->all(),
        ]);
    }

    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'receipt_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $this->accountService->createExpense($validated);

        return redirect()->route('dashboard.accounts.expenses')
            ->with('success', 'Expense recorded successfully.');
    }

    public function updateExpense(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'receipt_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $this->accountService->updateExpense($expense, $validated);

        return redirect()->route('dashboard.accounts.expenses')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroyExpense(Expense $expense)
    {
        $this->accountService->deleteExpense($expense);
        return redirect()->route('dashboard.accounts.expenses')
            ->with('success', 'Expense deleted successfully.');
    }

    public function reports(Request $request)
    {
        $startDate = $request->get('start_date') 
            ? Carbon::parse($request->get('start_date')) 
            : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date') 
            ? Carbon::parse($request->get('end_date')) 
            : Carbon::now()->endOfMonth();

        $report = $this->accountService->getFinancialReport($startDate, $endDate);
        $chartData = $this->accountService->getChartData(12);

        return view('dashboard.accounts.reports', [
            'report' => $report,
            'chartData' => $chartData,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function export(Request $request)
    {
        return view('dashboard.accounts.export');
    }

    public function exportPdf(Request $request)
    {
        $startDate = Carbon::parse($request->get('start_date', now()->startOfMonth()));
        $endDate = Carbon::parse($request->get('end_date', now()->endOfMonth()));
        
        $report = $this->accountService->getFinancialReport($startDate, $endDate);

        $pdf = PDF::loadView('exports.pdf.financial-report', [
            'report' => $report,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

        return $pdf->download('financial-report-' . now()->format('Y-m-d') . '.pdf');
    }
}
