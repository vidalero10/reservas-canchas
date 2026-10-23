<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function mostrarLogin(): View
    {
        return view('auth.login');
    }

    public function mostrarRegistro(): View
    {
        return view('auth.register');
    }

    public function login(Request $request): RedirectResponse
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credenciales, $request->boolean('remember'))) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'El correo o la contrasena no coinciden.']);
        }

        $request->session()->regenerate();

        return redirect()->intended($this->rutaInicial($request->user()));
    }

    public function registro(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'ci' => ['required', 'string', 'max:15', 'unique:clientes,ci'],
            'telefono' => ['nullable', 'string', 'regex:/^[67][0-9]{7}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $usuario = DB::transaction(function () use ($datos): User {
            $usuario = User::create([
                'name' => $datos['name'],
                'email' => $datos['email'],
                'password' => $datos['password'],
            ]);
            // El rol se fija en el servidor para que el formulario publico nunca pueda crear administradores.
            $usuario->rol = User::ROL_CLIENTE;
            $usuario->save();

            // Cada cuenta de cliente debe tener una ficha para reservar y consultar sus horarios.
            Cliente::create([
                'user_id' => $usuario->id,
                'ci' => $datos['ci'],
                'nombre' => $datos['name'],
                'telefono' => $datos['telefono'] ?? null,
            ]);

            return $usuario;
        });

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('cliente.reservas.index')
            ->with('success', 'Tu cuenta de cliente ya esta lista para reservar.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('inicio')->with('success', 'Sesion cerrada correctamente.');
    }

    private function rutaInicial(User $usuario): string
    {
        return $usuario->esAdministrador()
            ? route('admin.reservas.del_dia')
            : route('cliente.reservas.index');
    }
}
