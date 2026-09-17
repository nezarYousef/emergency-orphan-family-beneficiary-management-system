<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()->when($request->filled('search'), fn ($query) => $query->whereLike('name', '%'.$request->search.'%', caseSensitive: false)->orWhereLike('email', '%'.$request->search.'%', caseSensitive: false))->latest()->paginate(15)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.form', ['user' => new User(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        User::create($data);

        return redirect()->route('users.index')->with('status', __('messages.user.created'));
    }

    public function edit(User $user)
    {
        return view('users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $this->validated($request, $user);
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }
        $user->update($data);

        return redirect()->route('users.index')->with('status', __('messages.user.updated'));
    }

    public function destroy(User $user)
    {
        abort_if($user->is(auth()->user()), 422, __('messages.user.cannot_deactivate_self'));
        $user->update(['is_active' => false]);

        return redirect()->route('users.index')->with('status', __('messages.user.deactivated'));
    }

    private function validated(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['admin', 'data_entry', 'viewer'])],
            'is_active' => ['required', 'boolean'],
        ]);
    }
}
