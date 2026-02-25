<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $userService){}

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereIn('role', $request->input('role'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        $users      = $query->latest()->paginate(5)->withQueryString();
        $totalUsers = User::count();
        $activeNow  = User::where('last_login', '>=', now()->subMinutes(5))->count();
        $totalRoles = User::distinct('role')->count('role');

        return view('users.index', compact('users', 'totalUsers', 'activeNow', 'totalRoles'));
    }

    public function store(UserRequest $request) 
    {
        try {
            $this->userService->createUser($request);
            return redirect()->route('users.index')->with('success', 'Data user berhasil ditambahkan');
        } catch (\Throwable $e) {
            logger()->error('Add user failed',[
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('users.index')->withErrors('Terjadi kesalahan saat add user');
        }     
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $this->userService->updateUser($user, $request);
            return redirect()->route('users.index')->with('success', 'Data berhasil di update');
        } catch (\Throwable $e) {
            logger()->error('User update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('users.index')->withErrors('Terjadi kesalahan saat mengupdate user.');
        }
    }

    public function destroy(User $user) {
        $this->userService->deleteUser($user);
        return redirect()->route('users.index');
    }
}
