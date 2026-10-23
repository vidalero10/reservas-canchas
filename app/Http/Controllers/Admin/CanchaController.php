<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cancha;
use App\Models\Deporte;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CanchaController extends Controller
{
    public function index(): View
    {
        $canchas = Cancha::query()->with('deporte')->orderBy('nombre')->get();
        return view('admin.canchas.index', compact('canchas'));
    }

    public function create(): View
    {
        $cancha = new Cancha(['activa' => true]);
        $deportes = Deporte::query()->orderBy('nombre')->get();
        return view('admin.canchas.form', compact('cancha', 'deportes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validarCancha($request);
        $datos['activa'] = $request->boolean('activa');
        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('canchas', 'public');
        }
        Cancha::create($datos);
        return redirect()->route('admin.canchas.index')->with('success', 'Cancha creada correctamente.');
    }

    public function edit(Cancha $cancha): View
    {
        $deportes = Deporte::query()->orderBy('nombre')->get();
        return view('admin.canchas.form', compact('cancha', 'deportes'));
    }

    public function update(Request $request, Cancha $cancha): RedirectResponse
    {
        $datos = $this->validarCancha($request, $cancha);
        $datos['activa'] = $request->boolean('activa');
        $imagenAnterior = $cancha->imagen;
        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('canchas', 'public');
        } else {
            unset($datos['imagen']);
        }
        $cancha->update($datos);
        if ($imagenAnterior && $request->hasFile('imagen')) {
            Storage::disk('public')->delete($imagenAnterior);
        }
        return redirect()->route('admin.canchas.index')->with('success', 'Cancha actualizada correctamente.');
    }

    public function cambiarEstado(Cancha $cancha): RedirectResponse
    {
        $cancha->activa = !$cancha->activa;
        $cancha->save();
        return back()->with('success', $cancha->activa ? 'Cancha activada.' : 'Cancha desactivada.');
    }

    private function validarCancha(Request $request, ?Cancha $cancha = null): array
    {
        return $request->validate([
            'deporte_id' => ['required', 'integer', 'exists:deportes,id'],
            'nombre' => ['required', 'string', 'max:255', Rule::unique('canchas', 'nombre')->ignore($cancha?->id)],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'tarifa_diurna' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:999999.99'],
            'tarifa_nocturna' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:999999.99'],
        ]);
    }
}