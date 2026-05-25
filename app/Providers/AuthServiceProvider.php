<?php
namespace App\Providers;

use App\Models\AccountingAccount;
use App\Models\AccountingAccountMapping;
use App\Models\AccountingJournalEntry;
use App\Policies\AccountingJournalEntryPolicy;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Receivable;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Models\Voucher;
use App\Models\WashNote;
use App\Models\WhatsappTemplate;
use App\Policies\AccountingAccountMappingPolicy;
use App\Policies\AccountingAccountPolicy;
use App\Policies\BranchPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\DeliveryPolicy;
use App\Policies\ExpensePolicy;
use App\Policies\OrderPolicy;
use App\Policies\ReceivablePolicy;
use App\Policies\ServicePolicy;
use App\Policies\UserPolicy;
use App\Policies\VoucherPolicy;
use App\Policies\WashNotePolicy;
use App\Policies\WhatsappTemplatePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        User::class                     => UserPolicy::class,
        Branch::class                   => BranchPolicy::class,
        ServiceCategory::class          => CategoryPolicy::class,
        Service::class                  => ServicePolicy::class,
        Customer::class                 => CustomerPolicy::class,
        Order::class                    => OrderPolicy::class,
        Delivery::class                 => DeliveryPolicy::class,
        Voucher::class                  => VoucherPolicy::class,
        Receivable::class               => ReceivablePolicy::class,
        Expense::class                  => ExpensePolicy::class,
        WashNote::class                 => WashNotePolicy::class,
        WhatsappTemplate::class         => WhatsappTemplatePolicy::class,
        AccountingAccount::class        => AccountingAccountPolicy::class,
        AccountingAccountMapping::class => AccountingAccountMappingPolicy::class,
        AccountingJournalEntry::class   => AccountingJournalEntryPolicy::class,
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

        Gate::define('dashboard.summary', function (User $user) {
            // Spatie roles; semua role boleh melihat ringkasan sesuai scope cabangnya.
            return $user->hasAnyRole(['Superadmin', 'Admin Cabang', 'Kasir', 'Petugas Cuci', 'Kurir']);
        });

        Gate::define(
            'user.delete',
            fn($user, $target) =>
            $user->hasRole('Superadmin') ||
            ($user->hasRole('Admin Cabang') && ($target->branch_id === $user->branch_id))
        );
    }
}
