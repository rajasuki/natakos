<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OperationalExpense;
use App\Support\ActivityLogger;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OperationalExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $query = OperationalExpense::query()->orderByDesc('date')->orderByDesc('id');

        $filters = $this->filters($request);

        if ($filters['category'] !== null) {
            $query->where('category', $filters['category']);
        }

        if ($filters['q'] !== null) {
            $term = '%'.$filters['q'].'%';
            $query->where(function ($q) use ($term) {
                $q->where('description', 'like', $term)
                    ->orWhere('notes', 'like', $term);
            });
        }

        return view('admin.operational-expenses.index', [
            'expenses' => $query->paginate(10)->withQueryString(),
            'filters' => $filters,
            'hasActiveFilters' => $filters['q'] !== null || $filters['category'] !== null,
            'categoryLabels' => $this->categoryLabels(),
            'counts' => $this->counts(),
        ]);
    }

    public function create(): View
    {
        return view('admin.operational-expenses.create', [
            'categoryLabels' => $this->categoryLabels(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'description' => ['required', 'string', 'max:500'],
            'amount' => ['required', 'integer', 'min:0'],
            'category' => ['required', Rule::in(array_keys($this->categoryLabels()))],
            'date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated): void {
            $expense = OperationalExpense::create($validated);
            ActivityLogger::created('biaya_operasional', $expense->id, $expense->description);
        });

        return redirect()
            ->route('admin.operational-expenses.index')
            ->with('success', 'Biaya operasional berhasil dicatat.');
    }

    public function edit(OperationalExpense $operationalExpense): View
    {
        return view('admin.operational-expenses.edit', [
            'expense' => $operationalExpense,
            'categoryLabels' => $this->categoryLabels(),
        ]);
    }

    public function update(Request $request, OperationalExpense $operationalExpense): RedirectResponse
    {
        $validated = $request->validate([
            'description' => ['required', 'string', 'max:500'],
            'amount' => ['required', 'integer', 'min:0'],
            'category' => ['required', Rule::in(array_keys($this->categoryLabels()))],
            'date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($operationalExpense, $validated): void {
            $operationalExpense->update($validated);
            ActivityLogger::updated('biaya_operasional', $operationalExpense->id, $operationalExpense->description);
        });

        return redirect()
            ->route('admin.operational-expenses.index')
            ->with('success', 'Biaya operasional berhasil diperbarui.');
    }

    public function destroy(OperationalExpense $operationalExpense): RedirectResponse
    {
        $label = $operationalExpense->description;
        $operationalExpense->delete();

        ActivityLogger::deleted('biaya_operasional', $operationalExpense->id, $label);

        return redirect()
            ->route('admin.operational-expenses.index')
            ->with('success', 'Biaya operasional berhasil dihapus.');
    }

    public function export(Request $request): Response
    {
        $filters = $this->filters($request);
        $expenses = $this->exportQuery($filters)->get();
        $categoryLabels = $this->categoryLabels();

        $pdf = Pdf::loadView('admin.exports.operational-expenses-pdf', compact('expenses', 'categoryLabels'));

        return $pdf->download('operational-expenses-export.pdf');
    }

    public function exportCsv(Request $request): Response
    {
        $filters = $this->filters($request);
        $expenses = $this->exportQuery($filters)->get();
        $categoryLabels = $this->categoryLabels();

        $headers = ['Deskripsi', 'Kategori', 'Jumlah', 'Tanggal', 'Catatan'];
        $rows = $expenses->map(fn ($e) => [
            $e->description,
            $categoryLabels[$e->category] ?? $e->category,
            $e->amount,
            $e->date?->format('Y-m-d') ?? '',
            $e->notes ?? '',
        ]);

        $csv = $this->buildCsv($headers, $rows->all());

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="operational-expenses-export.csv"',
        ]);
    }

    private function exportQuery(array $filters)
    {
        $query = OperationalExpense::query();

        if ($filters['category'] !== null) {
            $query->where('category', $filters['category']);
        }

        if ($filters['q'] !== null) {
            $term = '%'.$filters['q'].'%';
            $query->where(function ($q) use ($term) {
                $q->where('description', 'like', $term)
                    ->orWhere('notes', 'like', $term);
            });
        }

        return $query->orderByDesc('date')->orderByDesc('id');
    }

    /**
     * @param  array<int, string>  $headers
     * @param  array<int, array<int, string>>  $rows
     */
    private function buildCsv(array $headers, array $rows): string
    {
        $output = fopen('php://temp', 'r+');

        fputcsv($output, $headers, ',', '"', '\\');

        foreach ($rows as $row) {
            fputcsv($output, $row, ',', '"', '\\');
        }

        rewind($output);

        return stream_get_contents($output);
    }

    private function categoryLabels(): array
    {
        return [
            'cleaning' => 'Kebersihan',
            'security' => 'Keamanan',
            'repair' => 'Perbaikan',
            'utility' => 'Listrik/Air Umum',
            'salary' => 'Gaji',
            'other' => 'Lainnya',
        ];
    }

    private function filters(Request $request): array
    {
        $q = trim((string) $request->query('q', ''));
        $category = (string) $request->query('category', '');

        return [
            'q' => $q !== '' ? $q : null,
            'category' => array_key_exists($category, $this->categoryLabels()) ? $category : null,
        ];
    }

    private function counts(): array
    {
        return [
            'total' => OperationalExpense::query()->count(),
            'thisMonth' => OperationalExpense::query()
                ->whereYear('date', now()->year)
                ->whereMonth('date', now()->month)
                ->sum('amount'),
            'allTime' => OperationalExpense::query()->sum('amount'),
        ];
    }
}
