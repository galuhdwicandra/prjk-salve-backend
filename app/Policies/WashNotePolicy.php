<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WashNote;

class WashNotePolicy
{
    public function before(User $user, string $ability): bool|null
    {
        // Superadmin bebas semua
        return $user->hasRole('Superadmin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        // Admin Cabang, Kasir, Petugas Cuci, Kurir minimal bisa lihat daftar (read-only)
        return $user->hasAnyRole(['Admin Cabang', 'Kasir', 'Petugas Cuci', 'Kurir']);
    }

    public function view(User $user, WashNote $note): bool
    {
        if ($user->hasRole('Petugas Cuci')) {
            return (int)$user->id === (int)$note->user_id; // hanya miliknya
        }
        if ($user->hasRole('Admin Cabang') && $user->branch_id) {
            return (string)$user->branch_id === (string)$note->branch_id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Petugas Cuci');
    }

    public function update(User $user, WashNote $note): bool
    {
        if ($user->hasRole('Petugas Cuci')) {
            return (int)$user->id === (int)$note->user_id;
        }
        if ($user->hasRole('Admin Cabang') && $user->branch_id) {
            return (string)$user->branch_id === (string)$note->branch_id;
        }
        return false;
    }

    public function delete(User $user, WashNote $note): bool
    {
        // Sesuai requirement: Petugas Cuci tidak bisa hapus; hanya Superadmin/Admin Cabang.
        if ($user->hasRole('Admin Cabang') && $user->branch_id) {
            return (string)$user->branch_id === (string)$note->branch_id;
        }
        return false;
    }
}
