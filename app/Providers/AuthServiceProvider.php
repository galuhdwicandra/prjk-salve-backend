<?php
namespace App\Providers;

use App\Models\AccountingAccount;
use App\Models\AccountingAccountMapping;
use App\Models\AccountingJournalEntry;
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
use App\Policies\AccountingJournalEntryPolicy;
use App\Policies\BranchPolicy;
use App\Models\BranchType;
use App\Policies\BranchTypePolicy;
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
use App\Policies\CashTransactionPolicy;
use App\Models\CashTransaction;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        User::class                     => UserPolicy::class,
        Branch::class                   => BranchPolicy::class,
        BranchType::class               => BranchTypePolicy::class,
        ServiceCategory::class          => CategoryPolicy::class,
        Service::class                  => ServicePolicy::class,
        Customer::class                 => CustomerPolicy::class,
        Order::class                    => OrderPolicy::class,
        Delivery::class                 => DeliveryPolicy::class,
        Voucher::class                  => VoucherPolicy::class,
        Receivable::class               => ReceivablePolicy::class,
        Expense::class                  => ExpensePolicy::class,
        CashTransaction::class          => CashTransactionPolicy::class,
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

        Gate::define('dashboard.summary', fn(User $user) => $user->canModule('dashboard'));
    }
}
