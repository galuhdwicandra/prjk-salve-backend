<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    /**
     * Superadmin bypass semua cek policy.
     */
    public function before(User $actor, string $ability): bool|null
    {
        return $actor->hasRole('Superadmin') ? true : null;
    }

    public function viewAny(User $actor): bool
    {
        // Kasir boleh lihat daftar (read-only); Admin Cabang jelas boleh.
        return $actor->hasAnyRole(['Admin Cabang', 'Kasir']);
    }

    public function view(User $actor, User $target): bool
    {
        // Boleh lihat diri sendiri
        if ($actor->id === $target->id) {
            return true;
        }

        // Admin Cabang: hanya user di cabangnya
        if ($actor->hasRole('Admin Cabang')) {
            return (string) $actor->branch_id === (string) $target->branch_id;
        }

        // Kasir: hanya lihat user di cabangnya (tanpa aksi lain)
        if ($actor->hasRole('Kasir')) {
            return (string) $actor->branch_id === (string) $target->branch_id;
        }

        return false;
    }

    public function create(User $actor): bool
    {
        // Admin Cabang boleh buat user, tapi cabang akan divalidasi di FormRequest
        return $actor->hasRole('Admin Cabang');
    }

    public function update(User $actor, User $target): bool
    {
        Log::info('UserPolicy@update', [
            'actor_id' => $actor->id,
            'actor_roles' => $actor->getRoleNames(),
            'actor_branch' => $actor->branch_id,
            'target_id' => $target->id,
            'target_roles' => $target->getRoleNames(),
            'target_branch' => $target->branch_id,
        ]);
        // Boleh update profil sendiri (mis. name/email — password via endpoint khusus)
        if ($actor->id === $target->id) {
            return true;
        }

        // Larangan: Admin Cabang tidak boleh utak-atik Superadmin
        if ($target->hasRole('Superadmin')) {
            return false;
        }

        // Admin Cabang: hanya user di cabangnya
        if ($actor->hasRole('Admin Cabang')) {
            return (string) $actor->branch_id === (string) $target->branch_id;
        }

        return false;
    }

    public function delete(User $actor, User $target): bool
    {
        // Tidak boleh hapus diri sendiri
        if ($actor->id === $target->id) {
            return false;
        }

        // Larangan: Admin Cabang tidak boleh hapus Superadmin
        if ($target->hasRole('Superadmin')) {
            return false;
        }

        if ($actor->hasRole('Admin Cabang')) {
            return (string) $actor->branch_id === (string) $target->branch_id;
        }

        return false;
    }

    /**
     * Aksi khusus yang sering dipakai
     */
    public function resetPassword(User $actor, User $target): bool
    {
        // Ikuti aturan update
        return $this->update($actor, $target);
    }

    public function setActive(User $actor, User $target): bool
    {
        return $this->update($actor, $target);
    }

    public function setRoles(User $actor, User $target): bool
    {
        // Tidak boleh sentuh Superadmin
        if ($target->hasRole('Superadmin')) {
            return false;
        }

        if ($actor->hasRole('Admin Cabang')) {
            // Hanya user di cabang yang sama
            return (string) $actor->branch_id === (string) $target->branch_id;
        }

        return false;
    }
}
