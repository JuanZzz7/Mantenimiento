<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->get('search')) {
            $query->where(fn($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
            );
        }
        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $data             = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['active']   = $request->boolean('active', true);

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(StoreUserRequest $request, User $user)
    {
        $data           = $request->validated();
        $data['active'] = $request->boolean('active', false);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $openOrders = $user->assignedWorkOrders()
            ->whereIn('status', ['pendiente', 'en_proceso'])->count();

        if ($openOrders > 0) {
            return back()->with('error', "El usuario tiene {$openOrders} orden(es) activas asignadas.");
        }

        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado.');
    }
}
