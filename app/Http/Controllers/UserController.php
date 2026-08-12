<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'password' => ['nullable', Password::min(6)->letters()->numbers()],
        ]);

        $this->userService->createUser($request->all());

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = $this->userService->getUser($id);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'password' => ['nullable', Password::min(6)->letters()->numbers()],
        ]);

        $this->userService->updateUser($id, $request->all());

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            $this->userService->deleteUser($id);
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}
