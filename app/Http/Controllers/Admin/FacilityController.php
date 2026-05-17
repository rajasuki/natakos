<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FacilityController extends Controller
{
    public function index(): View
    {
        return view('admin.facilities.index', [
            'facilities' => Facility::query()->orderBy('type')->orderBy('name')->get(),
            'typeLabels' => $this->typeLabels(),
        ]);
    }

    public function create(): View
    {
        return view('admin.facilities.create', [
            'typeLabels' => $this->typeLabels(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Facility::create($this->validatedData($request));

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function edit(Facility $facility): View
    {
        return view('admin.facilities.edit', [
            'facility' => $facility,
            'typeLabels' => $this->typeLabels(),
        ]);
    }

    public function update(Request $request, Facility $facility): RedirectResponse
    {
        $facility->update($this->validatedData($request, $facility));

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Facility $facility): RedirectResponse
    {
        try {
            $facility->delete();
        } catch (QueryException) {
            return redirect()
                ->route('admin.facilities.index')
                ->with('error', 'Fasilitas tidak dapat dihapus karena masih memiliki data terkait.');
        }

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Fasilitas berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Facility $facility = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('facilities')
                    ->ignore($facility?->id)
                    ->where(fn ($query) => $query->where('type', $request->string('type')->toString())),
            ],
            'type' => ['required', Rule::in(array_keys($this->typeLabels()))],
            'icon' => ['nullable', 'string', 'max:100'],
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function typeLabels(): array
    {
        return [
            'room' => 'Fasilitas Kamar',
            'public' => 'Fasilitas Umum',
        ];
    }
}
