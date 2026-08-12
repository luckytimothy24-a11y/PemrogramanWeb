<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;
    protected $activityLogService;

    public function __construct(UserRepositoryInterface $userRepository, ActivityLogService $activityLogService)
    {
        $this->userRepository = $userRepository;
        $this->activityLogService = $activityLogService;
    }

    public function getAllUsers()
    {
        return $this->userRepository->getAll();
    }

    public function getUser($id)
    {
        return $this->userRepository->findById($id);
    }

    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password'] ?? 'password');
        $data['role'] = $data['role'] ?? User::ROLE_STAFF;

        $user = $this->userRepository->create($data);

        $this->activityLogService->log('Tambah Pengguna', "Pengguna \"{$user->name}\" ({$user->role_label}) ditambahkan.");

        return $user;
    }

    public function updateUser($id, array $data)
    {
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user = $this->userRepository->update($id, $data);

        $this->activityLogService->log('Ubah Pengguna', "Data pengguna \"{$user->name}\" diperbarui.");

        return $user;
    }

    public function deleteUser($id)
    {
        $user = $this->userRepository->findById($id);

        if ($user->id === auth()->id()) {
            abort(422, 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        $result = $this->userRepository->delete($id);

        if ($result) {
            $this->activityLogService->log('Hapus Pengguna', "Pengguna \"{$user->name}\" dihapus.");
        }

        return $result;
    }
}
