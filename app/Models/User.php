<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens, HasRoles;

    public const MODULES = [
        'dashboard',
        'kasir-pos', 'kasir-receipt', 'kasir-customer', 'kasir-promo',
        'ops-sorting', 'ops-proses', 'ops-kirim', 'ops-tracker',
        'fin-kas', 'fin-transaksi', 'fin-kontak',
        'laporan',
        'set-user', 'set-master', 'set-outlet', 'set-coa', 'set-jurnal',
        'set-labels', 'set-paymethod', 'set-num', 'set-wa',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'branch_id',
        'is_active',
        'role_label',
        'modules',
        'manager',
        'all_branches',
        'show_balance',
        'custom_price',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active'         => 'boolean',
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'modules'           => 'array',
            'manager'           => 'boolean',
            'all_branches'      => 'boolean',
            'show_balance'      => 'boolean',
            'custom_price'      => 'boolean',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class, 'branch_id', 'id');
    }

    public function getRolesListAttribute(): array
    {
        return $this->roles()->pluck('name')->all();
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'branch_user');
    }

    public function branchIds(): array
    {
        $ids = $this->branches->pluck('id')->all();

        if ($this->branch_id) {
            $ids[] = $this->branch_id;
        }

        return array_values(array_unique(array_map('strval', $ids)));
    }

    public function inBranch(?string $branchId): bool
    {
        return $branchId !== null && in_array((string) $branchId, $this->branchIds(), true);
    }

    public function canAccessBranch(?string $branchId): bool
    {
        return $this->all_branches || $this->inBranch($branchId);
    }

    public function canManageBranch(?string $branchId): bool
    {
        return $this->isManager() && $this->canAccessBranch($branchId);
    }

    public function branchScopeIds(?string $requestedBranchId = null): ?array
    {
        $requestedBranchId = $requestedBranchId !== null && $requestedBranchId !== ''
            ? $requestedBranchId
            : null;

        if ($requestedBranchId !== null) {
            return $this->canAccessBranch($requestedBranchId) ? [$requestedBranchId] : [];
        }

        return $this->all_branches ? null : $this->branchIds();
    }

    public function effectiveModules(): array
    {
        return $this->modules ?? [];
    }

    public function canModule(string $key): bool
    {
        return in_array($key, $this->effectiveModules(), true);
    }

    public function isManager(): bool
    {
        return (bool) $this->manager;
    }

    public function setUsernameAttribute(?string $v): void
    {
        $this->attributes['username'] = $v !== null ? strtolower(trim($v)) : null;
    }
}
