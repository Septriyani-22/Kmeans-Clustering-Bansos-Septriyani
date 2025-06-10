<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('entries', 10);
        $search = $request->input('search');
    
        $users = User::query()
            ->when($search, fn($query) => $query->where('username', 'like', "%{$search}%"))
            ->paginate($perPage)
            ->appends(['search' => $search, 'entries' => $perPage]);
    
        return view('admin.users.index', compact('users'));
    }
    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required|min:8', 
            'role' => 'required|in:admin,kepala_desa',
        ]);
    
        $validated['password'] = bcrypt($validated['password']);
    
        Log::info('Data to create: ' . json_encode($validated));
        $user = User::create($validated);
        Log::info('Created user ID: ' . $user->id);
    
        return redirect()->route('users.index')->with('success', 'User added successfully.');
    }    
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|unique:users,username,' . $user->id,
            'password' => 'nullable|min:8', // Password opsional
            'role' => 'required|in:admin,KEPALA DESA',
        ]);
    
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']); 
    
        Log::info('Data to update: ' . json_encode($validated));
        $user->update($validated);
        Log::info('Updated user ID: ' . $user->id);
    
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
     }
    }
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}