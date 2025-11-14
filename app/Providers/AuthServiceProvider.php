<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Branch;
use App\Policies\BranchPolicy;
use App\Models\ServiceCategory;
use App\Policies\CategoryPolicy;
use App\Models\Service;
use App\Policies\ServicePolicy;
use App\Models\Customer;
use App\Policies\CustomerPolicy;
use App\Models\Order;
use App\Policies\OrderPolicy;
use App\Models\Delivery;
use App\Policies\DeliveryPolicy;
use App\Models\Voucher;
use App\Policies\VoucherPolicy;
use App\Models\Receivable;
use App\Policies\ReceivablePolicy;
class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        User::class => UserPolicy::class,
        Branch::class => BranchPolicy::class,
        ServiceCategory::class => CategoryPolicy::class,
        Service::class => ServicePolicy::class,
        Customer::class => CustomerPolicy::class,
        Order::class => OrderPolicy::class,
        Delivery::class => DeliveryPolicy::class,
        Voucher::class => VoucherPolicy::class,
        Receivable::class => ReceivablePolicy::class,
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Superadmin akses penuh (role mapping di SOP)
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Superadmin') ? true : null;
        });

        Gate::define(
            'user.assignRole',
            fn($user, $target = null) =>
            $user->hasAnyRole(['Superadmin', 'Admin Cabang'])
        );

        Gate::define('user.viewAny', fn($user) => $user->hasAnyRole(['Superadmin', 'Admin Cabang']));
        Gate::define(
            'user.view',
            fn($user, $target) =>
            $user->hasRole('Superadmin') ||
            ($user->hasRole('Admin Cabang') && ($target->branch_id === $user->branch_id)) ||
            ($user->id === $target->id)
        );
        Gate::define('user.create', fn($user) => $user->hasAnyRole(['Superadmin', 'Admin Cabang']));
        Gate::define(
            'user.update',
            fn($user, $target) =>
            $user->hasRole('Superadmin') ||
            ($user->hasRole('Admin Cabang') && ($target->branch_id === $user->branch_id)) ||
            ($user->id === $target->id)
        );
        Gate::define(
            'user.delete',
            fn($user, $target) =>
            $user->hasRole('Superadmin') ||
            ($user->hasRole('Admin Cabang') && ($target->branch_id === $user->branch_id))
        );
    }
}
