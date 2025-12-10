<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuariosController extends Controller
{
    public function index()
    {
        $usuarios = DB::table('users')->get();
        return view('usuarios.index', compact('usuarios'));
    }
    public function create()
    {
        return view('usuarios.registro');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'password' => 'required|string|min:6|confirmed',
            'telefono' => 'nullable|string|max:20',
            'rol' => 'required|in:administrador,usuario'
        ]);

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'telefono' => $request->telefono,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario registrado exitosamente');
    }
    public function loginForm()
    {
        return view('usuarios.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $usuario = DB::table('users')->where('email', $request->email)->first();

        if ($usuario && Hash::check($request->password, $usuario->password)) {
            session(['user_id' => $usuario->id, 'user_name' => $usuario->name, 'user_rol' => $usuario->rol]);
            return redirect()->route('inicio')->with('success', 'Bienvenido ' . $usuario->name);
        }

        return back()->with('error', 'Credenciales incorrectas');
    }
    public function logout()
    {
        session()->flush();
        return redirect()->route('usuarios.login')->with('success', 'Sesión cerrada');
    }
    public function perfil($id)
    {
        $usuario = DB::table('users')->where('id', $id)->first();
        return view('usuarios.perfil', compact('usuario'));
    }
    public function edit($id)
    {
        $usuario = DB::table('users')->where('id', $id)->first();
        return view('usuarios.editar', compact('usuario'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'nullable|string|max:20'
        ]);

        DB::table('users')->where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'updated_at' => now()
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado');
    }
    public function destroy($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado');
    }
    public function recuperarForm()
    {
        return view('usuarios.recuperar');
    }

    public function recuperar(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/']
        ]);

        $usuario = DB::table('users')->where('email', $request->email)->first();

        if ($usuario) {
            return back()->with('success', 'Se ha enviado un email con instrucciones para recuperar tu contraseña');
        }

        return back()->with('error', 'Email no encontrado');
    }
}
