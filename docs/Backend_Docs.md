# Dokumentasi Backend (FULL Source)

_Dihasilkan otomatis: 2025-12-08 02:35:38_  
**Root:** `/home/galuhdwicandra/projects/clone_salve/prjk-salve-backend`


## Daftar Isi

- [Controllers (app/Http/Controllers/Api)](#controllers-apphttpcontrollersapi)
  - [app/Http/Controllers/Api/AuthController.php](#file-apphttpcontrollersapiauthcontrollerphp)
  - [app/Http/Controllers/Api/BranchController.php](#file-apphttpcontrollersapibranchcontrollerphp)
  - [app/Http/Controllers/Api/CategoryController.php](#file-apphttpcontrollersapicategorycontrollerphp)
  - [app/Http/Controllers/Api/CustomerController.php](#file-apphttpcontrollersapicustomercontrollerphp)
  - [app/Http/Controllers/Api/DashboardController.php](#file-apphttpcontrollersapidashboardcontrollerphp)
  - [app/Http/Controllers/Api/DeliveryController.php](#file-apphttpcontrollersapideliverycontrollerphp)
  - [app/Http/Controllers/Api/ExpenseController.php](#file-apphttpcontrollersapiexpensecontrollerphp)
  - [app/Http/Controllers/Api/InvoiceCounterController.php](#file-apphttpcontrollersapiinvoicecountercontrollerphp)
  - [app/Http/Controllers/Api/LoyaltyController.php](#file-apphttpcontrollersapiloyaltycontrollerphp)
  - [app/Http/Controllers/Api/OrderController.php](#file-apphttpcontrollersapiordercontrollerphp)
  - [app/Http/Controllers/Api/OrderPaymentsController.php](#file-apphttpcontrollersapiorderpaymentscontrollerphp)
  - [app/Http/Controllers/Api/OrderPhotosController.php](#file-apphttpcontrollersapiorderphotoscontrollerphp)
  - [app/Http/Controllers/Api/ReceivableController.php](#file-apphttpcontrollersapireceivablecontrollerphp)
  - [app/Http/Controllers/Api/ReportController.php](#file-apphttpcontrollersapireportcontrollerphp)
  - [app/Http/Controllers/Api/ServiceController.php](#file-apphttpcontrollersapiservicecontrollerphp)
  - [app/Http/Controllers/Api/ServicePriceController.php](#file-apphttpcontrollersapiservicepricecontrollerphp)
  - [app/Http/Controllers/Api/UserController.php](#file-apphttpcontrollersapiusercontrollerphp)
  - [app/Http/Controllers/Api/VoucherController.php](#file-apphttpcontrollersapivouchercontrollerphp)
  - [app/Http/Controllers/Api/WashNoteController.php](#file-apphttpcontrollersapiwashnotecontrollerphp)

- [Models (app/Models)](#models-appmodels)
  - [app/Models/Branch.php](#file-appmodelsbranchphp)
  - [app/Models/Customer.php](#file-appmodelscustomerphp)
  - [app/Models/Delivery.php](#file-appmodelsdeliveryphp)
  - [app/Models/DeliveryEvent.php](#file-appmodelsdeliveryeventphp)
  - [app/Models/Expense.php](#file-appmodelsexpensephp)
  - [app/Models/InvoiceCounter.php](#file-appmodelsinvoicecounterphp)
  - [app/Models/LoyaltyAccount.php](#file-appmodelsloyaltyaccountphp)
  - [app/Models/LoyaltyLog.php](#file-appmodelsloyaltylogphp)
  - [app/Models/Order.php](#file-appmodelsorderphp)
  - [app/Models/OrderItem.php](#file-appmodelsorderitemphp)
  - [app/Models/OrderPhoto.php](#file-appmodelsorderphotophp)
  - [app/Models/OrderVoucher.php](#file-appmodelsordervoucherphp)
  - [app/Models/Payment.php](#file-appmodelspaymentphp)
  - [app/Models/Receivable.php](#file-appmodelsreceivablephp)
  - [app/Models/Service.php](#file-appmodelsservicephp)
  - [app/Models/ServiceCategory.php](#file-appmodelsservicecategoryphp)
  - [app/Models/ServicePrice.php](#file-appmodelsservicepricephp)
  - [app/Models/User.php](#file-appmodelsuserphp)
  - [app/Models/Voucher.php](#file-appmodelsvoucherphp)
  - [app/Models/WashNote.php](#file-appmodelswashnotephp)
  - [app/Models/WashNoteItem.php](#file-appmodelswashnoteitemphp)

- [Policies (app/Policies)](#policies-apppolicies)
  - [app/Policies/BranchPolicy.php](#file-apppoliciesbranchpolicyphp)
  - [app/Policies/CategoryPolicy.php](#file-apppoliciescategorypolicyphp)
  - [app/Policies/CustomerPolicy.php](#file-apppoliciescustomerpolicyphp)
  - [app/Policies/DeliveryPolicy.php](#file-apppoliciesdeliverypolicyphp)
  - [app/Policies/ExpensePolicy.php](#file-apppoliciesexpensepolicyphp)
  - [app/Policies/LoyaltyPolicy.php](#file-apppoliciesloyaltypolicyphp)
  - [app/Policies/OrderPolicy.php](#file-apppoliciesorderpolicyphp)
  - [app/Policies/ReceivablePolicy.php](#file-apppoliciesreceivablepolicyphp)
  - [app/Policies/ServicePolicy.php](#file-apppoliciesservicepolicyphp)
  - [app/Policies/UserPolicy.php](#file-apppoliciesuserpolicyphp)
  - [app/Policies/VoucherPolicy.php](#file-apppoliciesvoucherpolicyphp)
  - [app/Policies/WashNotePolicy.php](#file-apppolicieswashnotepolicyphp)

- [Form Requests (app/Http/Requests)](#form-requests-apphttprequests)
  - [app/Http/Requests/Auth/LoginRequest.php](#file-apphttprequestsauthloginrequestphp)
  - [app/Http/Requests/BranchStoreRequest.php](#file-apphttprequestsbranchstorerequestphp)
  - [app/Http/Requests/BranchUpdateRequest.php](#file-apphttprequestsbranchupdaterequestphp)
  - [app/Http/Requests/CategoryStoreRequest.php](#file-apphttprequestscategorystorerequestphp)
  - [app/Http/Requests/CategoryUpdateRequest.php](#file-apphttprequestscategoryupdaterequestphp)
  - [app/Http/Requests/CustomerSearchWARequest.php](#file-apphttprequestscustomersearchwarequestphp)
  - [app/Http/Requests/CustomerStoreRequest.php](#file-apphttprequestscustomerstorerequestphp)
  - [app/Http/Requests/CustomerUpdateRequest.php](#file-apphttprequestscustomerupdaterequestphp)
  - [app/Http/Requests/Dashboard/DashboardSummaryRequest.php](#file-apphttprequestsdashboarddashboardsummaryrequestphp)
  - [app/Http/Requests/Deliveries/DeliveryAssignRequest.php](#file-apphttprequestsdeliveriesdeliveryassignrequestphp)
  - [app/Http/Requests/Deliveries/DeliveryStatusRequest.php](#file-apphttprequestsdeliveriesdeliverystatusrequestphp)
  - [app/Http/Requests/Deliveries/DeliveryStoreRequest.php](#file-apphttprequestsdeliveriesdeliverystorerequestphp)
  - [app/Http/Requests/Expenses/ExpenseStoreRequest.php](#file-apphttprequestsexpensesexpensestorerequestphp)
  - [app/Http/Requests/Expenses/ExpenseUpdateRequest.php](#file-apphttprequestsexpensesexpenseupdaterequestphp)
  - [app/Http/Requests/InvoiceCounterStoreRequest.php](#file-apphttprequestsinvoicecounterstorerequestphp)
  - [app/Http/Requests/InvoiceCounterUpdateRequest.php](#file-apphttprequestsinvoicecounterupdaterequestphp)
  - [app/Http/Requests/OrderStatusRequest.php](#file-apphttprequestsorderstatusrequestphp)
  - [app/Http/Requests/OrderStoreRequest.php](#file-apphttprequestsorderstorerequestphp)
  - [app/Http/Requests/OrderUpdateRequest.php](#file-apphttprequestsorderupdaterequestphp)
  - [app/Http/Requests/Orders/OrderApplyVoucherRequest.php](#file-apphttprequestsordersorderapplyvoucherrequestphp)
  - [app/Http/Requests/Orders/OrderPhotosRequest.php](#file-apphttprequestsordersorderphotosrequestphp)
  - [app/Http/Requests/Payments/PaymentRequest.php](#file-apphttprequestspaymentspaymentrequestphp)
  - [app/Http/Requests/Receivables/ReceivableCreateRequest.php](#file-apphttprequestsreceivablesreceivablecreaterequestphp)
  - [app/Http/Requests/Receivables/ReceivableSettleRequest.php](#file-apphttprequestsreceivablesreceivablesettlerequestphp)
  - [app/Http/Requests/Reports/ReportFilterRequest.php](#file-apphttprequestsreportsreportfilterrequestphp)
  - [app/Http/Requests/ResetPasswordRequest.php](#file-apphttprequestsresetpasswordrequestphp)
  - [app/Http/Requests/ServicePriceSetRequest.php](#file-apphttprequestsservicepricesetrequestphp)
  - [app/Http/Requests/ServiceStoreRequest.php](#file-apphttprequestsservicestorerequestphp)
  - [app/Http/Requests/ServiceUpdateRequest.php](#file-apphttprequestsserviceupdaterequestphp)
  - [app/Http/Requests/UserStoreRequest.php](#file-apphttprequestsuserstorerequestphp)
  - [app/Http/Requests/UserUpdateRequest.php](#file-apphttprequestsuserupdaterequestphp)
  - [app/Http/Requests/Vouchers/VoucherStoreRequest.php](#file-apphttprequestsvouchersvoucherstorerequestphp)
  - [app/Http/Requests/Vouchers/VoucherUpdateRequest.php](#file-apphttprequestsvouchersvoucherupdaterequestphp)
  - [app/Http/Requests/WashNoteStoreRequest.php](#file-apphttprequestswashnotestorerequestphp)
  - [app/Http/Requests/WashNoteUpdateRequest.php](#file-apphttprequestswashnoteupdaterequestphp)

- [Services (app/Services)](#services-appservices)
  - [app/Services/AuthService.php](#file-appservicesauthservicephp)
  - [app/Services/DashboardService.php](#file-appservicesdashboardservicephp)
  - [app/Services/DeliveryService.php](#file-appservicesdeliveryservicephp)
  - [app/Services/InvoiceNumberService.php](#file-appservicesinvoicenumberservicephp)
  - [app/Services/InvoiceService.php](#file-appservicesinvoiceservicephp)
  - [app/Services/LoyaltyService.php](#file-appservicesloyaltyservicephp)
  - [app/Services/OrderNumberService.php](#file-appservicesordernumberservicephp)
  - [app/Services/OrderService.php](#file-appservicesorderservicephp)
  - [app/Services/PaymentService.php](#file-appservicespaymentservicephp)
  - [app/Services/PricingService.php](#file-appservicespricingservicephp)
  - [app/Services/ReceivableService.php](#file-appservicesreceivableservicephp)
  - [app/Services/ReportService.php](#file-appservicesreportservicephp)
  - [app/Services/UserService.php](#file-appservicesuserservicephp)
  - [app/Services/VoucherService.php](#file-appservicesvoucherservicephp)

- [Database (seeders)](#database-seeders)
  - [database/seeders/BranchSeeder.php](#file-databaseseedersbranchseederphp)
  - [database/seeders/DatabaseSeeder.php](#file-databaseseedersdatabaseseederphp)
  - [database/seeders/RolesTableSeeder.php](#file-databaseseedersrolestableseederphp)
  - [database/seeders/UserSeeder.php](#file-databaseseedersuserseederphp)

- [resources (resources)](#resources-resources)
  - [resources/views/orders/receipt.blade.php](#file-resourcesviewsordersreceiptbladephp)

- [routes/api.php](#routesapiphp)

- [AuthServiceProvider.php](#authserviceproviderphp)



## Controllers (app/Http/Controllers/Api)

### app/Http/Controllers/Api/AuthController.php

- SHA: `9226ec9d4fbb`  
- Ukuran: 2 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `AuthController` extends `Controller`**

Metode Publik:
- **__construct**(private AuthService $auth)
- **login**(Request $request) : *JsonResponse*
- **me**(Request $request) : *JsonResponse*
- **logout**(Request $request) : *JsonResponse*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(private AuthService $auth) {}

    public function login(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $res = $this->auth->login($payload['login'], $payload['password']);
        if (!$res['ok']) {
            return response()->json([
                'data' => null,
                'meta' => null,
                'message' => 'Unauthenticated',
                'errors' => ['auth' => [$res['message']]],
            ], 401);
        }

        return response()->json([
            'data' => ['user' => $res['user']],
            'meta' => ['token' => $res['token']],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'data' => ['user' => $this->auth->me($user)],
            'meta' => null,
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->auth->logout($request->user());

        return response()->json([
            'data' => null,
            'meta' => null,
            'message' => 'Logged out',
            'errors' => null,
        ]);
    }
}

```
</details>

### app/Http/Controllers/Api/BranchController.php

- SHA: `6e032450ac63`  
- Ukuran: 2 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `BranchController` extends `Controller`**

Metode Publik:
- **index**(Request $request)
- **show**(Branch $branch)
- **store**(BranchStoreRequest $request)
- **update**(BranchUpdateRequest $request, Branch $branch)
- **destroy**(Branch $branch)
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchStoreRequest;
use App\Http\Requests\BranchUpdateRequest;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Branch::class);

        $q = Branch::query()->orderBy('created_at', 'desc');

        if ($search = $request->query('q')) {
            $q->where(function ($w) use ($search) {
                $w->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $items = $q->paginate((int) $request->query('per_page', 10));

        return response()->json([
            'data' => $items->items(),
            'meta' => [
                'current_page' => $items->currentPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
                'last_page' => $items->lastPage(),
            ],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function show(Branch $branch)
    {
        $this->authorize('view', $branch);

        return response()->json([
            'data' => $branch,
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function store(BranchStoreRequest $request)
    {
        $payload = $request->validated();

        $this->authorize('create', Branch::class);

        $branch = new Branch($payload);
        $branch->id = (string) Str::uuid();
        $branch->save();

        return response()->json([
            'data' => $branch,
            'meta' => [],
            'message' => 'Created',
            'errors' => null,
        ], 201);
    }

    public function update(BranchUpdateRequest $request, Branch $branch)
    {
        $payload = $request->validated();

        $this->authorize('update', $branch);

        $branch->fill($payload)->save();

        return response()->json([
            'data' => $branch,
            'meta' => [],
            'message' => 'Updated',
            'errors' => null,
        ]);
    }

    public function destroy(Branch $branch)
    {
        $this->authorize('delete', $branch);

        $branch->delete();

        return response()->json([
            'data' => null,
            'meta' => [],
            'message' => 'Deleted',
            'errors' => null,
        ]);
    }
}

```
</details>

### app/Http/Controllers/Api/CategoryController.php

- SHA: `69f1f77ad824`  
- Ukuran: 3 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `CategoryController` extends `Controller`**

Metode Publik:
- **index**(Request $request)
- **show**(ServiceCategory $category)
- **store**(CategoryStoreRequest $request)
- **update**(CategoryUpdateRequest $request, ServiceCategory $category)
- **destroy**(ServiceCategory $category)
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', ServiceCategory::class);

        $q = ServiceCategory::query()->orderBy('name');
        if ($s = $request->query('q')) {
            $q->where('name', 'like', "%{$s}%");
        }
        if (($act = $request->query('is_active')) !== null) {
            $q->where('is_active', (bool) $act);
        }
        $items = $q->paginate((int) $request->query('per_page', 10));

        return response()->json([
            'data' => $items->items(),
            'meta' => [
                'current_page' => $items->currentPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
                'last_page' => $items->lastPage(),
            ],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function show(ServiceCategory $category)
    {
        $this->authorize('view', $category);

        return response()->json([
            'data' => $category,
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function store(CategoryStoreRequest $request)
    {
        $payload = $request->validated();
        $this->authorize('create', ServiceCategory::class);

        $category = new ServiceCategory($payload);
        $category->id = (string) Str::uuid();
        $category->save();

        // TODO: audit('CATEGORY_CREATE', $category)

        return response()->json([
            'data' => $category,
            'meta' => [],
            'message' => 'Created',
            'errors' => null,
        ], 201);
    }

    public function update(CategoryUpdateRequest $request, ServiceCategory $category)
    {
        $payload = $request->validated();
        $this->authorize('update', $category);

        $category->fill($payload)->save();

        // TODO: audit('CATEGORY_UPDATE', $category)

        return response()->json([
            'data' => $category,
            'meta' => [],
            'message' => 'Updated',
            'errors' => null,
        ]);
    }

    public function destroy(ServiceCategory $category)
    {
        $this->authorize('delete', $category);
        $category->delete();

        // TODO: audit('CATEGORY_DELETE', ['id' => $category->id])

        return response()->json([
            'data' => null,
            'meta' => [],
            'message' => 'Deleted',
            'errors' => null,
        ]);
    }
}

```
</details>

### app/Http/Controllers/Api/CustomerController.php

- SHA: `3182cb22cc7f`  
- Ukuran: 5 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `CustomerController` extends `Controller`**

Metode Publik:
- **index**(Request $request)
- **show**(Customer $customer)
- **store**(CustomerStoreRequest $request)
- **update**(CustomerUpdateRequest $request, Customer $customer) — @var Customer $customer
- **destroy**(Request $request, Customer $customer) — @var Customer $customer
- **searchByWhatsapp**(CustomerSearchWARequest $request) — @var Customer $customer
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Http\Requests\CustomerSearchWARequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Customer::class);

        $q = Customer::query()->orderByDesc('created_at');

        // Scope cabang
        if ($branchId = $this->branchScopeFor($request)) {
            $q->where('branch_id', $branchId);
        }

        if ($s = $request->query('q')) {
            $q->where(function ($w) use ($s) {
                $w->where('name', 'like', "%{$s}%")
                    ->orWhere('whatsapp', 'like', "%{$s}%")
                    ->orWhere('address', 'like', "%{$s}%");
            });
        }

        $items = $q->paginate((int) $request->query('per_page', 10));

        return response()->json([
            'data' => $items->items(),
            'meta' => [
                'current_page' => $items->currentPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
                'last_page' => $items->lastPage(),
            ],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function show(Customer $customer)
    {
        $this->authorize('view', $customer);

        return response()->json([
            'data' => $customer,
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function store(CustomerStoreRequest $request)
    {
        $payload = $request->validated();

        // Admin Cabang: paksa branch_id miliknya bila kosong
        if ($request->user()->hasRole('Admin Cabang') || $request->user()->hasRole('Kasir')) {
            $payload['branch_id'] = $payload['branch_id'] ?? $request->user()->branch_id;
        }

        $this->authorize('create', Customer::class);

        /** @var Customer $customer */
        $customer = DB::transaction(function () use ($payload) {
            $c = new Customer($payload);
            $c->id = (string) Str::uuid();
            $c->save();

            // TODO: audit('CUSTOMER_CREATE', $c)

            return $c;
        });

        return response()->json([
            'data' => $customer,
            'meta' => [],
            'message' => 'Created',
            'errors' => null,
        ], 201);
    }

    public function update(CustomerUpdateRequest $request, Customer $customer)
    {
        $payload = $request->validated();
        $this->authorize('update', $customer);

        DB::transaction(function () use ($customer, $payload) {
            $customer->fill($payload)->save();
            // TODO: audit('CUSTOMER_UPDATE', $customer)
        });

        return response()->json([
            'data' => $customer->refresh(),
            'meta' => [],
            'message' => 'Updated',
            'errors' => null,
        ]);
    }

    public function destroy(Request $request, Customer $customer)
    {
        $this->authorize('delete', $customer);

        DB::transaction(function () use ($customer) {
            $customer->delete();
            // TODO: audit('CUSTOMER_DELETE', ['id' => $customer->id])
        });

        return response()->json([
            'data' => null,
            'meta' => [],
            'message' => 'Deleted',
            'errors' => null,
        ]);
    }

    /** GET /customers/search-wa?wa=... */
    public function searchByWhatsapp(CustomerSearchWARequest $request)
    {
        $this->authorize('viewAny', Customer::class);

        $wa = preg_replace('/\s+/', '', (string) $request->query('wa'));
        $branchId = $this->branchScopeFor($request);

        $q = Customer::query()->where('whatsapp', $wa);
        if ($branchId) {
            $q->where('branch_id', $branchId);
        }

        $found = $q->first();

        if (!$found) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'Not found',
                'errors' => ['wa' => ['not_found']],
            ], 404);
        }

        return response()->json([
            'data' => $found,
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    private function branchScopeFor(Request $request): ?string
    {
        $me = $request->user();
        if ($me->hasRole('Superadmin')) {
            return (string) $request->query('branch_id') ?: null;
        }
        if (($me->hasRole('Admin Cabang') || $me->hasRole('Kasir')) && $me->branch_id) {
            return (string) $me->branch_id;
        }
        return null;
    }
}

```
</details>

### app/Http/Controllers/Api/DashboardController.php

- SHA: `ed75c6016132`  
- Ukuran: 925 B  
- Namespace: `App\Http\Controllers\Api`

**Class `DashboardController` extends `Controller`**

Metode Publik:
- **summary**(DashboardSummaryRequest $request, DashboardService $service)
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\DashboardSummaryRequest;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function summary(DashboardSummaryRequest $request, DashboardService $service)
    {
        Gate::authorize('dashboard.summary');

        $data = $service->summary(
            $request->fromDate(),
            $request->toDate(),
            $request->branchId(),
        );

        return response()->json([
            'data' => $data,
            'meta' => [
                'from' => $request->input('from'),
                'to' => $request->input('to'),
                'branch_id' => $request->input('branch_id'),
            ],
            'message' => 'OK',
        ], Response::HTTP_OK);
    }
}

```
</details>

### app/Http/Controllers/Api/DeliveryController.php

- SHA: `57ff79d75d6d`  
- Ukuran: 5 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `DeliveryController` extends `Controller`**

Metode Publik:
- **__construct**(private DeliveryService $svc)
- **index**(Request $request)
- **show**(Delivery $delivery)
- **store**(DeliveryStoreRequest $request)
- **assign**(DeliveryAssignRequest $request, Delivery $delivery)
- **updateStatus**(DeliveryStatusRequest $request, Delivery $delivery)
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deliveries\DeliveryStoreRequest;
use App\Http\Requests\Deliveries\DeliveryAssignRequest;
use App\Http\Requests\Deliveries\DeliveryStatusRequest;
use App\Models\{Order, Delivery};
use App\Services\DeliveryService;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function __construct(private DeliveryService $svc)
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Delivery::class);

        $user = $request->user();
        $q = Delivery::query()
            ->with(['courier:id,name', 'order:id,branch_id,number,invoice_no'])
            ->latest('created_at');

        // Filter umum
        if ($status = $request->query('status')) {
            $q->where('status', $status);
        }
        if ($courierId = $request->query('courier_id')) {
            $q->where('assigned_to', $courierId);
        }
        if ($term = trim((string) $request->query('q', ''))) {
            $q->where(function ($w) use ($term) {
                $w->whereRaw('id::text ILIKE ?', ["%{$term}%"])
                    ->orWhereRaw('order_id::text ILIKE ?', ["%{$term}%"])
                    ->orWhereHas('order', function ($oq) use ($term) {
                        $oq->where('number', 'ILIKE', "%{$term}%")
                            ->orWhere('invoice_no', 'ILIKE', "%{$term}%");
                    });
            });
        }

        // Scope cabang & peran (pola sama seperti controller lain yang Anda pakai)
        $branchId = $this->branchScopeFor($request); // lihat helper di bawah
        if ($branchId) {
            $q->whereHas('order', fn($oq) => $oq->where('branch_id', $branchId));
        }

        // Kurir hanya lihat yang ditugaskan
        if ($user->hasRole('Kurir')) {
            $q->where('assigned_to', $user->id);
        }

        $per = max(1, min(200, (int) $request->query('per_page', 50)));
        $page = $q->paginate($per);

        $items = collect($page->items())->map(function (Delivery $d) {
            return [
                'id' => $d->id,
                'order_id' => $d->order_id,
                'order_invoice_no' => $d->order?->invoice_no,
                'order_number'     => $d->order?->number,
                'type' => $d->type,
                'fee' => $d->fee,
                'assigned_to' => $d->assigned_to,
                'status' => $d->status,
                'created_at' => $d->created_at,
                // opsional: info kurir ringkas
                'courier' => $d->courier ? ['id' => $d->courier->id, 'name' => $d->courier->name] : null,
            ];
        })->all();

        return response()->json([
            'data' => $items,
            'meta' => [
                'current_page' => $page->currentPage(),
                'per_page' => $page->perPage(),
                'total' => $page->total(),
                'last_page' => $page->lastPage(),
            ],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function show(Delivery $delivery)
    {
        $this->authorize('view', $delivery);
        return response()->json([
            'data' => $delivery->load(['courier:id,name', 'events']),
            'meta' => null,
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function store(DeliveryStoreRequest $request)
    {
        $payload = $request->validated();
        $order = Order::query()->with('branch')->findOrFail($payload['order_id']);

        $this->authorize('create', Delivery::class);

        $delivery = $this->svc->create($order, $payload, $request->user());
        $res = $this->svc->autoAssign($order->getKey());

        return response()->json([
            'data' => [
                'delivery' => $res['delivery'],
            ],
            'meta' => ['idempotent' => $res['idempotent']],
            'message' => $res['idempotent'] ? 'Created (already assigned)' : 'Created & auto-assigned',
            'errors' => null,
        ], 201);
    }

    public function assign(DeliveryAssignRequest $request, Delivery $delivery)
    {
        $delivery->loadMissing('order');
        $this->authorize('assignCourier', $delivery);

        $next = $this->svc->assignManual($delivery, (int) $request->validated()['courier_id'], $request->user());

        return response()->json([
            'data' => $next,
            'meta' => [],
            'message' => 'Courier assigned',
            'errors' => null,
        ]);
    }

    public function updateStatus(DeliveryStatusRequest $request, Delivery $delivery)
    {
        $delivery->loadMissing('order');
        $this->authorize('updateStatus', $delivery);

        $f = $request->file('photo');
        $next = $this->svc->updateStatus(
            $delivery,
            $request->validated()['status'],
            $f,
            $request->validated()['note'] ?? null,
            $request->user()
        );

        return response()->json([
            'data' => $next,
            'meta' => [],
            'message' => 'Status updated',
            'errors' => null,
        ]);
    }

    private function branchScopeFor(Request $request): ?string
    {
        $u = $request->user();
        if ($u->hasRole('Superadmin')) {
            $bid = (string) $request->query('branch_id', '');
            return $bid !== '' ? $bid : null;
        }
        return $u->branch_id ?: null;
    }
}

```
</details>

### app/Http/Controllers/Api/ExpenseController.php

- SHA: `3f7933189b3b`  
- Ukuran: 4 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `ExpenseController` extends `Controller`**

Metode Publik:
- **index**(Request $request)
- **store**(ExpenseStoreRequest $request)
- **show**(Expense $expense)
- **update**(ExpenseUpdateRequest $request, Expense $expense)
- **destroy**(Request $request, Expense $expense)
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Expenses\ExpenseStoreRequest;
use App\Http\Requests\Expenses\ExpenseUpdateRequest;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Expense::class);

        $user = $request->user();

        $query = Expense::query()
            ->with('branch')
            ->orderByDesc('created_at');

        // Scope per cabang:
        // - Superadmin: boleh lihat semua, bisa filter branch_id
        // - Admin Cabang: hanya cabangnya sendiri
        if ($user->hasRole('Superadmin')) {
            if ($branchId = $request->query('branch_id')) {
                $query->where('branch_id', $branchId);
            }
        } else {
            if ($user->branch_id !== null) {
                $query->where('branch_id', $user->branch_id);
            }
        }

        // Optional filter tanggal (kalau nanti dipakai di F11)
        if ($dateFrom = $request->query('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->query('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $perPage = min($request->integer('per_page', 50), 100);

        return $query->paginate($perPage);
    }

    public function store(ExpenseStoreRequest $request)
    {
        $this->authorize('create', Expense::class);

        $user = $request->user();

        $branchId = null;

        if ($user->hasRole('Superadmin')) {
            $branchId = $request->input('branch_id');

            if (!$branchId) {
                return response()->json([
                    'message' => 'branch_id wajib diisi untuk Superadmin.',
                ], 422);
            }
        } else {
            $branchId = $user->branch_id;

            if (!$branchId) {
                return response()->json([
                    'message' => 'User tidak memiliki cabang yang terasosiasi.',
                ], 422);
            }
        }

        $data = $request->validated();

        $data['branch_id'] = $branchId;

        // Handle upload bukti
        if ($request->hasFile('proof')) {
            $storedPath = $request
                ->file('proof')
                ->store('uploads/expenses', 'public');

            // Sesuai pola order_photos: path yang disimpan "storage/..."
            $data['proof_path'] = 'storage/' . $storedPath;
        }

        $expense = Expense::create($data);

        return response()->json([
            'data' => $expense->load('branch'),
        ], 201);
    }

    public function show(Expense $expense)
    {
        $this->authorize('view', $expense);

        return response()->json([
            'data' => $expense->load('branch'),
        ]);
    }

    public function update(ExpenseUpdateRequest $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $data = $request->validated();

        // branch_id tidak boleh diubah lewat update
        unset($data['branch_id']);

        // Jika ada file baru, hapus file lama
        if ($request->hasFile('proof')) {
            if ($expense->proof_path) {
                $this->deleteProofFile($expense->proof_path);
            }

            $storedPath = $request
                ->file('proof')
                ->store('uploads/expenses', 'public');

            $data['proof_path'] = 'storage/' . $storedPath;
        }

        $expense->update($data);

        return response()->json([
            'data' => $expense->fresh()->load('branch'),
        ]);
    }

    public function destroy(Request $request, Expense $expense)
    {
        $this->authorize('delete', $expense);

        if ($expense->proof_path) {
            $this->deleteProofFile($expense->proof_path);
        }

        $expense->delete();

        return response()->json([], 204);
    }

    private function deleteProofFile(string $storedPath): void
    {
        // storedPath berbentuk "storage/uploads/expenses/xxx.ext"
        // Disk 'public' menyimpan di "app/public/..."
        $relativePath = preg_replace('#^storage/#', '', $storedPath);

        Storage::disk('public')->delete($relativePath);
    }
}

```
</details>

### app/Http/Controllers/Api/InvoiceCounterController.php

- SHA: `5aff7a2f1617`  
- Ukuran: 7 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `InvoiceCounterController` extends `Controller`**

Metode Publik:
- **index**(Request $request)
- **store**(InvoiceCounterStoreRequest $request)
- **update**(InvoiceCounterUpdateRequest $request, string $id)
- **destroy**(string $id)
- **preview**(Request $request, InvoiceService $invoice)
- **resetNow**(string $id) — POST /invoice-counters/{id}/reset-now
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceCounterStoreRequest;
use App\Http\Requests\InvoiceCounterUpdateRequest;
use App\Models\Branch;
use App\Models\InvoiceCounter;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceCounterController extends Controller
{
    public function index(Request $request)
    {
        $branchId = (string) $request->query('branch_id');
        if (!$branchId) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'branch_id is required',
                'errors' => ['branch_id' => ['required']],
            ], 422);
        }

        $branch = Branch::query()->find($branchId);
        if (!$branch) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'Branch not found',
                'errors' => ['branch_id' => ['not_found']],
            ], 404);
        }

        // Otorisasi: admin_cabang hanya boleh melihat cabangnya
        $this->authorize('view', $branch);

        $items = InvoiceCounter::query()
            ->where('branch_id', $branch->id)
            ->orderBy('prefix')
            ->get();

        return response()->json([
            'data' => $items,
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function store(InvoiceCounterStoreRequest $request)
    {
        $payload = $request->validated();
        $branch = Branch::query()->find($payload['branch_id']);
        if (!$branch) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'Branch not found',
                'errors' => ['branch_id' => ['not_found']],
            ], 404);
        }

        // Otorisasi: hanya yang bisa update/manajemen branch yang boleh menambah counter
        $this->authorize('update', $branch);

        // Unik per (branch_id, prefix)
        $exists = InvoiceCounter::query()
            ->where('branch_id', $payload['branch_id'])
            ->where('prefix', $payload['prefix'])
            ->exists();
        if ($exists) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'Prefix already exists for this branch',
                'errors' => ['prefix' => ['unique_for_branch']],
            ], 422);
        }

        $counter = InvoiceCounter::query()->create([
            'branch_id' => $payload['branch_id'],
            'prefix' => strtoupper($payload['prefix']),
            'seq' => 0,            // mulai dari 0; akan naik saat dipakai
            'reset_policy' => $payload['reset_policy'],
            'last_reset_month' => null,         // biar generator yang set saat first use/monthly reset
        ]);

        return response()->json([
            'data' => $counter,
            'meta' => [],
            'message' => 'Created',
            'errors' => null,
        ], 201);
    }


    public function update(InvoiceCounterUpdateRequest $request, string $id)
    {
        $counter = InvoiceCounter::query()->find($id);
        if (!$counter) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'Counter not found',
                'errors' => ['id' => ['not_found']],
            ], 404);
        }

        // Otorisasi via cabang
        $branch = $counter->branch;
        $this->authorize('update', $branch);

        $payload = $request->validated();

        // Pastikan unique (branch_id, prefix) bila prefix berubah
        if (isset($payload['prefix']) && $payload['prefix'] !== $counter->prefix) {
            $exists = InvoiceCounter::query()
                ->where('branch_id', $counter->branch_id)
                ->where('prefix', $payload['prefix'])
                ->exists();
            if ($exists) {
                return response()->json([
                    'data' => null,
                    'meta' => [],
                    'message' => 'Prefix already exists for this branch',
                    'errors' => ['prefix' => ['unique_for_branch']],
                ], 422);
            }
        }

        $counter->fill($payload)->save();

        return response()->json([
            'data' => $counter,
            'meta' => [],
            'message' => 'Updated',
            'errors' => null,
        ]);
    }

    public function destroy(string $id)
    {
        $counter = InvoiceCounter::query()->find($id);
        if (!$counter) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'Counter not found',
                'errors' => ['id' => ['not_found']],
            ], 404);
        }
        $this->authorize('update', $counter->branch);

        // Tambahkan guard jika sudah terpakai di invoice (optional)
        // if ($counter->invoices()->exists()) { return 409 … }

        $counter->delete();
        return response()->json([
            'data' => null,
            'meta' => [],
            'message' => 'Deleted',
            'errors' => null,
        ]);
    }

    public function preview(Request $request, InvoiceService $invoice)
    {
        $branchId = (string) $request->query('branch_id');
        if (!$branchId) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'branch_id is required',
                'errors' => ['branch_id' => ['required']],
            ], 422);
        }

        $branch = Branch::query()->find($branchId);
        if (!$branch) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'Branch not found',
                'errors' => ['branch_id' => ['not_found']],
            ], 404);
        }

        $this->authorize('update', $branch);

        $result = $invoice->preview($branchId);
        return response()->json([
            'data' => $result,
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    /**
     * POST /invoice-counters/{id}/reset-now
     * Reset seq ke 0 dan set last_reset_month ke bulan berjalan.
     * Otorisasi: update pada Branch terkait counter.
     */
    public function resetNow(string $id)
    {
        /** @var InvoiceCounter|null $counter */
        $counter = InvoiceCounter::query()->find($id);
        if (!$counter) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'InvoiceCounter not found',
                'errors' => ['id' => ['not_found']],
            ], 404);
        }

        $branch = $counter->branch;
        $this->authorize('update', $branch);

        $counter->seq = 0;
        $counter->last_reset_month = now('Asia/Jakarta')->format('Ym');
        $counter->save();

        return response()->json([
            'data' => $counter->fresh(),
            'meta' => [],
            'message' => 'Reset OK',
            'errors' => null,
        ]);
    }
}

```
</details>

### app/Http/Controllers/Api/LoyaltyController.php

- SHA: `5d35fd551cef`  
- Ukuran: 2 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `LoyaltyController` extends `Controller`**

Metode Publik:
- **__construct**(private LoyaltyService $svc)
- **summary**(Request $req, Customer $customer)
- **history**(Request $req, Customer $customer)
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\LoyaltyLog;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    public function __construct(private LoyaltyService $svc) {}

    public function summary(Request $req, Customer $customer)
    {
        $this->authorize('viewLoyalty', $customer);

        $branchId = (string) ($req->query('branch_id') ?: $req->user()->branch_id ?: $customer->branch_id);
        $acc = $this->svc->getOrCreateAccount((string) $customer->getKey(), $branchId);

        return response()->json([
            'data' => [
                'stamps' => (int) $acc->stamps,
                'cycle'  => 10,
                'next'   => (($acc->stamps % 10) + 1),
            ],
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function history(Request $req, Customer $customer)
    {
        $this->authorize('viewLoyalty', $customer);

        $branchId = (string) ($req->query('branch_id') ?: $req->user()->branch_id ?: $customer->branch_id);
        $logs = LoyaltyLog::query()
            ->where('customer_id', (string) $customer->getKey())
            ->where('branch_id', $branchId)
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'data' => $logs->items(),
            'meta' => ['current_page' => $logs->currentPage(), 'last_page' => $logs->lastPage()],
            'message' => 'OK',
            'errors' => null,
        ]);
    }
}

```
</details>

### app/Http/Controllers/Api/OrderController.php

- SHA: `b21ecbafe3cb`  
- Ukuran: 7 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `OrderController` extends `Controller`**

Metode Publik:
- **__construct**(private OrderService $svc)
- **index**(Request $request)
- **show**(Order $order)
- **store**(OrderStoreRequest $request)
- **update**(OrderUpdateRequest $request, Order $order)
- **receipt**(Request $request, Order $order)
- **shareLink**(Request $request, Order $order) : *JsonResponse* — @var \App\Services\LoyaltyService $loySvc
- **transitionStatus**(OrderStatusRequest $request, Order $order) — @var \App\Services\LoyaltyService $loySvc
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Requests\OrderStatusRequest;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;

class OrderController extends Controller
{
    public function __construct(private OrderService $svc) {}

    // GET /orders
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        $me = $request->user();
        $q = Order::query()
            ->with(['customer', 'items.service', 'receivable'])
            ->withCount('payments');

        // ===== (1) Sorting yang fleksibel =====
        $sortBy  = in_array($request->query('sort_by'), ['created_at', 'received_at', 'ready_at'])
            ? $request->query('sort_by') : 'created_at';
        $sortDir = strtolower((string) $request->query('sort_dir')) === 'asc' ? 'asc' : 'desc';
        $q->orderBy($sortBy, $sortDir);

        // ===== (2) Scope cabang =====
        if ($me->hasRole('Superadmin')) {
            if ($branchId = (string) $request->query('branch_id')) {
                $q->where('branch_id', $branchId);
            }
        } else if ($me->branch_id) {
            $q->where('branch_id', $me->branch_id);
        }

        // ===== (3) Pencarian cepat =====
        if ($s = $request->query('q')) {
            $q->where(function ($w) use ($s) {
                $w->where('number', 'like', "%{$s}%")
                    ->orWhere('notes', 'like', "%{$s}%");
            });
        }
        if ($st = $request->query('status')) {
            $q->where('status', $st);
        }

        // ===== (4) Filter tanggal existing (created_at) — tetap dipertahankan =====
        if ($from = $request->query('from')) {
            $q->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->query('to')) {
            $q->whereDate('created_at', '<=', $to);
        }

        // ===== (5) Filter tanggal baru: received_at =====
        if ($rf = $request->query('received_from')) {
            $q->whereDate('received_at', '>=', $rf);
        }
        if ($rt = $request->query('received_to')) {
            $q->whereDate('received_at', '<=', $rt);
        }

        // ===== (6) Filter tanggal baru: ready_at =====
        if ($yf = $request->query('ready_from')) {
            $q->whereDate('ready_at', '>=', $yf);
        }
        if ($yt = $request->query('ready_to')) {
            $q->whereDate('ready_at', '<=', $yt);
        }

        $per  = (int) max(1, min(100, (int) $request->query('per_page', 10)));
        $page = $q->paginate($per);

        return response()->json([
            'data' => $page->items(),
            'meta' => [
                'current_page' => $page->currentPage(),
                'per_page'     => $page->perPage(),
                'total'        => $page->total(),
                'last_page'    => $page->lastPage(),
                'sort_by'      => $sortBy,
                'sort_dir'     => $sortDir,
            ],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }


    public function show(Order $order)
    {
        $this->authorize('view', $order);

        return response()->json([
            'data' => $order->load(['customer', 'items.service', 'photos', 'receivable']),
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    // POST /orders
    public function store(OrderStoreRequest $request)
    {
        $this->authorize('create', Order::class);
        $payload = $request->validated();

        // Admin Cabang/Kasir: fallback branch ke cabang aktor
        if (empty($payload['branch_id'])) {
            $payload['branch_id'] = $request->user()->branch_id;
        }

        $order = $this->svc->createDraft($payload, $request->user())
            ->load(['customer', 'items.service']); // optional: konsisten dengan show()

        return response()->json([
            'data' => $order,
            'meta' => [],
            'message' => 'Created',
            'errors' => null,
        ], 201);
    }

    // PUT /orders/{order}
    public function update(OrderUpdateRequest $request, Order $order)
    {
        $this->authorize('update', $order);

        $order = $this->svc->update($order, $request->validated(), $request->user());

        return response()->json([
            'data' => $order,
            'meta' => [],
            'message' => 'Updated',
            'errors' => null,
        ]);
    }

    public function receipt(Request $request, Order $order)
    {
        if (!$request->hasValidSignature()) {
            $this->authorize('view', $order);
        }

        $order->load([
            'items.service:id,name',
            'branch:id,name,address',
        ]);

        $loy = null;
        if ($order->customer_id) {
            /** @var \App\Services\LoyaltyService $loySvc */
            $loySvc = app(LoyaltyService::class);
            $acc    = $loySvc->getOrCreateAccount(
                (string) $order->customer_id,
                (string) $order->branch_id
            );
            $cycle   = LoyaltyService::CYCLE;
            $stamps  = (int) $acc->stamps;
            $next    = ($stamps % $cycle) + 1;
            $target25  = 5;
            $target100 = 10;
            // sisa transaksi (0 artinya reward terjadi pada transaksi ini)
            $sisa25   = ($target25  - $next + $cycle) % $cycle;
            $sisa100  = ($target100 - $next + $cycle) % $cycle;
            $loy = [
                'stamps'  => $stamps,
                'cycle'   => $cycle,
                'next'    => $next,
                'sisa25'  => $sisa25,
                'sisa100' => $sisa100,
            ];
        }

        $html = view('orders.receipt', [
            'order'     => $order,
            'branch'    => $order->getRelation('branch'),
            'printedAt' => now(),
            'loy'       => $loy,
        ])->render();

        return new Response($html, 200, ['Content-Type' => 'text/html; charset=UTF-8']);
    }

    // POST /orders/{order}/share-link
    public function shareLink(Request $request, Order $order): JsonResponse
    {
        // Staff yang berhak melihat order juga berhak membuat link struk
        $this->authorize('view', $order);

        // Buat signed URL ke route publik: /r/receipt/{order}
        $shareUrl = URL::temporarySignedRoute(
            'public.receipts.show',
            now()->addMinutes(120),
            ['order' => (string) $order->getKey()]
        );

        return response()->json([
            'data' => [
                'share_url' => $shareUrl,
                'expires_in_minutes' => 120,
            ],
            'meta' => (object)[],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    // POST /orders/{order}/status
    public function transitionStatus(OrderStatusRequest $request, Order $order)
    {
        $this->authorize('transitionStatus', $order);
        $order = $this->svc->transition($order, $request->validated()['next'], $request->user());

        return response()->json([
            'data' => [
                'id' => (string) $order->getKey(),
                'status' => (string) $order->getAttribute('status'),
            ],
            'meta' => [],
            'message' => 'Status updated',
            'errors' => null,
        ]);
    }
}

```
</details>

### app/Http/Controllers/Api/OrderPaymentsController.php

- SHA: `3adf2247688f`  
- Ukuran: 1 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `OrderPaymentsController` extends `Controller`**

Metode Publik:
- **__construct**(private PaymentService $svc)
- **store**(PaymentRequest $request, Order $order) : *JsonResponse*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\PaymentRequest;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;

class OrderPaymentsController extends Controller
{
    public function __construct(private PaymentService $svc)
    {
        $this->middleware('auth:sanctum');
    }

    public function store(PaymentRequest $request, Order $order): JsonResponse
    {
        $this->authorize('update', $order);

        $payload = $request->validated();
        $res = $this->svc->apply(
            $order,
            $payload['method'],
            (float) $payload['amount'],
            $payload['paid_at'] ?? null,
            $payload['note'] ?? null
        );

        return response()->json([
            'data' => [
                'order' => $res['order'],
                'payment' => $res['payment'],
            ],
            'meta' => ['idempotent' => $res['idempotent']],
            'message' => 'Payment applied',
            'errors' => null,
        ], 201);
    }
}

```
</details>

### app/Http/Controllers/Api/OrderPhotosController.php

- SHA: `6f0a722896b0`  
- Ukuran: 2 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `OrderPhotosController` extends `Controller`**

Metode Publik:
- **__construct**(private OrderService $svc)
- **store**(OrderPhotosRequest $request, Order $order) : *JsonResponse*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrderPhotosRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class OrderPhotosController extends Controller
{
    public function __construct(private OrderService $svc)
    {
        $this->middleware('auth:sanctum');
    }

    public function store(OrderPhotosRequest $request, Order $order): JsonResponse
    {
        $this->authorize('update', $order);

        $before = $request->file('photos.before', []);
        $after = $request->file('photos.after', []);

        if ((count($before) + count($after)) === 0) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'No files uploaded',
                'errors' => ['photos' => ['empty']],
            ], 422);
        }

        $dir = "uploads/orders/{$order->id}";
        $rows = [];

        foreach ($before as $f) {
            $p = $f->store($dir . '/before', 'public');
            $rows[] = ['kind' => 'before', 'path' => "storage/{$p}"];
        }
        foreach ($after as $f) {
            $p = $f->store($dir . '/after', 'public');
            $rows[] = ['kind' => 'after', 'path' => "storage/{$p}"];
        }

        $order = $this->svc->attachPhotos($order, $rows);

        return response()->json([
            'data' => $order,
            'meta' => [],
            'message' => 'Photos uploaded',
            'errors' => null,
        ]);
    }
}

```
</details>

### app/Http/Controllers/Api/ReceivableController.php

- SHA: `faf1c359d191`  
- Ukuran: 3 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `ReceivableController` extends `Controller`**

Metode Publik:
- **index**(Request $req)
- **settle**(ReceivableSettleRequest $req, string $id, ReceivableService $svc)
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use App\Models\Receivable;
use App\Models\Order;
use App\Http\Requests\Receivables\ReceivableSettleRequest;
use App\Services\ReceivableService;

class ReceivableController extends Controller
{
    public function index(Request $req)
    {
        $this->authorize('viewAny', Receivable::class);

        $user = $req->user();
        $status = strtoupper((string) $req->query('status', ''));

        $q = Receivable::query()
            ->with(['order' => function ($q) {
                $q->select(['id', 'branch_id', 'customer_id', 'invoice_no', 'grand_total', 'paid_amount', 'due_amount', 'status', 'payment_status', 'created_at']);
            }, 'order.customer:id,name,whatsapp'])
            ->join('orders', 'orders.id', '=', 'receivables.order_id')
            ->select('receivables.*');

        // Filter per cabang (kecuali Superadmin)
        if ($user->branch_id) {
            $q->where('orders.branch_id', $user->branch_id);
        }

        // Filter status
        if (in_array($status, ['OPEN', 'PARTIAL', 'SETTLED', 'CANCELLED'], true)) {
            $q->where('receivables.status', $status);
        } elseif ($status === 'OVERDUE') {
            $today = Carbon::today();
            $q->whereNotNull('receivables.due_date')
                ->where('receivables.due_date', '<', $today->toDateString())
                ->where('receivables.remaining_amount', '>', 0);
        }

        $q->orderByRaw('CASE WHEN receivables.due_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('receivables.due_date', 'asc')
            ->orderBy('receivables.created_at', 'desc');

        $data = $q->paginate((int) $req->query('per_page', 15));

        return response()->json([
            'data' => $data,
            'meta' => (object) [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function settle(ReceivableSettleRequest $req, string $id, ReceivableService $svc)
    {
        $rcv = Receivable::query()->with('order')->findOrFail($id);
        $this->authorize('settle', $rcv);

        $method = $req->input('method');     // CASH|QRIS|TRANSFER
        $amount = (float) $req->input('amount');
        $paidAt = $req->date('paid_at');
        $note   = $req->input('note');

        $result = $svc->settle($rcv->order, $method, $amount, $paidAt, $note);

        $ord = $result['order'];
        $orderId = is_array($ord)
            ? (string) data_get($ord, 'order.id', data_get($ord, 'id'))
            : (string) data_get($ord, 'id');
        // Link internal (butuh auth) untuk staff
        $receiptUrl = $orderId ? url("/api/v1/orders/{$orderId}/receipt") : null;
        // Link publik bertanda tangan untuk dibagikan ke pelanggan (berlaku 120 menit)
        $shareUrl = $orderId
            ? URL::temporarySignedRoute(
                'public.receipts.show',
                now()->addMinutes(120),
                ['order' => $orderId]
            )
            : null;

        return response()->json([
            'data' => [
                'order' => $result['order'],
                'receivable' => $result['receivable'],
                'order_id' => $orderId,
                'receipt_url' => $receiptUrl,
                'share_url' => $shareUrl,
            ],
            'meta' => (object) [],
            'message' => 'Pelunasan berhasil.',
            'errors' => null,
        ]);
    }
}

```
</details>

### app/Http/Controllers/Api/ReportController.php

- SHA: `53432391d1e2`  
- Ukuran: 4 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `ReportController` extends `Controller`**

Metode Publik:
- **__construct**(private ReportService $svc)
- **preview**(string $kind, ReportFilterRequest $req) : *JsonResponse* — GET /reports/{kind} – preview JSON (paginated)
- **export**(string $kind, ReportFilterRequest $req) — GET /reports/{kind} – preview JSON (paginated)
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ReportFilterRequest;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function __construct(private ReportService $svc) {}

    /** GET /reports/{kind} – preview JSON (paginated) */
    public function preview(string $kind, ReportFilterRequest $req): JsonResponse
    {
        [$q, $columns] = $this->resolveQuery($kind, $req);

        $perPage = (int) max(1, min(100, (int) $req->input('per_page', 20)));
        $page = $this->svc->paginate($q, $perPage);

        return response()->json([
            'data' => $page->items(),
            'meta' => [
                'current_page' => $page->currentPage(),
                'per_page'     => $page->perPage(),
                'total'        => $page->total(),
                'last_page'    => $page->lastPage(),
                'kind'         => $kind,
                'columns'      => $columns,
            ],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    /** GET /reports/{kind}/export?format=csv|xlsx – file download (CSV baseline) */
    public function export(string $kind, ReportFilterRequest $req)
    {
        [$q, $columns] = $this->resolveQuery($kind, $req);

        // format saat ini: CSV (Excel-ready). XLSX bisa ditambahkan kemudian.
        $format = $req->input('format', 'csv');
        $from   = $req->input('from');
        $to     = $req->input('to');
        $branch = $req->branchId() ? 'branch' : 'all';
        $filename = sprintf('%s_%s-%s_%s.%s', $kind, str_replace('-', '', $from), str_replace('-', '', $to), $branch, $format);
        $delimiterKey = $req->input('delimiter', 'semicolon');
        $delimiter = match ($delimiterKey) {
            'comma' => ',',
            'tab' => "\t",
            default => ';', // 'semicolon'
        };

        if ($format === 'csv') {
            return $this->svc->streamCsv($q, $columns, $filename, $delimiter);
        }

        // Fallback, untuk saat ini tetap CSV bila XLSX belum diaktifkan
        return $this->svc->streamCsv($q, $columns, Str::replaceLast('.xlsx', '.csv', $filename), $delimiter);
    }

    /** Resolver: mapping jenis report -> query builder + urutan kolom */
    private function resolveQuery(string $kind, ReportFilterRequest $req): array
    {
        $from = $req->fromDate();
        $to   = $req->toDate();
        $bid  = $req->branchId();

        switch ($kind) {
            case 'sales':
            case 'payments':
                $q = $this->svc->buildSalesQuery($from, $to, $bid, $req->input('method'));
                // alias kolom SELECT sudah snake_case agar pas ke CSV
                $columns = ['branch', 'paid_at', 'invoice', 'method', 'amount', 'cashier'];
                return [$q, $columns];

            case 'orders':
                $q = $this->svc->buildOrdersQuery($from, $to, $bid, $req->input('status'));
                $columns = ['branch', 'created_at', 'number', 'invoice_no', 'customer', 'status', 'grand_total', 'paid_amount', 'payment_status'];
                return [$q, $columns];

            case 'receivables':
                $q = $this->svc->buildReceivablesQuery($from, $to, $bid, $req->input('status'));
                $columns = ['branch', 'date', 'invoice', 'remaining_amount', 'status'];
                return [$q, $columns];

            case 'expenses':
                $q = $this->svc->buildExpensesQuery($from, $to, $bid);
                $columns = ['branch', 'created_at', 'category', 'amount', 'note'];
                return [$q, $columns];

            case 'services':
                $q = $this->svc->buildServiceItemsQuery($from, $to, $bid);
                $columns = ['branch', 'service', 'unit', 'qty', 'amount'];
                return [$q, $columns];
        }

        abort(404, 'Unknown report kind.');
    }
}

```
</details>

### app/Http/Controllers/Api/ServiceController.php

- SHA: `def3779fd6a0`  
- Ukuran: 3 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `ServiceController` extends `Controller`**

Metode Publik:
- **index**(Request $request)
- **show**(Service $service)
- **store**(ServiceStoreRequest $request)
- **update**(ServiceUpdateRequest $request, Service $service)
- **destroy**(Service $service)
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceStoreRequest;
use App\Http\Requests\ServiceUpdateRequest;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Service::class);

        $q = Service::query()->with('category')->orderBy('name');

        if ($s = $request->query('q')) {
            $q->where(function ($w) use ($s) {
                $w->where('name', 'like', "%{$s}%")
                    ->orWhere('unit', 'like', "%{$s}%");
            });
        }
        if ($cat = $request->query('category_id')) {
            $q->where('category_id', $cat);
        }
        if (($act = $request->query('is_active')) !== null) {
            $q->where('is_active', (bool) $act);
        }

        $items = $q->paginate((int) $request->query('per_page', 10));

        return response()->json([
            'data' => $items->items(),
            'meta' => [
                'current_page' => $items->currentPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
                'last_page' => $items->lastPage(),
            ],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function show(Service $service)
    {
        $this->authorize('view', $service);

        return response()->json([
            'data' => $service->load('category', 'prices'),
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function store(ServiceStoreRequest $request)
    {
        $payload = $request->validated();
        $this->authorize('create', Service::class);

        $service = new Service($payload);
        $service->id = (string) Str::uuid();
        $service->save();

        // TODO: audit('SERVICE_CREATE', $service)

        return response()->json([
            'data' => $service,
            'meta' => [],
            'message' => 'Created',
            'errors' => null,
        ], 201);
    }

    public function update(ServiceUpdateRequest $request, Service $service)
    {
        $payload = $request->validated();
        $this->authorize('update', $service);

        $service->fill($payload)->save();

        // TODO: audit('SERVICE_UPDATE', $service)

        return response()->json([
            'data' => $service,
            'meta' => [],
            'message' => 'Updated',
            'errors' => null,
        ]);
    }

    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);
        $service->delete();

        // TODO: audit('SERVICE_DELETE', ['id' => $service->id])

        return response()->json([
            'data' => null,
            'meta' => [],
            'message' => 'Deleted',
            'errors' => null,
        ]);
    }
}

```
</details>

### app/Http/Controllers/Api/ServicePriceController.php

- SHA: `317726c91494`  
- Ukuran: 3 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `ServicePriceController` extends `Controller`**

Metode Publik:
- **set**(ServicePriceSetRequest $request) — Set atau update harga override per cabang (idempotent).
- **listByService**(Request $request) — Set atau update harga override per cabang (idempotent).
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServicePriceSetRequest;
use App\Models\Branch;
use App\Models\Service;
use App\Models\ServicePrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServicePriceController extends Controller
{
    /**
     * Set atau update harga override per cabang (idempotent).
     * Body: { service_id, branch_id, price }
     */
    public function set(ServicePriceSetRequest $request)
    {
        $payload = $request->validated();

        // Otorisasi: hanya Superadmin/Admin Cabang; Admin Cabang dibatasi ke cabangnya
        $service = Service::query()->findOrFail($payload['service_id']);
        $this->authorize('update', $service);

        $branch = Branch::query()->findOrFail($payload['branch_id']);
        if ($request->user()->hasRole('Admin Cabang')) {
            if ((string) $request->user()->branch_id !== (string) $branch->id) {
                return response()->json([
                    'data' => null,
                    'meta' => [],
                    'message' => 'Forbidden',
                    'errors' => ['branch_id' => ['restricted_to_own_branch']],
                ], 403);
            }
        }

        $row = DB::transaction(function () use ($payload) {
            /** @var \App\Models\ServicePrice $sp */
            $sp = ServicePrice::query()
                ->where('service_id', $payload['service_id'])
                ->where('branch_id', $payload['branch_id'])
                ->lockForUpdate()
                ->first();

            if (!$sp) {
                $sp = new ServicePrice([
                    'id' => (string) Str::uuid(),
                    'service_id' => $payload['service_id'],
                    'branch_id' => $payload['branch_id'],
                    'price' => $payload['price'],
                ]);
                $sp->save();
            } else {
                $sp->price = $payload['price'];
                $sp->save();
            }

            // TODO: audit('SERVICE_PRICE_SET', $sp)

            return $sp->fresh(['service', 'branch']);
        });

        return response()->json([
            'data' => $row,
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    /**
     * Opsional: lihat daftar harga sebuah service di seluruh cabang.
     * Query: ?service_id=... (wajib)
     */
    public function listByService(Request $request)
    {
        $serviceId = (string) $request->query('service_id');
        if (!$serviceId) {
            return response()->json([
                'data' => null,
                'meta' => [],
                'message' => 'service_id is required',
                'errors' => ['service_id' => ['required']],
            ], 422);
        }

        $service = Service::query()->findOrFail($serviceId);
        $this->authorize('view', $service);

        $items = ServicePrice::query()
            ->where('service_id', $serviceId)
            ->with('branch')
            ->orderBy('branch_id')
            ->get();

        return response()->json([
            'data' => $items,
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }
}

```
</details>

### app/Http/Controllers/Api/UserController.php

- SHA: `89c4aa70fdf4`  
- Ukuran: 6 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `UserController` extends `Controller`**

Metode Publik:
- **__construct**(private UserService $svc)
- **index**(Request $request) : *JsonResponse*
- **show**(Request $request, User $user) : *JsonResponse* — @var \Illuminate\Pagination\LengthAwarePaginator $page
- **store**(UserStoreRequest $request) : *JsonResponse* — @var \Illuminate\Pagination\LengthAwarePaginator $page
- **update**(UserUpdateRequest $request, User $user) : *JsonResponse* — @var \Illuminate\Pagination\LengthAwarePaginator $page
- **destroy**(Request $request, User $user) : *JsonResponse* — @var \Illuminate\Pagination\LengthAwarePaginator $page
- **resetPassword**(Request $request, User $user) : *JsonResponse* — @var \Illuminate\Pagination\LengthAwarePaginator $page
- **setActive**(Request $request, User $user) : *JsonResponse* — @var \Illuminate\Pagination\LengthAwarePaginator $page
- **setRoles**(Request $request, User $user) : *JsonResponse* — @var \Illuminate\Pagination\LengthAwarePaginator $page
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    public function __construct(private UserService $svc)
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $filters = [
            'search' => (string) $request->query('q', ''),
            'branch_id' => $this->branchScopeFor($request),
            'role'      => (string) $request->query('role', ''),
        ];
        $perPage = (int) $request->integer('per_page', 15);

        /** @var \Illuminate\Pagination\LengthAwarePaginator $page */
        $page = $this->svc->paginate($filters, $perPage);

        // Normalisasi: roles -> string[]
        $items = collect($page->items())->map(function (User $u) {
            return [
                'id'        => $u->id,
                'name'      => $u->name,
                'email'     => $u->email,
                'username'  => $u->username,
                'branch_id' => $u->branch_id,
                'is_active' => (bool) $u->is_active,
                'roles'     => $u->getRoleNames()->values(), // ← ["Kurir", ...]
            ];
        })->values();

        return response()->json([
            'data' => $items,
            'meta' => [
                'current_page' => $page->currentPage(),
                'per_page' => $page->perPage(),
                'total' => $page->total(),
                'last_page' => $page->lastPage(),
                // opsional tambahkan:
                // 'from' => $page->firstItem(),
                // 'to'   => $page->lastItem(),
            ],
            'message' => 'OK',
            'errors' => [],
        ]);
    }

    public function show(Request $request, User $user): JsonResponse
    {
        $this->authorize('view', $user);

        // Kembalikan bentuk yang sama (roles: string[])
        $user->load('roles:id,name');
        $data = [
            'id'        => $user->id,
            'name'      => $user->name,
            'email'     => $user->email,
            'username'  => $user->username,
            'branch_id' => $user->branch_id,
            'is_active' => (bool) $user->is_active,
            'roles'     => $user->getRoleNames()->values(),
        ];

        return response()->json([
            'data' => $data,
            'meta' => null,
            'message' => 'OK',
            'errors' => [],
        ]);
    }

    public function store(UserStoreRequest $request): JsonResponse
    {
        $payload = $request->validated();

        // Admin Cabang: paksa branch_id sesuai miliknya bila tidak diisi
        if ($request->user()->hasRole('Admin Cabang') && empty($payload['branch_id'])) {
            $payload['branch_id'] = $request->user()->branch_id;
        }

        $user = $this->svc->create($payload);

        return response()->json([
            'data' => $user,
            'meta' => null,
            'message' => 'Created',
            'errors' => null,
        ], 201);
    }

    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $payload = $request->validated();

        if ($request->user()->hasRole('Admin Cabang') && array_key_exists('branch_id', $payload)) {
            $payload['branch_id'] = $request->user()->branch_id;
        }

        $updated = $this->svc->update($user, $payload);

        return response()->json([
            'data' => $updated,
            'meta' => null,
            'message' => 'Updated',
            'errors' => [],
        ]);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $this->svc->delete($user);

        return response()->json([
            'data' => null,
            'meta' => null,
            'message' => 'Deleted',
            'errors' => [],
        ]);
    }

    private function branchScopeFor(Request $request): ?string
    {
        $me = $request->user();

        if ($me->hasRole('Superadmin')) {
            // bebas: boleh query('branch_id') atau null (semua)
            return $request->query('branch_id');
        }

        // Admin Cabang & Kasir dibatasi ke cabangnya
        return $me->branch_id ? (string) $me->branch_id : null;
    }

    public function resetPassword(Request $request, User $user): JsonResponse
    {
        $this->authorize('resetPassword', $user);

        $data = $request->validate([
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()],
        ]);

        $this->svc->resetPassword($user, $data['password']);

        return response()->json([
            'data' => null,
            'meta' => null,
            'message' => 'Password reset successful',
            'errors' => [],
        ]);
    }

    public function setActive(Request $request, User $user): JsonResponse
    {
        $this->authorize('setActive', $user);

        $data = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $updated = $this->svc->setActive($user, (bool) $data['is_active']);

        return response()->json([
            'data' => ['id' => $updated->id, 'is_active' => $updated->is_active],
            'meta' => null,
            'message' => 'User activity toggled',
            'errors' => [],
        ]);
    }

    public function setRoles(Request $request, User $user): JsonResponse
    {
        $this->authorize('setRoles', $user);

        $data = $request->validate([
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $updated = $this->svc->setRoles($user, $data['roles']);

        return response()->json([
            'data' => $updated->load('roles:id,name'),
            'meta' => null,
            'message' => 'Roles updated',
            'errors' => [],
        ]);
    }
}

```
</details>

### app/Http/Controllers/Api/VoucherController.php

- SHA: `d1f7ed4e4508`  
- Ukuran: 5 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `VoucherController` extends `Controller`**

Metode Publik:
- **index**(Request $req)
- **store**(VoucherStoreRequest $req)
- **show**(Voucher $voucher)
- **update**(VoucherUpdateRequest $req, Voucher $voucher)
- **destroy**(Request $req, Voucher $voucher)
- **applyToOrder**(OrderApplyVoucherRequest $req, Order $order, VoucherService $svc)
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vouchers\VoucherStoreRequest;
use App\Http\Requests\Vouchers\VoucherUpdateRequest;
use App\Http\Requests\Orders\OrderApplyVoucherRequest;
use App\Models\Order;
use App\Models\Voucher;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    public function index(Request $req)
    {
        $this->authorize('viewAny', Voucher::class);

        $q = Voucher::query();

        // Filter opsional: code, active
        if ($code = $req->query('code')) {
            $q->where('code', 'like', '%' . strtoupper(trim($code)) . '%');
        }
        if ($req->filled('active')) {
            $q->where('active', (bool) $req->query('active'));
        }

        // Admin cabang hanya melihat miliknya + global
        $u = $req->user();
        if ($u && method_exists($u, 'hasRole') && $u->hasRole('admin')) {
            $q->where(function ($w) use ($u) {
                $w->whereNull('branch_id')->orWhere('branch_id', $u->branch_id);
            });
        }

        $data = $q->orderByDesc('created_at')->paginate((int) ($req->query('per_page', 20)));

        return response()->json([
            'data' => $data->items(),
            'meta' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function store(VoucherStoreRequest $req)
    {
        $this->authorize('create', Voucher::class);

        $payload = $req->validated();

        $voucher = Voucher::query()->create([
            'id' => (string) Str::uuid(),
            'branch_id' => $payload['branch_id'] ?? null,
            'code' => $payload['code'],
            'type' => $payload['type'],
            'value' => $payload['value'],
            'start_at' => $payload['start_at'] ?? null,
            'end_at' => $payload['end_at'] ?? null,
            'min_total' => $payload['min_total'] ?? 0,
            'usage_limit' => $payload['usage_limit'] ?? null,
            'active' => $payload['active'] ?? true,
        ]);

        // TODO: audit('VOUCHER_CREATE', ['voucher_id' => $voucher->id, 'actor' => $req->user()?->id]);

        return response()->json([
            'data' => $voucher,
            'meta' => (object) [],
            'message' => 'Created',
            'errors' => null,
        ], 201);
    }

    public function show(Voucher $voucher)
    {
        $this->authorize('view', $voucher);

        return response()->json([
            'data' => $voucher,
            'meta' => (object) [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function update(VoucherUpdateRequest $req, Voucher $voucher)
    {
        $this->authorize('update', $voucher);

        $payload = $req->validated();
        $voucher->fill($payload)->save();

        // TODO: audit('VOUCHER_UPDATE', ['voucher_id' => $voucher->id, 'actor' => $req->user()?->id]);

        return response()->json([
            'data' => $voucher,
            'meta' => (object) [],
            'message' => 'Updated',
            'errors' => null,
        ]);
    }

    public function destroy(Request $req, Voucher $voucher)
    {
        $this->authorize('delete', $voucher);

        $voucher->delete();

        // TODO: audit('VOUCHER_DELETE', ['voucher_id' => $voucher->id, 'actor' => $req->user()?->id]);

        return response()->json([
            'data' => (object) [],
            'meta' => (object) [],
            'message' => 'Deleted',
            'errors' => null,
        ]);
    }

    public function applyToOrder(OrderApplyVoucherRequest $req, Order $order, VoucherService $svc)
    {
        // Otorisasi update order mengikuti pola existing
        $this->authorize('update', $order);

        $code = strtoupper(trim($req->input('code')));
        $voucher = Voucher::query()->where('code', $code)->first();

        if (!$voucher) {
            return response()->json([
                'data' => (object) [],
                'meta' => (object) [],
                'message' => 'Voucher tidak ditemukan.',
                'errors' => ['code' => ['Voucher tidak ditemukan.']],
            ], 422);
        }

        $order = $svc->apply($order, $voucher, $req->user());

        return response()->json([
            'data' => $order,
            'meta' => (object) [],
            'message' => 'Voucher diterapkan.',
            'errors' => null,
        ]);
    }
}

```
</details>

### app/Http/Controllers/Api/WashNoteController.php

- SHA: `572a86633ae1`  
- Ukuran: 10 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `WashNoteController` extends `Controller`**

Metode Publik:
- **index**(Request $request)
- **show**(WashNote $wash_note)
- **store**(WashNoteStoreRequest $request)
- **update**(WashNoteUpdateRequest $request, WashNote $wash_note)
- **destroy**(Request $request, WashNote $wash_note)
- **candidates**(Request $request)
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WashNoteStoreRequest;
use App\Http\Requests\WashNoteUpdateRequest;
use App\Models\WashNote;
use App\Models\WashNoteItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WashNoteController extends Controller
{
    // GET /wash-notes
    public function index(Request $request)
    {
        $this->authorize('viewAny', WashNote::class);

        $me = $request->user();
        $q = WashNote::query()
            ->with(['user', 'branch'])
            ->orderByDesc('note_date')
            ->orderByDesc('created_at');

        // Scope role
        if ($me->hasRole('Petugas Cuci')) {
            $q->where('user_id', $me->id);
        } elseif ($me->hasRole('Admin Cabang') && $me->branch_id) {
            $q->where('branch_id', $me->branch_id);
        }

        $from = $request->query('date_from');
        $to   = $request->query('date_to');

        if ($from && $to) {
            $q->whereBetween('note_date', [$from, $to]);
        } elseif ($from) {
            $q->whereDate('note_date', '>=', $from);
        } elseif ($to) {
            $q->whereDate('note_date', '<=', $to);
        }

        $per  = (int) max(1, min(100, (int) $request->query('per_page', 20)));
        $rows = $q->paginate($per);

        $recap = [
            'orders_count' => (int) $rows->sum('orders_count'),
            'total_qty'    => (float) $rows->sum('total_qty'),
        ];

        return response()->json([
            'data' => $rows->items(),
            'meta' => [
                'page'  => $rows->currentPage(),
                'pages' => $rows->lastPage(),
                'total' => $rows->total(),
                'recap' => $recap,
            ],
            'message' => null,
            'errors'  => null,
        ]);
    }

    // GET /wash-notes/{id}
    public function show(WashNote $wash_note)
    {
        $this->authorize('view', $wash_note);
        $wash_note->load(['items.order.customer', 'user', 'branch']);

        return response()->json([
            'data' => $wash_note,
            'meta' => null,
            'message' => null,
            'errors' => null,
        ]);
    }

    // POST /wash-notes
    public function store(WashNoteStoreRequest $request)
    {
        $me = $request->user();
        $data = $request->validated();

        $existing = WashNote::query()
            ->where('user_id', $me->id)
            ->whereDate('note_date', $data['note_date'])
            ->first();

        if ($existing) {
            return response()->json([
                'data' => null,
                'meta' => ['existing_id' => (string) $existing->id],
                'message' => 'Catatan untuk tanggal tersebut sudah ada.',
                'errors' => ['duplicate' => ['note_date already exists for this user']],
            ], 422);
        }

        $note = DB::transaction(function () use ($me, $data) {
            $note = WashNote::create([
                'id' => Str::uuid()->toString(),
                'user_id' => $me->id,
                'branch_id' => $me->branch_id, // boleh null jika petugas di pusat
                'note_date' => $data['note_date'],
                'orders_count' => 0,
                'total_qty' => 0,
            ]);

            $ordersCount = 0;
            $totalQty = 0;

            foreach ($data['items'] as $it) {
                WashNoteItem::create([
                    'id' => Str::uuid()->toString(),
                    'wash_note_id' => $note->id,
                    'order_id' => $it['order_id'],
                    'qty' => number_format((float) ($it['qty'] ?? 0), 2, '.', ''),
                    'process_status' => $it['process_status'] ?? null,
                    'started_at' => $it['started_at'] ?? null,
                    'finished_at' => $it['finished_at'] ?? null,
                    'note' => $it['note'] ?? null,
                ]);
                $ordersCount++;
                $totalQty += (float)($it['qty'] ?? 0);
            }

            $note->update([
                'orders_count' => $ordersCount,
                'total_qty' => number_format((float) $totalQty, 2, '.', ''),
            ]);

            return $note->fresh(['items.order.customer']);
        });

        return response()->json([
            'data' => $note,
            'meta' => null,
            'message' => 'Catatan cuci dibuat.',
            'errors' => null,
        ], 201);
    }

    // PATCH /wash-notes/{wash_note}
    public function update(WashNoteUpdateRequest $request, WashNote $wash_note)
    {
        $data = $request->validated();

        $note = DB::transaction(function () use ($wash_note, $data) {
            // Optional: update header tanggal (tetap pastikan unik per user+date)
            if (isset($data['note_date']) && $data['note_date']) {
                $dup = WashNote::where('user_id', $wash_note->user_id)
                    ->whereDate('note_date', $data['note_date'])
                    ->where('id', '!=', $wash_note->id)
                    ->exists();
                if ($dup) {
                    abort(response()->json([
                        'data' => null,
                        'meta' => null,
                        'message' => 'Tanggal sudah dipakai.',
                        'errors' => ['duplicate' => ['note_date duplicate']],
                    ], 422));
                }
                $wash_note->note_date = $data['note_date'];
            }

            if (isset($data['items'])) {
                // sinkron sederhana: hapus semua lalu tulis ulang (aman & jelas)
                $wash_note->items()->delete();

                $ordersCount = 0;
                $totalQty = 0;
                foreach ($data['items'] as $it) {
                    $wash_note->items()->create([
                        'id' => Str::uuid()->toString(),
                        'order_id' => $it['order_id'],
                        'qty' => number_format((float) ($it['qty'] ?? 0), 2, '.', ''),
                        'process_status' => $it['process_status'] ?? null,
                        'started_at' => $it['started_at'] ?? null,
                        'finished_at' => $it['finished_at'] ?? null,
                        'note' => $it['note'] ?? null,
                    ]);
                    $ordersCount++;
                    $totalQty += (float)($it['qty'] ?? 0);
                }
                $wash_note->orders_count = $ordersCount;
                $wash_note->total_qty = number_format((float) $totalQty, 2, '.', '');
            }

            $wash_note->save();
            return $wash_note->fresh(['items.order.customer']);
        });

        return response()->json([
            'data' => $note,
            'meta' => null,
            'message' => 'Catatan cuci diperbarui.',
            'errors' => null,
        ]);
    }

    // DELETE /wash-notes/{wash_note}
    public function destroy(Request $request, WashNote $wash_note)
    {
        $this->authorize('delete', $wash_note);
        $wash_note->delete();

        return response()->json([
            'data' => null,
            'meta' => null,
            'message' => 'Catatan cuci dihapus.',
            'errors' => null,
        ]);
    }

    // GET /wash-notes/candidates?query=...&date=YYYY-MM-DD
    // pencarian order untuk form tambah
    public function candidates(Request $request)
    {
        $this->authorize('viewAny', WashNote::class);

        $me     = $request->user();
        $query  = trim((string) $request->query('query', ''));
        $from   = $request->query('date_from');
        $to     = $request->query('date_to');
        // ⬅️ baru: tanggal catatan aktif dari klien (fallback ke ?date=)
        $onDate = $request->query('on_date') ?: $request->query('date');

        $q = Order::query()
            ->with(['customer'])
            ->withSum('items as default_qty', 'qty')
            ->orderByDesc('created_at');

        // Petugas Cuci boleh lintas cabang; selain itu batasi cabang
        if (!$me->hasRole('Petugas Cuci') && $me->branch_id) {
            $q->where('branch_id', $me->branch_id);
        }

        if ($query !== '') {
            // Portabel: pgsql → ILIKE, selainnya pakai LOWER(...) LIKE untuk case-insensitive
            if (DB::getDriverName() === 'pgsql') {
                $q->where(function ($w) use ($query) {
                    $w->where('number', 'ilike', "%{$query}%")
                        ->orWhere('invoice_no', 'ilike', "%{$query}%")
                        ->orWhereHas('customer', fn($c) => $c->where('name', 'ilike', "%{$query}%"));
                });
            } else {
                $needle = '%' . mb_strtolower($query) . '%';
                $q->where(function ($w) use ($needle) {
                    $w->whereRaw('LOWER(number) LIKE ?', [$needle])
                        ->orWhereRaw('LOWER(COALESCE(invoice_no, "")) LIKE ?', [$needle])
                        ->orWhereHas('customer', fn($c) => $c->whereRaw('LOWER(name) LIKE ?', [$needle]));
                });
            }
        }

        if ($from && $to) {
            $q->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
        } elseif ($from) {
            $q->where('created_at', '>=', $from . ' 00:00:00');
        } elseif ($to) {
            $q->where('created_at', '<=', $to . ' 23:59:59');
        }


        if (empty($onDate)) {
            $onDate = now()->toDateString();
        }
        $q->whereNotExists(function ($sub) use ($onDate, $me) {
            $sub->from('wash_note_items as wni')
                ->join('wash_notes as wn', 'wn.id', '=', 'wni.wash_note_id')
                ->whereColumn('wni.order_id', 'orders.id')
                ->whereDate('wn.note_date', $onDate);
        });

        $rows = $q->limit(20)->get();

        $rows->transform(function ($o) {
            $o->default_qty = (float) ($o->default_qty ?? 0);
            return $o;
        });

        return response()->json([
            'data'    => $rows,
            'meta'    => [
                'count'   => $rows->count(),
                'on_date' => $onDate,
            ],
            'message' => null,
            'errors'  => null,
        ]);
    }
}

```
</details>



## Models (app/Models)

### app/Models/Branch.php

- SHA: `2872c07ec6c6`  
- Ukuran: 706 B  
- Namespace: `App\Models`

**Class `Branch` extends `Model`**

Metode Publik:
- **invoiceCounters**()
- **scopeLite**($q)
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'branches';

    protected $fillable = [
        'code',
        'name',
        'address',
        'invoice_prefix',
        'reset_policy',
    ];

    public function invoiceCounters()
    {
        return $this->hasMany(InvoiceCounter::class, 'branch_id', 'id');
    }

    public function scopeLite($q)
    {
        return $q->select(['id', 'name', 'address']);
    }
}

```
</details>

### app/Models/Customer.php

- SHA: `2b1004dedcaa`  
- Ukuran: 585 B  
- Namespace: `App\Models`

**Class `Customer` extends `Model`**

Metode Publik:
- **branch**()
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'customers';

    protected $fillable = [
        'branch_id',
        'name',
        'whatsapp',
        'address',
        'notes',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}

```
</details>

### app/Models/Delivery.php

- SHA: `33d3ae90aad7`  
- Ukuran: 979 B  
- Namespace: `App\Models`

**Class `Delivery` extends `Model`**

Metode Publik:
- **order**()
- **courier**()
- **events**()
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Delivery extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'deliveries';

    protected $fillable = [
        'order_id',
        'type',
        'zone_id',
        'fee',
        'assigned_to',
        'auto_assigned',
        'status',
        'handover_photo',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
        'auto_assigned' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function courier()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function events()
    {
        return $this->hasMany(DeliveryEvent::class, 'delivery_id', 'id');
    }
}

```
</details>

### app/Models/DeliveryEvent.php

- SHA: `4618be90f2e4`  
- Ukuran: 535 B  
- Namespace: `App\Models`

**Class `DeliveryEvent` extends `Model`**

Metode Publik:
- **delivery**()
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryEvent extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'delivery_events';

    protected $fillable = ['delivery_id', 'status', 'note'];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class, 'delivery_id', 'id');
    }
}

```
</details>

### app/Models/Expense.php

- SHA: `3fb0d1d88867`  
- Ukuran: 644 B  
- Namespace: `App\Models`

**Class `Expense` extends `Model`**

Metode Publik:
- **branch**()
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Expense extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'expenses';

    protected $fillable = [
        'branch_id',
        'category',
        'amount',
        'note',
        'proof_path',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}

```
</details>

### app/Models/InvoiceCounter.php

- SHA: `214e0c65ae0c`  
- Ukuran: 672 B  
- Namespace: `App\Models`

**Class `InvoiceCounter` extends `Model`**

Metode Publik:
- **branch**()
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceCounter extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'invoice_counters';

    protected $fillable = [
        'branch_id',
        'prefix',
        'seq',
        'reset_policy',
        'last_reset_month',
    ];

    protected $casts = [
        'seq' => 'integer',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}

```
</details>

### app/Models/LoyaltyAccount.php

- SHA: `7a064f5dd330`  
- Ukuran: 323 B  
- Namespace: `App\Models`

**Class `LoyaltyAccount` extends `Model`**
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class LoyaltyAccount extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['customer_id','branch_id','stamps','lifetime'];
}
```
</details>

### app/Models/LoyaltyLog.php

- SHA: `7da979e5c9a6`  
- Ukuran: 342 B  
- Namespace: `App\Models`

**Class `LoyaltyLog` extends `Model`**
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class LoyaltyLog extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['order_id', 'customer_id', 'branch_id', 'action', 'before', 'after'];
}

```
</details>

### app/Models/Order.php

- SHA: `16ad84e636d2`  
- Ukuran: 3 KB  
- Namespace: `App\Models`

**Class `Order` extends `Model`**

> Tipe numerik-keuangan yang disimpan sebagai string (sesuai cast decimal Laravel).

Metode Publik:
- **branch**()
- **customer**()
- **items**()
- **photos**()
- **payments**()
- **vouchers**()
- **receivable**()
- **setMoney**(string $attr, float|int|string|null $value) : *void* — Mutator generik untuk kolom uang — menerima float|int|string.
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Tipe numerik-keuangan yang disimpan sebagai string (sesuai cast decimal Laravel).
 *
 * @property numeric-string $subtotal
 * @property numeric-string $discount
 * @property numeric-string $grand_total
 * @property numeric-string $paid_amount
 * @property numeric-string $due_amount
 */
class Order extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'orders';

    protected $fillable = [
        'branch_id',
        'customer_id',
        'number',
        'invoice_no',
        'status',
        'payment_status',
        'subtotal',
        'discount',
        'dp_amount',
        'grand_total',
        'paid_amount',
        'paid_at',
        'due_amount',
        'notes',
        'created_by',
        'received_at',
        'ready_at',
    ];

    // Tetap pakai decimal:2 (Laravel mengembalikan string)
    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'dp_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'created_by' => 'integer',
        'received_at' => 'datetime',
        'ready_at'    => 'datetime',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
    public function photos()
    {
        return $this->hasMany(OrderPhoto::class, 'order_id', 'id');
    }
    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class, 'order_id', 'id');
    }
    public function vouchers()
    {
        return $this->belongsToMany(\App\Models\Voucher::class, 'order_vouchers', 'order_id', 'voucher_id')
            ->withPivot(['id', 'applied_amount', 'applied_by', 'applied_at'])
            ->withTimestamps();
    }
    public function receivable()
    {
        return $this->hasOne(\App\Models\Receivable::class, 'order_id', 'id');
    }

    /**
     * Mutator generik untuk kolom uang — menerima float|int|string.
     * Agar IDE happy, panggil via $this->setMoney('subtotal', $v) dsb.
     * @param float|int|string|null $value
     */
    public function setMoney(string $attr, float|int|string|null $value): void
    {
        $v = is_numeric($value) ? (float) $value : 0.0;
        $this->attributes[$attr] = number_format($v, 2, '.', '');
    }
}

```
</details>

### app/Models/OrderItem.php

- SHA: `08408e431b38`  
- Ukuran: 1 KB  
- Namespace: `App\Models`

**Class `OrderItem` extends `Model`**

> @property numeric-string $qty

Metode Publik:
- **order**()
- **service**()
- **setMoney**(string $attr, float|int|string|null $value) : *void*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property numeric-string $qty
 * @property numeric-string $price
 * @property numeric-string $total
 */
class OrderItem extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'order_items';

    protected $fillable = ['order_id', 'service_id', 'qty', 'price', 'total', 'note'];

    protected $casts = [
        'qty' => 'decimal:2',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function setMoney(string $attr, float|int|string|null $value): void
    {
        $v = is_numeric($value) ? (float) $value : 0.0;
        $this->attributes[$attr] = number_format($v, 2, '.', '');
    }
}

```
</details>

### app/Models/OrderPhoto.php

- SHA: `eadef3af774e`  
- Ukuran: 515 B  
- Namespace: `App\Models`

**Class `OrderPhoto` extends `Model`**

Metode Publik:
- **order**()
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderPhoto extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'order_photos';

    protected $fillable = ['order_id', 'kind', 'path'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}

```
</details>

### app/Models/OrderVoucher.php

- SHA: `8cfb6e11a09e`  
- Ukuran: 785 B  
- Namespace: `App\Models`

**Class `OrderVoucher` extends `Model`**

Metode Publik:
- **order**()
- **voucher**()
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderVoucher extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'order_vouchers';

    protected $fillable = ['order_id', 'voucher_id', 'applied_amount', 'applied_by', 'applied_at'];

    protected $casts = [
        'applied_amount' => 'decimal:2',
        'applied_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'id');
    }
}

```
</details>

### app/Models/Payment.php

- SHA: `a54c2fa1b283`  
- Ukuran: 677 B  
- Namespace: `App\Models`

**Class `Payment` extends `Model`**

Metode Publik:
- **order**()
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'payments';

    protected $fillable = [
        'order_id',
        'method',
        'amount',
        'paid_at',
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}

```
</details>

### app/Models/Receivable.php

- SHA: `a0289f2797ee`  
- Ukuran: 685 B  
- Namespace: `App\Models`

**Class `Receivable` extends `Model`**

Metode Publik:
- **order**()
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receivable extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'receivables';

    protected $fillable = [
        'order_id',
        'due_date',
        'remaining_amount',
        'status',
    ];

    protected $casts = [
        'remaining_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}

```
</details>

### app/Models/Service.php

- SHA: `664f3e096369`  
- Ukuran: 825 B  
- Namespace: `App\Models`

**Class `Service` extends `Model`**

Metode Publik:
- **category**()
- **prices**()
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'services';

    protected $fillable = [
        'category_id',
        'name',
        'unit',
        'price_default',
        'is_active',
    ];

    protected $casts = [
        'price_default' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id', 'id');
    }

    public function prices()
    {
        return $this->hasMany(ServicePrice::class, 'service_id', 'id');
    }
}

```
</details>

### app/Models/ServiceCategory.php

- SHA: `39f2f2865673`  
- Ukuran: 592 B  
- Namespace: `App\Models`

**Class `ServiceCategory` extends `Model`**

Metode Publik:
- **services**()
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceCategory extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'service_categories';

    protected $fillable = ['name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'category_id', 'id');
    }
}

```
</details>

### app/Models/ServicePrice.php

- SHA: `8f94665098ac`  
- Ukuran: 707 B  
- Namespace: `App\Models`

**Class `ServicePrice` extends `Model`**

Metode Publik:
- **service**()
- **branch**()
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServicePrice extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'service_prices';

    protected $fillable = ['service_id', 'branch_id', 'price'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}

```
</details>

### app/Models/User.php

- SHA: `9bab0ed384b9`  
- Ukuran: 2 KB  
- Namespace: `App\Models`

**Class `User` extends `Authenticatable`**

Metode Publik:
- **branch**() — @use HasFactory<\Database\Factories\UserFactory>
- **getRolesListAttribute**() : *array* — @use HasFactory<\Database\Factories\UserFactory>
- **setUsernameAttribute**(?string $v) : *void* — @use HasFactory<\Database\Factories\UserFactory>
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens, HasRoles;

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
            'is_active' => 'boolean',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class, 'branch_id', 'id');
    }

    public function getRolesListAttribute(): array
    {
        // Hindari N1; panggil onlyNames() dari Spatie
        return $this->roles()->pluck('name')->all();
    }

    public function setUsernameAttribute(?string $v): void
    {
        $this->attributes['username'] = $v !== null ? strtolower(trim($v)) : null;
    }
}

```
</details>

### app/Models/Voucher.php

- SHA: `abdd11b39c51`  
- Ukuran: 995 B  
- Namespace: `App\Models`

**Class `Voucher` extends `Model`**

Metode Publik:
- **orders**()
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'vouchers';

    protected $fillable = [
        'branch_id',
        'code',
        'type',
        'value',
        'start_at',
        'end_at',
        'min_total',
        'usage_limit',
        'active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_total' => 'decimal:2',
        'active' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_vouchers', 'voucher_id', 'order_id')
            ->withPivot(['id', 'applied_amount', 'applied_by', 'applied_at'])
            ->withTimestamps();
    }
}

```
</details>

### app/Models/WashNote.php

- SHA: `84ff66ac2ecb`  
- Ukuran: 1010 B  
- Namespace: `App\Models`

**Class `WashNote` extends `Model`**

Metode Publik:
- **user**() : *BelongsTo*
- **branch**() : *BelongsTo*
- **items**() : *HasMany*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WashNote extends Model
{
    protected $table = 'wash_notes';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'branch_id',
        'note_date',
        'orders_count',
        'total_qty',
    ];

    protected $casts = [
        'note_date'  => 'date:Y-m-d',
        'orders_count' => 'integer',
        'total_qty' => 'decimal:2',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
    public function items(): HasMany
    {
        return $this->hasMany(WashNoteItem::class);
    }
}

```
</details>

### app/Models/WashNoteItem.php

- SHA: `8ed7279d6289`  
- Ukuran: 735 B  
- Namespace: `App\Models`

**Class `WashNoteItem` extends `Model`**

Metode Publik:
- **washNote**() : *BelongsTo*
- **order**() : *BelongsTo*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WashNoteItem extends Model
{
    protected $table = 'wash_note_items';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'wash_note_id',
        'order_id',
        'qty',
        'process_status',
        'started_at',
        'finished_at',
        'note',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
    ];

    public function washNote(): BelongsTo
    {
        return $this->belongsTo(WashNote::class);
    }
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

```
</details>



## Policies (app/Policies)

### app/Policies/BranchPolicy.php

- SHA: `c1dfe7a3aff2`  
- Ukuran: 1 KB  
- Namespace: `App\Policies`

**Class `BranchPolicy`**

Metode Publik:
- **before**(User $user, $ability)
- **viewAny**(User $user) : *bool*
- **view**(User $user, Branch $branch) : *bool*
- **create**(User $user) : *bool*
- **update**(User $user, Branch $branch) : *bool*
- **delete**(User $user, Branch $branch) : *bool*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Branch;

class BranchPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('Superadmin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole(['Superadmin', 'Admin Cabang']);
    }

    public function view(User $user, Branch $branch): bool
    {
        if ($user->hasRole('Admin Cabang')) {
            return (string) $user->branch_id === (string) $branch->id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('superadmin');
    }

    public function update(User $user, Branch $branch): bool
    {
        if ($user->hasRole('Admin Cabang')) {
            return (string) $user->branch_id === (string) $branch->id;
        }
        return false;
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->hasRole('superadmin');
    }
}

```
</details>

### app/Policies/CategoryPolicy.php

- SHA: `83d6b855eca7`  
- Ukuran: 960 B  
- Namespace: `App\Policies`

**Class `CategoryPolicy`**

Metode Publik:
- **before**(User $user, $ability)
- **viewAny**(User $user) : *bool*
- **view**(User $user, ServiceCategory $category) : *bool*
- **create**(User $user) : *bool*
- **update**(User $user, ServiceCategory $category) : *bool*
- **delete**(User $user, ServiceCategory $category) : *bool*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ServiceCategory;

class CategoryPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('Superadmin'))
            return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang']);
    }

    public function view(User $user, ServiceCategory $category): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang']);
    }

    public function update(User $user, ServiceCategory $category): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang']);
    }

    public function delete(User $user, ServiceCategory $category): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang']);
    }
}

```
</details>

### app/Policies/CustomerPolicy.php

- SHA: `246ec52cc10f`  
- Ukuran: 2 KB  
- Namespace: `App\Policies`

**Class `CustomerPolicy`**

Metode Publik:
- **before**(User $user, $ability)
- **viewAny**(User $user) : *bool*
- **view**(User $user, Customer $c) : *bool*
- **create**(User $user) : *bool*
- **update**(User $user, Customer $c) : *bool*
- **viewLoyalty**(User $user, Customer $customer) : *bool*
- **delete**(User $user, Customer $c) : *bool*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('Superadmin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang', 'Kasir']);
    }

    public function view(User $user, Customer $c): bool
    {
        if ($user->hasRole('Admin Cabang') || $user->hasRole('Kasir')) {
            return (string) $user->branch_id === (string) $c->branch_id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang', 'Kasir']);
    }

    public function update(User $user, Customer $c): bool
    {
        if ($user->hasRole('Admin Cabang') || $user->hasRole('Kasir')) {
            return (string) $user->branch_id === (string) $c->branch_id;
        }
        return false;
    }

    public function viewLoyalty(User $user, Customer $customer): bool
    {
        if ($user->hasRole('Superadmin')) return true;
        if ($user->hasAnyRole(['Admin Cabang', 'Kasir'])) {
            return (string)$user->branch_id === (string)$customer->branch_id;
        }
        return false;
    }

    public function delete(User $user, Customer $c): bool
    {
        if ($user->hasRole('Admin Cabang')) {
            return (string) $user->branch_id === (string) $c->branch_id;
        }
        // Kasir tidak bisa delete
        return false;
    }
}

```
</details>

### app/Policies/DeliveryPolicy.php

- SHA: `b5d183b67b82`  
- Ukuran: 1 KB  
- Namespace: `App\Policies`

**Class `DeliveryPolicy`**

Metode Publik:
- **before**(User $user, $ability)
- **create**(User $user) : *bool*
- **assignCourier**(User $user, Delivery $delivery) : *bool*
- **updateStatus**(User $user, Delivery $delivery) : *bool*
- **view**(User $user, Delivery $delivery) : *bool*
- **viewAny**(User $user) : *bool*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Policies;

use App\Models\Delivery;
use App\Models\User;

class DeliveryPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('Superadmin'))
            return true;
        return null;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Admin Cabang', 'Kasir']);
    }

    public function assignCourier(User $user, Delivery $delivery): bool
    {
        if ($user->hasRole('Admin Cabang')) {
            return (string) $delivery->order?->branch_id === (string) $user->branch_id;
        }
        return false;
    }

    public function updateStatus(User $user, Delivery $delivery): bool
    {
        if ($user->hasRole('Kurir')) {
            return (int) $delivery->assigned_to === (int) $user->id;
        }
        if ($user->hasRole('Admin Cabang')) {
            return (string) $delivery->order?->branch_id === (string) $user->branch_id;
        }
        return false;
    }

    public function view(User $user, Delivery $delivery): bool
    {
        if ($user->hasAnyRole(['Admin Cabang', 'Kasir', 'Petugas Cuci', 'Kurir'])) {
            return (string) $delivery->order?->branch_id === (string) $user->branch_id
                || (int) $delivery->assigned_to === (int) $user->id;
        }
        return false;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Admin Cabang', 'Kasir', 'Petugas Cuci', 'Kurir']);
    }
}

```
</details>

### app/Policies/ExpensePolicy.php

- SHA: `57cfc797292f`  
- Ukuran: 1 KB  
- Namespace: `App\Policies`

**Class `ExpensePolicy`**

Metode Publik:
- **before**(?User $user, string $ability)
- **viewAny**(User $user) : *bool*
- **view**(User $user, Expense $expense) : *bool*
- **create**(User $user) : *bool*
- **update**(User $user, Expense $expense) : *bool*
- **delete**(User $user, Expense $expense) : *bool*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
{
    use HandlesAuthorization;

    public function before(?User $user, string $ability)
    {
        if ($user && $user->hasRole('Superadmin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        // Sesuai SOP: modul Expenses hanya untuk Admin Cabang (dan Superadmin via before)
        return $user->hasRole('Admin Cabang');
    }

    public function view(User $user, Expense $expense): bool
    {
        if ($user->hasRole('Admin Cabang') && $user->branch_id !== null) {
            return $expense->branch_id === $user->branch_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Admin Cabang') && $user->branch_id !== null;
    }

    public function update(User $user, Expense $expense): bool
    {
        return $this->view($user, $expense);
    }

    public function delete(User $user, Expense $expense): bool
    {
        return $this->view($user, $expense);
    }
}

```
</details>

### app/Policies/LoyaltyPolicy.php

- SHA: `1792a12c3762`  
- Ukuran: 382 B  
- Namespace: `App\Policies`

**Class `LoyaltyPolicy`**

Metode Publik:
- **before**(User $actor, string $ability) : *bool|null*
- **view**(User $actor, Customer $customer) : *bool*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;

class LoyaltyPolicy
{
    public function before(User $actor, string $ability): bool|null
    {
        return $actor->hasRole('Superadmin') ? true : null;
    }

    public function view(User $actor, Customer $customer): bool
    {
        return $actor->hasAnyRole(['Admin Cabang', 'Kasir']);
    }
}

```
</details>

### app/Policies/OrderPolicy.php

- SHA: `5d88ccb506d5`  
- Ukuran: 2 KB  
- Namespace: `App\Policies`

**Class `OrderPolicy`**

Metode Publik:
- **before**(User $user, $ability)
- **viewAny**(User $user) : *bool*
- **view**(User $user, Order $order) : *bool*
- **create**(User $user) : *bool*
- **update**(User $user, Order $order) : *bool*
- **delete**(User $user, Order $order) : *bool*
- **transitionStatus**(User $user, Order $order) : *bool*
- **settlePayment**(User $user, Order $order) : *bool*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('Superadmin'))
            return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Admin Cabang', 'Kasir', 'Petugas Cuci', 'Kurir']);
    }

    public function view(User $user, Order $order): bool
    {
        if ($user->hasAnyRole(['Admin Cabang', 'Kasir', 'Petugas Cuci', 'Kurir'])) {
            return (string) $user->branch_id === (string) $order->branch_id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Admin Cabang', 'Kasir']);
    }

    public function update(User $user, Order $order): bool
    {
        if (! $user->hasRole('Admin Cabang')) {
            return false;
        }
        // Harus satu cabang
        $sameBranch = (string) $user->branch_id === (string) $order->branch_id;
        if (! $sameBranch) return false;
        // Kunci: status terminal tidak boleh diedit
        $terminal = in_array($order->status, ['DELIVERING', 'PICKED_UP', 'CANCELED'], true);
        if ($terminal) return false;
        return true;
    }

    public function delete(User $user, Order $order): bool
    {
        // Hanya Admin Cabang
        if (! $user->hasRole('Admin Cabang')) return false;
        // Harus satu cabang
        $sameBranch = (string) $user->branch_id === (string) $order->branch_id;
        if (! $sameBranch) return false;
        // Kunci: status terminal tidak boleh dihapus
        $terminal = in_array($order->status, ['DELIVERING', 'PICKED_UP', 'CANCELED'], true);
        if ($terminal) return false;
        // Kunci: jika sudah ada pembayaran tidak boleh dihapus
        if ($order->payments()->exists()) return false;
        return true;
    }

    public function transitionStatus(User $user, Order $order): bool
    {
        if ($user->hasAnyRole(['Admin Cabang', 'Kasir', 'Petugas Cuci', 'Kurir'])) {
            return (string) $user->branch_id === (string) $order->branch_id;
        }
        return false;
    }

    public function settlePayment(User $user, Order $order): bool
    {
        if ($user->hasAnyRole(['Admin Cabang', 'Kasir'])) {
            return (string) $user->branch_id === (string) $order->branch_id;
        }
        return false;
    }
}

```
</details>

### app/Policies/ReceivablePolicy.php

- SHA: `839b5ee441f9`  
- Ukuran: 955 B  
- Namespace: `App\Policies`

**Class `ReceivablePolicy`**

Metode Publik:
- **before**(User $user, $ability)
- **viewAny**(User $user) : *bool*
- **view**(User $user, Receivable $receivable) : *bool*
- **settle**(User $user, Receivable $receivable) : *bool*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Receivable;

class ReceivablePolicy
{
    public function before(User $user, $ability)
    {
        // Superadmin full access
        if ($user->hasRole('Superadmin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        // Kasir & Admin Cabang boleh melihat
        return $user->hasRole(['Admin Cabang', 'Kasir', 'Superadmin']);
    }

    public function view(User $user, Receivable $receivable): bool
    {
        // Batasi per cabang via relasi Order
        return $user->branch_id && $receivable->order && $receivable->order->branch_id === $user->branch_id;
    }

    public function settle(User $user, Receivable $receivable): bool
    {
        // Pelunasan oleh Kasir/Admin Cabang di cabang yang sama
        return $this->view($user, $receivable) && $user->hasRole(['Admin Cabang', 'Kasir']);
    }
}

```
</details>

### app/Policies/ServicePolicy.php

- SHA: `c2bbfe6c8d4e`  
- Ukuran: 940 B  
- Namespace: `App\Policies`

**Class `ServicePolicy`**

Metode Publik:
- **before**(User $user, $ability)
- **viewAny**(User $user) : *bool*
- **view**(User $user, Service $service) : *bool*
- **create**(User $user) : *bool*
- **update**(User $user, Service $service) : *bool*
- **delete**(User $user, Service $service) : *bool*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Service;

class ServicePolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('Superadmin'))
            return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang','Kasir']);
    }

    public function view(User $user, Service $service): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang','Kasir']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang']);
    }

    public function update(User $user, Service $service): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang']);
    }

    public function delete(User $user, Service $service): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Cabang']);
    }
}

```
</details>

### app/Policies/UserPolicy.php

- SHA: `5a811da043ce`  
- Ukuran: 3 KB  
- Namespace: `App\Policies`

**Class `UserPolicy`**

Metode Publik:
- **before**(User $actor, string $ability) : *bool|null* — Superadmin bypass semua cek policy.
- **viewAny**(User $actor) : *bool* — Superadmin bypass semua cek policy.
- **view**(User $actor, User $target) : *bool* — Superadmin bypass semua cek policy.
- **create**(User $actor) : *bool* — Superadmin bypass semua cek policy.
- **update**(User $actor, User $target) : *bool* — Superadmin bypass semua cek policy.
- **delete**(User $actor, User $target) : *bool* — Superadmin bypass semua cek policy.
- **resetPassword**(User $actor, User $target) : *bool* — Superadmin bypass semua cek policy.
- **setActive**(User $actor, User $target) : *bool* — Superadmin bypass semua cek policy.
- **setRoles**(User $actor, User $target) : *bool* — Superadmin bypass semua cek policy.
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
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

```
</details>

### app/Policies/VoucherPolicy.php

- SHA: `063e1272ad19`  
- Ukuran: 1 KB  
- Namespace: `App\Policies`

**Class `VoucherPolicy`**

Metode Publik:
- **before**(User $user, string $ability) : *?bool*
- **viewAny**(User $user) : *bool*
- **view**(User $user, Voucher $voucher) : *bool*
- **create**(User $user) : *bool*
- **update**(User $user, Voucher $voucher) : *bool*
- **delete**(User $user, Voucher $voucher) : *bool*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voucher;

class VoucherPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        // Selaraskan kapitalisasi role dengan data/FE
        if ($user->hasRole('Superadmin'))
            return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole('Admin Cabang');
    }

    public function view(User $user, Voucher $voucher): bool
    {
        if ($user->hasRole('Admin Cabang'))
            return $voucher->branch_id === $user->branch_id;
        if ($user->hasRole('Kasir'))
            return $voucher->active === true;
        return $user->branch_id && $user->branch_id === $voucher->branch_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Admin Cabang');
    }

    public function update(User $user, Voucher $voucher): bool
    {
        if ($voucher->branch_id === null)
            return false; // global hanya superadmin (ditangani di before)
        return $user->hasRole('Admin Cabang') && $voucher->branch_id === $user->branch_id;
    }

    public function delete(User $user, Voucher $voucher): bool
    {
        if ($voucher->branch_id === null)
            return false;
        return $user->hasRole('Admin Cabang') && $voucher->branch_id === $user->branch_id;
    }
}


```
</details>

### app/Policies/WashNotePolicy.php

- SHA: `8b2f48814901`  
- Ukuran: 2 KB  
- Namespace: `App\Policies`

**Class `WashNotePolicy`**

Metode Publik:
- **before**(User $user, string $ability) : *bool|null*
- **viewAny**(User $user) : *bool*
- **view**(User $user, WashNote $note) : *bool*
- **create**(User $user) : *bool*
- **update**(User $user, WashNote $note) : *bool*
- **delete**(User $user, WashNote $note) : *bool*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
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

```
</details>



## Form Requests (app/Http/Requests)

### app/Http/Requests/Auth/LoginRequest.php

- SHA: `8dd59b857462`  
- Ukuran: 2 KB  
- Namespace: `App\Http\Requests\Auth`

**Class `LoginRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool* — Determine if the user is authorized to make this request.
- **rules**() : *array* — Determine if the user is authorized to make this request.
- **authenticate**() : *void* — Determine if the user is authorized to make this request.
- **ensureIsNotRateLimited**() : *void* — Determine if the user is authorized to make this request.
- **throttleKey**() : *string* — Determine if the user is authorized to make this request.
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}

```
</details>

### app/Http/Requests/BranchStoreRequest.php

- SHA: `bc0cc0149344`  
- Ukuran: 680 B  
- Namespace: `App\Http\Requests`

**Class `BranchStoreRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Branch::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:32', 'unique:branches,code'],
            'name' => ['required', 'string', 'max:150'],
            'address' => ['nullable', 'string', 'max:255'],
            'invoice_prefix' => ['required', 'string', 'regex:/^[A-Z]{2,8}$/'],
            'reset_policy' => ['required', 'in:monthly,never'],
        ];
    }
}

```
</details>

### app/Http/Requests/BranchUpdateRequest.php

- SHA: `e016b3d04ff4`  
- Ukuran: 762 B  
- Namespace: `App\Http\Requests`

**Class `BranchUpdateRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $branch = $this->route('branch');
        return $this->user()?->can('update', $branch) ?? false;
    }

    public function rules(): array
    {
        $branch = $this->route('branch');
        return [
            'code' => ['required', 'string', 'max:32', 'unique:branches,code,' . $branch->id],
            'name' => ['required', 'string', 'max:150'],
            'address' => ['nullable', 'string', 'max:255'],
            'invoice_prefix' => ['required', 'string', 'regex:/^[A-Z]{2,8}$/'],
            'reset_policy' => ['required', 'in:monthly,never'],
        ];
    }
}

```
</details>

### app/Http/Requests/CategoryStoreRequest.php

- SHA: `0d7a9632ab19`  
- Ukuran: 479 B  
- Namespace: `App\Http\Requests`

**Class `CategoryStoreRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ServiceCategory;

class CategoryStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', ServiceCategory::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}

```
</details>

### app/Http/Requests/CategoryUpdateRequest.php

- SHA: `f7580edead1e`  
- Ukuran: 513 B  
- Namespace: `App\Http\Requests`

**Class `CategoryUpdateRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ServiceCategory;

class CategoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $category = $this->route('category');
        return $this->user()?->can('update', $category) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}

```
</details>

### app/Http/Requests/CustomerSearchWARequest.php

- SHA: `359f2e485ed7`  
- Ukuran: 401 B  
- Namespace: `App\Http\Requests`

**Class `CustomerSearchWARequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerSearchWARequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null; // authorize di controller via policy viewAny
    }

    public function rules(): array
    {
        return [
            'wa' => ['required', 'string', 'max:32'],
        ];
    }
}

```
</details>

### app/Http/Requests/CustomerStoreRequest.php

- SHA: `38679783f912`  
- Ukuran: 1 KB  
- Namespace: `App\Http\Requests`

**Class `CustomerStoreRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Customer::class) ?? false;
    }

    public function rules(): array
    {
        $branchId = $this->input('branch_id') ?: $this->user()?->branch_id;

        return [
            'branch_id' => ['nullable', 'uuid', 'exists:branches,id'],
            'name' => ['required', 'string', 'max:150'],
            'whatsapp' => [
                'required',
                'string',
                'max:32',
                Rule::unique('customers', 'whatsapp')->where(fn($q) => $q->where('branch_id', $branchId)),
            ],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Normalisasi ringan: trim dan hilangkan spasi pada whatsapp
        $wa = preg_replace('/\s+/', '', (string) $this->input('whatsapp'));
        $this->merge(['whatsapp' => $wa]);
    }
}

```
</details>

### app/Http/Requests/CustomerUpdateRequest.php

- SHA: `5285851be61d`  
- Ukuran: 1 KB  
- Namespace: `App\Http\Requests`

**Class `CustomerUpdateRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array* — @var Customer $customer
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Customer;

class CustomerUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Customer $customer */
        $customer = $this->route('customer');
        return $this->user()?->can('update', $customer) ?? false;
    }

    public function rules(): array
    {
        /** @var Customer $customer */
        $customer = $this->route('customer');
        $branchId = $customer->branch_id;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:150'],
            'whatsapp' => [
                'sometimes',
                'required',
                'string',
                'max:32',
                Rule::unique('customers', 'whatsapp')
                    ->where(fn($q) => $q->where('branch_id', $branchId))
                    ->ignore($customer->id, 'id'),
            ],
            'address' => ['sometimes', 'nullable', 'string', 'max:255'],
            'notes' => ['sometimes', 'nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('whatsapp')) {
            $this->merge(['whatsapp' => preg_replace('/\s+/', '', (string) $this->input('whatsapp'))]);
        }
    }
}

```
</details>

### app/Http/Requests/Dashboard/DashboardSummaryRequest.php

- SHA: `a4b594f5872d`  
- Ukuran: 1 KB  
- Namespace: `App\Http\Requests\Dashboard`

**Class `DashboardSummaryRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
- **fromDate**() : *Carbon*
- **toDate**() : *Carbon*
- **branchId**() : *?string*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class DashboardSummaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('dashboard.summary') === true;
    }

    public function rules(): array
    {
        return [
            'from' => ['required', 'date_format:Y-m-d'],
            'to' => ['required', 'date_format:Y-m-d', 'after_or_equal:from'],
            'branch_id' => ['nullable', 'uuid'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Non-Superadmin wajib terikat ke cabangnya sendiri bila branch_id tidak diisi.
        $u = $this->user();
        if ($u && !$u->hasRole('Superadmin') && !$this->input('branch_id')) {
            $this->merge(['branch_id' => $u->branch_id]);
        }
    }

    public function fromDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d', $this->input('from'))->startOfDay();
    }

    public function toDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d', $this->input('to'))->endOfDay();
    }

    public function branchId(): ?string
    {
        return $this->input('branch_id');
    }
}

```
</details>

### app/Http/Requests/Deliveries/DeliveryAssignRequest.php

- SHA: `990c3eea3cce`  
- Ukuran: 424 B  
- Namespace: `App\Http\Requests\Deliveries`

**Class `DeliveryAssignRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests\Deliveries;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryAssignRequest extends FormRequest
{
    public function authorize(): bool
    {
        // authorize di controller via policy assignCourier
        return true;
    }

    public function rules(): array
    {
        return [
            'courier_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}

```
</details>

### app/Http/Requests/Deliveries/DeliveryStatusRequest.php

- SHA: `43bdfd523d1b`  
- Ukuran: 1007 B  
- Namespace: `App\Http\Requests\Deliveries`

**Class `DeliveryStatusRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
- **messages**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests\Deliveries;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeliveryStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in([
                    'CREATED',
                    'ASSIGNED',
                    'ON_THE_WAY',
                    'PICKED',
                    'HANDOVER',
                    'COMPLETED',
                    'FAILED',
                    'CANCELLED'
                ]),
            ],
            'note' => ['nullable', 'string', 'max:200'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'photo.image' => 'File foto harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 4MB.',
        ];
    }
}

```
</details>

### app/Http/Requests/Deliveries/DeliveryStoreRequest.php

- SHA: `659b9476cda8`  
- Ukuran: 1 KB  
- Namespace: `App\Http\Requests\Deliveries`

**Class `DeliveryStoreRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests\Deliveries;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeliveryStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Delivery::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        // normalisasi 'type'
        if ($t = $this->input('type')) {
            $this->merge(['type' => strtolower($t)]);
        }
    }

    public function rules(): array
    {
        $branchId = $this->user()?->branch_id;

        return [
            'order_id' => [
                'required', 'uuid',
                Rule::exists('orders', 'id')
                    ->when(!$this->user()?->hasRole('Superadmin'),
                        fn($q) => $q->where('branch_id', $branchId)),
            ],
            'type' => ['required', Rule::in(['pickup','delivery','return'])],
            'zone_id' => ['nullable','uuid'],
            'fee' => ['nullable','numeric','min:0'],
        ];
    }
}

```
</details>

### app/Http/Requests/Expenses/ExpenseStoreRequest.php

- SHA: `5c7bf55f4b7b`  
- Ukuran: 610 B  
- Namespace: `App\Http\Requests\Expenses`

**Class `ExpenseStoreRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests\Expenses;

use App\Models\Expense;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Expense::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'category' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
            'proof' => ['nullable', 'file', 'max:4096', 'mimes:jpg,jpeg,png,pdf'],
        ];
    }
}

```
</details>

### app/Http/Requests/Expenses/ExpenseUpdateRequest.php

- SHA: `1c565a8f575d`  
- Ukuran: 699 B  
- Namespace: `App\Http\Requests\Expenses`

**Class `ExpenseUpdateRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array* — @var \App\Models\Expense $expense
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests\Expenses;

use App\Models\Expense;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\Expense $expense */
        $expense = $this->route('expense');

        return $this->user()?->can('update', $expense) ?? false;
    }

    public function rules(): array
    {
        return [
            'category' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
            'proof' => ['nullable', 'file', 'max:4096', 'mimes:jpg,jpeg,png,pdf'],
        ];
    }
}

```
</details>

### app/Http/Requests/InvoiceCounterStoreRequest.php

- SHA: `a8ce0038a22b`  
- Ukuran: 860 B  
- Namespace: `App\Http\Requests`

**Class `InvoiceCounterStoreRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
- **messages**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceCounterStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // di-controller tetap pakai Policy pada Branch
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'uuid', 'exists:branches,id'],
            'prefix' => ['required', 'string', 'max:8'],
            'reset_policy' => ['required', 'in:monthly,never'],
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required' => 'branch_id is required',
            'prefix.required' => 'prefix is required',
            'prefix.max' => 'prefix max length is 8 characters',
            'reset_policy.in' => 'reset_policy must be monthly or never',
        ];
    }
}

```
</details>

### app/Http/Requests/InvoiceCounterUpdateRequest.php

- SHA: `abbd2d61549c`  
- Ukuran: 538 B  
- Namespace: `App\Http\Requests`

**Class `InvoiceCounterUpdateRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceCounterUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['Superadmin', 'Admin Cabang']) ?? false;
    }

    public function rules(): array
    {
        return [
            'prefix' => ['required', 'string', 'regex:/^[A-Z]{2,8}$/'],
            'reset_policy' => ['required', 'in:monthly,never'],
            'seq' => ['required', 'integer', 'min:0'],
        ];
    }
}

```
</details>

### app/Http/Requests/OrderStatusRequest.php

- SHA: `fb9d1581c5c5`  
- Ukuran: 487 B  
- Namespace: `App\Http\Requests`

**Class `OrderStatusRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $order = $this->route('order');
        return $this->user()?->can('transitionStatus', $order) ?? false;
    }

    public function rules(): array
    {
        return [
            'next' => ['required', 'string', 'in:QUEUE,WASHING,DRYING,IRONING,READY,DELIVERING,PICKED_UP,CANCELED'],
        ];
    }
}

```
</details>

### app/Http/Requests/OrderStoreRequest.php

- SHA: `cc1faa755d53`  
- Ukuran: 3 KB  
- Namespace: `App\Http\Requests`

**Class `OrderStoreRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array* — Normalisasi waktu lokal "naif" ke 'Y-m-d H:i:s' tanpa konversi zona waktu.
- **messages**() : *array* — Normalisasi waktu lokal "naif" ke 'Y-m-d H:i:s' tanpa konversi zona waktu.
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Order::class) ?? false;
    }

    /**
     * Normalisasi waktu lokal "naif" ke 'Y-m-d H:i:s' tanpa konversi zona waktu.
     */
    protected function normalizeLocal(?string $dt): ?string
    {
        if (!$dt) return null;
        $s = str_replace('T', ' ', trim($dt));
        $s = preg_replace('/Z$/', '', $s); // buang Z bila ada
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $s)) {
            $s .= ':00';
        }
        try {
            return \Carbon\CarbonImmutable::createFromFormat('Y-m-d H:i:s', $s)->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            // Jika format tak cocok, biarkan apa adanya agar validator 'date' menangkapnya.
            return $s;
        }
    }

    protected function prepareForValidation(): void
    {
        $data = [];
        if ($this->has('customer_id')) {
            $data['customer_id'] = trim((string) $this->input('customer_id'));
        }
        if ($this->has('notes')) {
            $data['notes'] = ($this->input('notes') === null) ? null : trim((string) $this->input('notes'));
        }
        // Normalisasi datetime lokal (tanpa konversi TZ) untuk kolom TIMESTAMP WITHOUT TIME ZONE
        if ($this->has('received_at')) {
            $data['received_at'] = $this->normalizeLocal($this->input('received_at'));
        }
        if ($this->has('ready_at')) {
            $data['ready_at'] = $this->normalizeLocal($this->input('ready_at'));
        }
        if ($data !== []) {
            $this->merge($data);
        }
    }

    public function rules(): array
    {
        $branchId = $this->user()?->branch_id;

        return [
            'branch_id' => ['nullable', 'uuid', 'exists:branches,id'],
            'customer_id' => [
                'required',
                'uuid',
                Rule::exists('customers', 'id')->where(fn($q) => $q->where('branch_id', $branchId)),
            ],
            'notes' => ['nullable', 'string'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.service_id' => ['required', 'uuid', 'exists:services,id'],
            'items.*.qty' => ['required', 'numeric', 'gt:0'],
            'received_at' => ['nullable', 'date'],
            'ready_at'    => ['nullable', 'date', 'after_or_equal:received_at'],
            // price dari klien diabaikan; server akan hitung pakai PricingService
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Pelanggan wajib dipilih.',
            'customer_id.uuid' => 'Pelanggan tidak valid.',
            'customer_id.exists' => 'Pelanggan tidak ditemukan di cabang Anda.',
        ];
    }
}

```
</details>

### app/Http/Requests/OrderUpdateRequest.php

- SHA: `848783099280`  
- Ukuran: 3 KB  
- Namespace: `App\Http\Requests`

**Class `OrderUpdateRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array* — Normalisasi waktu lokal "naif" ke 'Y-m-d H:i:s' tanpa konversi zona waktu.
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalisasi waktu lokal "naif" ke 'Y-m-d H:i:s' tanpa konversi zona waktu.
     */
    protected function normalizeLocal(?string $dt): ?string
    {
        if (!$dt) return null;
        $s = str_replace('T', ' ', trim($dt));
        $s = preg_replace('/Z$/', '', $s); // buang Z bila ada
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $s)) {
            $s .= ':00';
        }
        try {
            return \Carbon\CarbonImmutable::createFromFormat('Y-m-d H:i:s', $s)->format('Y-m-d H:i:s');
        } catch (\Throwable) {
            // Jika format tak cocok, biarkan apa adanya agar validator 'date' menangkapnya.
            return $s;
        }
    }

    public function rules(): array
    {
        return [
            'customer_id'        => ['sometimes', 'nullable', 'uuid', 'exists:customers,id'],
            'discount'           => ['sometimes', 'numeric', 'min:0'],
            'notes'              => ['sometimes', 'nullable', 'string', 'max:500'],

            'items'              => ['sometimes', 'array', 'min:1'],
            'items.*.id'         => ['sometimes', 'uuid', 'exists:order_items,id'],
            'items.*.service_id' => ['required_with:items', 'uuid', 'exists:services,id', 'distinct'],
            'items.*.qty'        => ['required_with:items', 'integer', 'min:1'],
            'items.*.note'       => ['sometimes', 'nullable', 'string', 'max:300'],
            'received_at' => ['nullable', 'date'],
            'ready_at'    => ['nullable', 'date', 'after_or_equal:received_at'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $data = $this->all();
        // Trim field sederhana bila dikirim
        if (array_key_exists('customer_id', $data) && $data['customer_id'] !== null) {
            $data['customer_id'] = trim((string) $data['customer_id']);
        }
        if (array_key_exists('notes', $data)) {
            $data['notes'] = $data['notes'] === null ? null : trim((string) $data['notes']);
        }
        if (isset($data['discount']) && $data['discount'] !== null) {
            $data['discount'] = is_numeric($data['discount']) ? (float) $data['discount'] : $data['discount'];
        }
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $k => $row) {
                if (isset($row['qty'])) {
                    $data['items'][$k]['qty'] = is_numeric($row['qty']) ? (int) $row['qty'] : $row['qty'];
                }
            }
        }
        // Normalisasi datetime lokal (tanpa konversi TZ) untuk kolom TIMESTAMP WITHOUT TIME ZONE
        if (array_key_exists('received_at', $data)) {
            $data['received_at'] = $this->normalizeLocal($data['received_at']);
        }
        if (array_key_exists('ready_at', $data)) {
            $data['ready_at'] = $this->normalizeLocal($data['ready_at']);
        }
        $this->replace($data);
    }
}

```
</details>

### app/Http/Requests/Orders/OrderApplyVoucherRequest.php

- SHA: `f712b239f60b`  
- Ukuran: 423 B  
- Namespace: `App\Http\Requests\Orders`

**Class `OrderApplyVoucherRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class OrderApplyVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Otorisasi pada controller via $this->authorize('update', $order)
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:40'],
        ];
    }
}

```
</details>

### app/Http/Requests/Orders/OrderPhotosRequest.php

- SHA: `8f5485516bd5`  
- Ukuran: 723 B  
- Namespace: `App\Http\Requests\Orders`

**Class `OrderPhotosRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
- **messages**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class OrderPhotosRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Otorisasi akan dicek di Controller (authorize('update', $order))
        return true;
    }

    public function rules(): array
    {
        return [
            'photos.before.*' => ['image', 'max:4096'], // ~4 MB per file
            'photos.after.*'  => ['image', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'photos.before.*.image' => 'File "before" harus berupa gambar.',
            'photos.after.*.image'  => 'File "after" harus berupa gambar.',
        ];
    }
}

```
</details>

### app/Http/Requests/Payments/PaymentRequest.php

- SHA: `cc8052cfe780`  
- Ukuran: 1 KB  
- Namespace: `App\Http\Requests\Payments`

**Class `PaymentRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
- **withValidator**($validator)
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authorize di controller (settlePayment/update)
    }

    public function rules(): array
    {
        return [
            'method' => ['required', 'in:PENDING,DP,CASH,QRIS,TRANSFER'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'paid_at' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $method = (string) $this->input('method');
            $amount = (float) $this->input('amount', 0);
            $grand = (float) $this->route('order')->grand_total; // order binding
            if ($method === 'DP' && $amount > $grand) {
                $v->errors()->add('amount', 'DP tidak boleh melebihi grand_total.');
            }
        });
    }
}

```
</details>

### app/Http/Requests/Receivables/ReceivableCreateRequest.php

- SHA: `680365444219`  
- Ukuran: 343 B  
- Namespace: `App\Http\Requests\Receivables`

**Class `ReceivableCreateRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests\Receivables;

use Illuminate\Foundation\Http\FormRequest;

class ReceivableCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'due_date' => ['nullable', 'date'],
        ];
    }
}

```
</details>

### app/Http/Requests/Receivables/ReceivableSettleRequest.php

- SHA: `c7af4148cc72`  
- Ukuran: 1 KB  
- Namespace: `App\Http\Requests\Receivables`

**Class `ReceivableSettleRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
- **withValidator**(Validator $validator) : *void*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests\Receivables;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Receivable;

class ReceivableSettleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'method' => ['required', 'in:CASH,QRIS,TRANSFER'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'paid_at' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            $id = $this->route('id');
            $rcv = $id ? Receivable::query()->with('order')->find($id) : null;
            if (!$rcv) {
                return;
            }
            $amt = (float) $this->input('amount', 0);
            $remaining = (float) $rcv->remaining_amount;
            if ($amt > $remaining + 1e-6) {
                $v->errors()->add('amount', 'Jumlah melebihi sisa piutang.');
            }
        });
    }
}

```
</details>

### app/Http/Requests/Reports/ReportFilterRequest.php

- SHA: `6ee01e303594`  
- Ukuran: 2 KB  
- Namespace: `App\Http\Requests\Reports`

**Class `ReportFilterRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
- **fromDate**() : *Carbon*
- **toDate**() : *Carbon*
- **branchId**() : *?string*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class ReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Selaras dengan DashboardSummaryRequest (gate/ability yang sama)
        return $this->user()?->can('dashboard.summary') === true;
    }

    public function rules(): array
    {
        return [
            'from' => ['required', 'date_format:Y-m-d'],
            'to' => ['required', 'date_format:Y-m-d', 'after_or_equal:from'],
            'branch_id' => ['nullable', 'uuid'],
            // filter spesifik opsional
            'method' => ['nullable', 'string', 'max:32'], // untuk sales (payments)
            'status' => ['nullable', 'string', 'max:32'], // untuk orders/receivables
            'format' => ['nullable', 'in:csv,xlsx'],
            'delimiter' => ['nullable', 'in:comma,semicolon,tab'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $u = $this->user();
        if ($u && !$u->hasRole('Superadmin') && !$this->input('branch_id')) {
            $this->merge(['branch_id' => $u->branch_id]);
        }
    }

    public function fromDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d', $this->input('from'))->startOfDay();
    }

    public function toDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d', $this->input('to'))->endOfDay();
    }

    public function branchId(): ?string
    {
        return $this->input('branch_id');
    }
}

```
</details>

### app/Http/Requests/ResetPasswordRequest.php

- SHA: `7973077d9c62`  
- Ukuran: 428 B  
- Namespace: `App\Http\Requests`

**Class `ResetPasswordRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}

```
</details>

### app/Http/Requests/ServicePriceSetRequest.php

- SHA: `e5e29ccac2f7`  
- Ukuran: 837 B  
- Namespace: `App\Http\Requests`

**Class `ServicePriceSetRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
- **messages**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServicePriceSetRequest extends FormRequest
{
    public function authorize(): bool
    {
        // otorisasi dilakukan pada controller via policy Service + scope cabang
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'service_id' => ['required', 'uuid', 'exists:services,id'],
            'branch_id' => ['required', 'uuid', 'exists:branches,id'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'service_id.required' => 'service_id wajib diisi',
            'branch_id.required' => 'branch_id wajib diisi',
            'price.required' => 'price wajib diisi',
        ];
    }
}

```
</details>

### app/Http/Requests/ServiceStoreRequest.php

- SHA: `77a97b9513f4`  
- Ukuran: 666 B  
- Namespace: `App\Http\Requests`

**Class `ServiceStoreRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Service;

class ServiceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Service::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'uuid', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'unit' => ['required', 'string', 'max:32'],
            'price_default' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}

```
</details>

### app/Http/Requests/ServiceUpdateRequest.php

- SHA: `21cee56c7eb0`  
- Ukuran: 705 B  
- Namespace: `App\Http\Requests`

**Class `ServiceUpdateRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Service;

class ServiceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $service = $this->route('service');
        return $this->user()?->can('update', $service) ?? false;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'uuid', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'unit' => ['required', 'string', 'max:32'],
            'price_default' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}

```
</details>

### app/Http/Requests/UserStoreRequest.php

- SHA: `f2f73594304a`  
- Ukuran: 3 KB  
- Namespace: `App\Http\Requests`

**Class `UserStoreRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
- **withValidator**($validator) : *void*
- **messages**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\User::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('username')) {
            $this->merge(['username' => strtolower(trim((string) $this->input('username')))]);
        }
        if ($this->has('email')) {
            $this->merge(['email' => strtolower(trim((string) $this->input('email')))]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-z0-9_.]+$/',
                'unique:users,username',
            ],
            'password' => ['required', Password::min(8)->mixedCase()->numbers()],
            // Jika kamu ingin default aktif = true, ubah ke 'sometimes|boolean' lalu handle default di controller
            'is_active' => ['required', 'boolean'],
            'branch_id' => ['nullable', 'uuid', 'exists:branches,id'],
            'role' => [
                'required',
                'string',
                'max:100',
                Rule::exists('roles', 'name')->where('guard_name', 'web'),
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $actor = $this->user();

            if (!$actor)
                return;

            // Admin Cabang: branch_id WAJIB ada & harus sama dengan cabang aktor
            if ($actor->hasRole('Admin Cabang')) {
                $branchId = (string) $this->input('branch_id', '');
                if ($branchId === '') {
                    $v->errors()->add('branch_id', 'branch_id wajib diisi untuk Admin Cabang.');
                } elseif ($branchId !== (string) $actor->branch_id) {
                    $v->errors()->add('branch_id', 'Anda hanya boleh membuat user pada cabang Anda sendiri.');
                }

                // Admin Cabang dilarang set Superadmin
                if ($this->input('role') === 'Superadmin') {
                    $v->errors()->add('role', 'Admin Cabang tidak boleh menetapkan role Superadmin.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'role.required' => 'Role wajib diisi.',
            'role.exists' => 'Role tidak valid.',
            'branch_id.uuid' => 'branch_id harus berupa UUID yang valid.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'username.regex' => 'Username hanya boleh huruf/angka/underscore/titik (lowercase).',
        ];
    }
}

```
</details>

### app/Http/Requests/UserUpdateRequest.php

- SHA: `0ae2325164cc`  
- Ukuran: 3 KB  
- Namespace: `App\Http\Requests`

**Class `UserUpdateRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array* — @var \App\Models\User|null $target
- **withValidator**($validator) : *void* — @var \App\Models\User|null $target
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\User|null $target */
        $target = $this->route('user') ?? User::query()->find($this->route('id'));
        return $target ? ($this->user()?->can('update', $target) ?? false) : false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('username')) {
            $this->merge(['username' => strtolower(trim((string) $this->input('username')))]);
        }
        if ($this->has('email')) {
            $this->merge(['email' => strtolower(trim((string) $this->input('email')))]);
        }
    }

    public function rules(): array
    {
        /** @var \App\Models\User|null $target */
        $target = $this->route('user') ?? User::query()->find($this->route('id'));

        return [
            'name' => ['sometimes', 'string', 'max:150'],
            'email' => [
                'sometimes',
                'email',
                'max:190',
                $target ? Rule::unique('users', 'email')->ignore($target->id) : Rule::unique('users', 'email')
            ],
            'username' => [
                'sometimes',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-z0-9_.]+$/',
                $target ? Rule::unique('users', 'username')->ignore($target->id) : Rule::unique('users', 'username'),
            ],
            // opsional: kuatkan rule password
            'password' => ['nullable', Password::min(8)->mixedCase()->numbers()],
            'is_active' => ['sometimes', 'boolean'],
            'branch_id' => ['sometimes', 'nullable', 'uuid', 'exists:branches,id'],
            'role' => [
                'sometimes',
                'string',
                'max:100',
                Rule::exists('roles', 'name')->where('guard_name', 'web'),
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            /** @var \App\Models\User|null $actor */
            $actor = $this->user();
            /** @var \App\Models\User|null $target */
            $target = $this->route('user');

            if (!$actor || !$target)
                return;

            // Admin Cabang hanya boleh ubah user di cabangnya (Policy juga cek; ini double guard)
            if ($actor->hasRole('Admin Cabang') && (string) $actor->branch_id !== (string) $target->branch_id) {
                $v->errors()->add('user', 'Anda tidak berwenang mengubah user di cabang lain.');
            }

            // Admin Cabang: jika mengubah branch_id, harus tetap sama dengan cabang aktor
            if ($this->has('branch_id') && $actor->hasRole('Admin Cabang')) {
                $branchId = (string) $this->input('branch_id');
                if ($branchId !== (string) $actor->branch_id) {
                    $v->errors()->add('branch_id', 'branch_id harus sama dengan cabang Anda.');
                }
            }

            // Admin Cabang: tidak boleh set role Superadmin
            if ($this->has('role') && $actor->hasRole('Admin Cabang') && $this->input('role') === 'Superadmin') {
                $v->errors()->add('role', 'Admin Cabang tidak boleh menetapkan role Superadmin.');
            }
        });
    }
}

```
</details>

### app/Http/Requests/Vouchers/VoucherStoreRequest.php

- SHA: `92fc24dc4b14`  
- Ukuran: 2 KB  
- Namespace: `App\Http\Requests\Vouchers`

**Class `VoucherStoreRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
- **withValidator**($validator)
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests\Vouchers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VoucherStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Voucher::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('code')) {
            $this->merge(['code' => strtoupper(trim((string) $this->input('code')))]);
        }
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['nullable', 'uuid'],
            'code' => ['required', 'string', 'max:40', 'unique:vouchers,code'],
            'type' => ['required', Rule::in(['PERCENT', 'NOMINAL'])],
            'value' => ['required', 'numeric', 'min:0'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'min_total' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'active' => ['boolean'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            if ($this->input('type') === 'PERCENT') {
                $val = (float) $this->input('value', 0);
                if ($val < 0 || $val > 100) {
                    $v->errors()->add('value', 'Persentase harus 0–100.');
                }
            }
            // Admin cabang hanya boleh set branch_id == branch dirinya
            $u = $this->user();
            if ($u && method_exists($u, 'hasRole') && $u->hasRole('admin')) {
                if (!$this->filled('branch_id') || $this->input('branch_id') !== $u->branch_id) {
                    $v->errors()->add('branch_id', 'Admin cabang hanya boleh membuat voucher untuk cabangnya.');
                }
            }
        });
    }
}

```
</details>

### app/Http/Requests/Vouchers/VoucherUpdateRequest.php

- SHA: `3220aef98066`  
- Ukuran: 1 KB  
- Namespace: `App\Http\Requests\Vouchers`

**Class `VoucherUpdateRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array* — @var Voucher $voucher
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests\Vouchers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Voucher;

class VoucherUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Voucher $voucher */
        $voucher = $this->route('voucher');
        return $this->user()?->can('update', $voucher) ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('code')) {
            $this->merge(['code' => strtoupper(trim((string) $this->input('code')))]);
        }
    }

    public function rules(): array
    {
        /** @var Voucher $voucher */
        $voucher = $this->route('voucher');

        return [
            'branch_id' => ['nullable', 'uuid'],
            'code' => ['sometimes', 'string', 'max:40', Rule::unique('vouchers', 'code')->ignore($voucher?->id, 'id')],
            'type' => ['sometimes', Rule::in(['PERCENT', 'NOMINAL'])],
            'value' => ['sometimes', 'numeric', 'min:0'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'min_total' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'active' => ['boolean'],
        ];
    }
}

```
</details>

### app/Http/Requests/WashNoteStoreRequest.php

- SHA: `e5cd8c6c346f`  
- Ukuran: 4 KB  
- Namespace: `App\Http\Requests`

**Class `WashNoteStoreRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
- **withValidator**($validator)
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Order;

class WashNoteStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\WashNote::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        // Normalizer waktu: ubah "HH:MM:SS" → "HH:MM"
        $normTime = function ($t) {
            if ($t === null || $t === '') return null;
            $t = trim((string) $t);
            if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $t)) {
                return substr($t, 0, 5);
            }
            return $t; // biarkan "HH:MM" apa adanya
        };

        // note_date tetap dirapikan
        if ($this->has('note_date')) {
            $this->merge(['note_date' => trim((string) $this->input('note_date'))]);
        }

        // items: pangkas detik untuk started_at/finished_at, kosong → null
        if ($this->has('items') && is_array($this->input('items'))) {
            $items = array_map(function ($it) use ($normTime) {
                $it = (array) $it;

                // Kosong "" → null untuk field tertentu
                foreach (['started_at', 'finished_at', 'note', 'process_status'] as $k) {
                    if (array_key_exists($k, $it) && $it[$k] === '') {
                        $it[$k] = null;
                    }
                }

                // Normalisasi format waktu agar sesuai rule date_format:H:i
                if (array_key_exists('started_at', $it)) {
                    $it['started_at'] = $normTime($it['started_at']);
                }
                if (array_key_exists('finished_at', $it)) {
                    $it['finished_at'] = $normTime($it['finished_at']);
                }

                // Rapikan order_id bila ada
                if (array_key_exists('order_id', $it) && $it['order_id'] !== null) {
                    $it['order_id'] = trim((string) $it['order_id']);
                }

                return $it;
            }, $this->input('items', []));

            $this->merge(['items' => $items]);
        }
    }


    public function rules(): array
    {
        $me = $this->user();

        return [
            'note_date' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],

            'items.*.order_id' => [
                'required',
                'uuid',
                // Default: semua order harus dari cabang user (selaras constraint proyek).
                // KECUALI Petugas Cuci: boleh lintas cabang (mengikuti kebutuhan operasional pusat).
                Rule::exists('orders', 'id')->where(function ($q) use ($me) {
                    // Superadmin & Petugas Cuci: tanpa filter cabang
                    if ($me->hasRole('Superadmin') || $me->hasRole('Petugas Cuci')) {
                        return $q;
                    }
                    // Selain itu: batasi ke cabang user bila ada
                    if ($me->branch_id) {
                        return $q->where('branch_id', $me->branch_id);
                    }
                    return $q;
                }),
            ],
            'items.*.qty' => ['nullable', 'numeric', 'min:0'],
            'items.*.process_status' => ['nullable', 'string', 'in:QUEUE,WASH,DRY,FINISHING,COMPLETED,PICKED_UP'], // refer SOP
            'items.*.started_at' => ['nullable', 'date_format:H:i'],
            'items.*.finished_at' => ['nullable', 'date_format:H:i'],
            'items.*.note' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $items = (array) $this->input('items', []);
            foreach ($items as $idx => $it) {
                $s = $it['started_at'] ?? null;
                $f = $it['finished_at'] ?? null;
                if ($s && $f && is_string($s) && is_string($f) && $f < $s) {
                    $v->errors()->add("items.$idx.finished_at", "Jam selesai harus ≥ jam mulai.");
                }
            }
        });
    }
}

```
</details>

### app/Http/Requests/WashNoteUpdateRequest.php

- SHA: `85a22f801f5d`  
- Ukuran: 867 B  
- Namespace: `App\Http\Requests`

**Class `WashNoteUpdateRequest` extends `WashNoteStoreRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array* — PATCH: items tidak wajib dikirim. Aturan lain tetap mewarisi dari StoreRequest,
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

class WashNoteUpdateRequest extends WashNoteStoreRequest
{
    public function authorize(): bool
    {
        $note = $this->route('wash_note'); // route-model binding
        return $this->user()?->can('update', $note) ?? false;
    }

    /**
     * PATCH: items tidak wajib dikirim. Aturan lain tetap mewarisi dari StoreRequest,
     * termasuk pengecualian Superadmin/Petugas Cuci dan validasi jam mulai/selesai.
     */
    public function rules(): array
    {
        $rules = parent::rules();

        // Jadikan items opsional saat update (tanpa min:1)
        $rules['items'] = ['sometimes', 'array'];
        // Nested fields hanya divalidasi jika items dikirim
        $rules['items.*.order_id'][0] = 'required_with:items';
        
        $rules['note_date'] = ['sometimes', 'date'];

        return $rules;
    }
}

```
</details>



## Services (app/Services)

### app/Services/AuthService.php

- SHA: `102127084776`  
- Ukuran: 2 KB  
- Namespace: `App\Services`

**Class `AuthService`**

Metode Publik:
- **login**(string $login, string $password) : *array*
- **me**(User $user) : *array*
- **logout**(User $user) : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(string $login, string $password): array
    {
        $login = trim($login);
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL) !== false;
        $query = User::query();
        if ($isEmail) {
            $query->where('email', strtolower($login));
        } else {
            $query->where('username', strtolower($login));
        }
        $user = $query->first();

        if (!$user || !Hash::check($password, (string) $user->password)) {
            return ['ok' => false, 'status' => 401, 'message' => 'Invalid credentials.'];
        }

        if ((bool) ($user->is_active ?? true) === false) {
            return ['ok' => false, 'status' => 403, 'message' => 'Account is inactive.'];
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'ok' => true,
            'status' => 200,
            'user' => $user->loadMissing('roles'),
            'token' => $token,
        ];
    }

    public function me(User $user): array
    {
        return $this->presentUser($user);
    }

    private function presentUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'branch_id' => $user->branch_id,
            'is_active' => (bool) $user->is_active,
            'roles' => $user->getRoleNames()->values(),
        ];
    }

    public function logout(User $user): array
    {
        $token = request()->user()?->currentAccessToken();
        if ($token) {
            $token->delete();
        }
        return ['ok' => true, 'status' => 200, 'message' => 'Logged out.'];
    }
}

```
</details>

### app/Services/DashboardService.php

- SHA: `56f00aba7078`  
- Ukuran: 6 KB  
- Namespace: `App\Services`

**Class `DashboardService`**

Metode Publik:
- **summary**(Carbon $from, Carbon $to, ?string $branchId) : *array* — @param Carbon $from  awal hari (inklusif)
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * @param Carbon $from  awal hari (inklusif)
     * @param Carbon $to    akhir hari (inklusif)
     * @param string|null $branchId
     * @return array<string,mixed>
     */
    public function summary(Carbon $from, Carbon $to, ?string $branchId): array
    {
        // === OMZET (basis kas) ===
        $omzetTotal = (float) DB::table('payments')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('payments.paid_at', [$from, $to])
            ->sum('payments.amount');

        // === TRANSAKSI (jumlah order dibuat) ===
        $ordersCount = (int) DB::table('orders')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('orders.created_at', [$from, $to])
            ->count();

        // === ONGKIR (jumlah fee pengantaran) ===
        $shippingFee = (float) DB::table('deliveries')
            ->join('orders', 'orders.id', '=', 'deliveries.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('deliveries.created_at', [$from, $to])
            ->sum('deliveries.fee');

        // === VOUCHER (penggunaan & nilai) ===
        $voucherAgg = DB::table('order_vouchers')
            ->join('orders', 'orders.id', '=', 'order_vouchers.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('order_vouchers.applied_at', [$from, $to])
            ->selectRaw('COUNT(DISTINCT order_vouchers.order_id) AS used_count,
                         COALESCE(SUM(order_vouchers.applied_amount),0) AS used_amount')
            ->first();

        $vouchersUsedCount  = (int) ($voucherAgg->used_count  ?? 0);
        $vouchersUsedAmount = (float) ($voucherAgg->used_amount ?? 0);

        // === PIUTANG (outstanding & overdue) ===
        $now = now();
        $recv = DB::table('receivables')
            ->join('orders', 'orders.id', '=', 'receivables.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereIn('receivables.status', ['OPEN', 'PARTIAL', 'OVERDUE'])
            ->selectRaw("
                COALESCE(SUM(receivables.remaining_amount),0) AS remaining_amount,
                COUNT(*) AS open_count,
                COALESCE(SUM(CASE WHEN receivables.due_date < ? THEN receivables.remaining_amount ELSE 0 END),0) AS overdue_amount,
                SUM(CASE WHEN receivables.due_date < ? THEN 1 ELSE 0 END) AS overdue_count
            ", [$now, $now])
            ->first();

        $receivablesOpenAmount = (float) ($recv->remaining_amount ?? 0);
        $receivablesOpenCount  = (int)   ($recv->open_count       ?? 0);
        $overdueAmount         = (float) ($recv->overdue_amount   ?? 0);
        $overdueCount          = (int)   ($recv->overdue_count    ?? 0);

        // === DP Outstanding (diletakkan di root KPI) ===
        $dp = DB::table('receivables')
            ->join('orders', 'orders.id', '=', 'receivables.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereIn('receivables.status', ['OPEN', 'PARTIAL'])
            ->selectRaw('COUNT(*) AS cnt, COALESCE(SUM(receivables.remaining_amount),0) AS amt')
            ->first();

        $dpOutstandingCount  = (int)   ($dp->cnt ?? 0);
        $dpOutstandingAmount = (float) ($dp->amt ?? 0);

        // === TOP LAYANAN (Top 5 by omzet dalam window order dibuat) ===
        $topServices = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('services', 'services.id', '=', 'order_items.service_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('orders.created_at', [$from, $to])
            ->groupBy('order_items.service_id', 'services.name')
            ->selectRaw('order_items.service_id, services.name, SUM(order_items.qty) AS qty, SUM(order_items.total) AS amount')
            ->orderByDesc('amount')
            ->limit(5)
            ->get();

        // === OMZET HARIAN (time-series untuk grafik) ===
        $daily = DB::table('payments')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('payments.paid_at', [$from, $to])
            ->selectRaw("date_trunc('day', payments.paid_at)::date AS d, SUM(payments.amount) AS sum")
            ->groupBy('d')
            ->orderBy('d')
            ->get()
            ->map(fn($r) => ['date' => (string) $r->d, 'amount' => (float) $r->sum])
            ->all();

        // === OMZET BULANAN (time-series untuk grafik) ===
        $monthly = DB::table('payments')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('payments.paid_at', [$from, $to])
            ->selectRaw("to_char(date_trunc('month', payments.paid_at), 'YYYY-MM') AS m, SUM(payments.amount) AS sum")
            ->groupBy('m')
            ->orderBy('m')
            ->get()
            ->map(fn($r) => ['month' => (string) $r->m, 'amount' => (float) $r->sum])
            ->all();

        // === Payload yang DIHARAPKAN Frontend (flatten + time-series) ===
        return [
            'omzet_total' => $omzetTotal,
            'orders_count' => $ordersCount,

            'delivery_shipping_fee' => $shippingFee,

            'vouchers_used_count'  => $vouchersUsedCount,
            'vouchers_used_amount' => $vouchersUsedAmount,

            'receivables_open_count'  => $receivablesOpenCount,
            'receivables_open_amount' => $receivablesOpenAmount,
            'overdue_count'           => $overdueCount,
            'overdue_amount'          => $overdueAmount,

            'dp_outstanding_count'  => $dpOutstandingCount,
            'dp_outstanding_amount' => $dpOutstandingAmount,

            'omzet_daily'   => $daily,    // [{ date: 'YYYY-MM-DD', amount: number }, ...]
            'omzet_monthly' => $monthly,  // [{ month: 'YYYY-MM', amount: number }, ...]
            'top_services'  => $topServices,
        ];
    }
}

```
</details>

### app/Services/DeliveryService.php

- SHA: `94af194c9457`  
- Ukuran: 5 KB  
- Namespace: `App\Services`

**Class `DeliveryService`**

Metode Publik:
- **create**(Order $order, array $data, User $actor) : *Delivery*
- **autoAssign**(string $orderId) : *array* — @var Delivery $d
- **assignManual**(Delivery $delivery, int $courierId, User $actor) : *Delivery* — @var Delivery $d
- **updateStatus**(Delivery $delivery, string $status, ?UploadedFile $photo, ?string $note, User $actor) : *Delivery* — @var Delivery $d
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Services;

use App\Models\{Delivery, DeliveryEvent, Order, User};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeliveryService
{
    private const TERMINAL = ['COMPLETED', 'FAILED', 'CANCELLED'];

    public function create(Order $order, array $data, User $actor): Delivery
    {
        return DB::transaction(function () use ($order, $data) {
            /** @var Delivery $d */
            $d = new Delivery([
                'order_id' => $order->getKey(),
                'type' => (string) $data['type'],
                'zone_id' => $data['zone_id'] ?? null,
                'fee' => (float) ($data['fee'] ?? 0),
                'status' => 'CREATED',
                'auto_assigned' => false,
            ]);
            $d->save();

            DeliveryEvent::query()->create([
                'id' => (string) Str::uuid(),
                'delivery_id' => $d->getKey(),
                'status' => 'CREATED',
                'note' => 'Delivery created',
            ]);

            return $d->fresh(['order', 'courier', 'events']);
        });
    }

    public function autoAssign(string $orderId): array
    {
        return DB::transaction(function () use ($orderId) {
            /** @var Delivery $delivery */
            $delivery = Delivery::query()->where('order_id', $orderId)->latest('created_at')->firstOrFail();
            $order = $delivery->order()->firstOrFail();

            if ($delivery->assigned_to && !in_array($delivery->status, self::TERMINAL, true)) {
                return ['delivery' => $delivery->fresh(['courier']), 'idempotent' => true];
            }

            $pool = User::role('Kurir')
                ->where('is_active', 1)
                ->where('branch_id', $order->branch_id)
                ->get(['id']);

            if ($pool->isEmpty()) {
                return ['delivery' => $delivery, 'idempotent' => true];
            }

            $counts = DB::table('deliveries')
                ->select('assigned_to', DB::raw('COUNT(*) as total'))
                ->whereNotIn('status', self::TERMINAL)
                ->whereIn('assigned_to', $pool->pluck('id')->all())
                ->groupBy('assigned_to')
                ->pluck('total', 'assigned_to');

            // pilih kurir dengan beban paling kecil; jika tie → id terkecil
            $chosen = $pool
                ->map(fn($u) => ['id' => (int) $u->id, 'load' => (int) ($counts[$u->id] ?? 0)])
                ->sortBy(['load', 'id'])
                ->first();

            $delivery->forceFill([
                'assigned_to' => $chosen['id'],
                'auto_assigned' => true,
                'status' => 'ASSIGNED',
            ])->save();

            DeliveryEvent::query()->create([
                'id' => (string) Str::uuid(),
                'delivery_id' => $delivery->getKey(),
                'status' => 'ASSIGNED',
                'note' => 'Auto-assigned courier #' . $chosen['id'],
            ]);

            return ['delivery' => $delivery->fresh(['courier']), 'idempotent' => false];
        });
    }

    public function assignManual(Delivery $delivery, int $courierId, User $actor): Delivery
    {
        return DB::transaction(function () use ($delivery, $courierId) {
            $delivery->forceFill([
                'assigned_to' => $courierId,
                'auto_assigned' => false,
                'status' => 'ASSIGNED',
            ])->save();

            DeliveryEvent::query()->create([
                'id' => (string) Str::uuid(),
                'delivery_id' => $delivery->getKey(),
                'status' => 'ASSIGNED',
                'note' => 'Manually assigned courier #' . $courierId,
            ]);

            return $delivery->fresh(['courier']);
        });
    }

    public function updateStatus(Delivery $delivery, string $status, ?UploadedFile $photo, ?string $note, User $actor): Delivery
    {
        return DB::transaction(function () use ($delivery, $status, $photo, $note) {
            $delivery->forceFill(['status' => $status])->save();

            if ($photo && $status === 'HANDOVER') {
                $dir = "uploads/deliveries/{$delivery->getKey()}/{$status}";
                $p = $photo->store($dir, 'public');
                $delivery->forceFill(['handover_photo' => "storage/{$p}"])->save();
            }

            DeliveryEvent::query()->create([
                'id' => (string) Str::uuid(),
                'delivery_id' => $delivery->getKey(),
                'status' => $status,
                'note' => $note,
            ]);

            return $delivery->fresh(['courier', 'events']);
        });
    }
}

```
</details>

### app/Services/InvoiceNumberService.php

- SHA: `da39a3ee5e6b`  
- Ukuran: 0 B  
- Namespace: ``
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php

```
</details>

### app/Services/InvoiceService.php

- SHA: `74136cd3556a`  
- Ukuran: 4 KB  
- Namespace: `App\Services`

**Class `InvoiceService`**

Metode Publik:
- **generate**(string $branchId) : *string* — Generate nomor faktur format: {PREFIX}-{YYYYMM}-{SEQ6}
- **generatePair**(string $branchId, ?Carbon $now = null) : *array* — Generate nomor faktur format: {PREFIX}-{YYYYMM}-{SEQ6}
- **preview**(string $branchId, ?Carbon $now = null) : *array* — Generate nomor faktur format: {PREFIX}-{YYYYMM}-{SEQ6}
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\InvoiceCounter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InvoiceService
{
    /**
     * Generate nomor faktur format: {PREFIX}-{YYYYMM}-{SEQ6}
     * Reset bulanan jika reset_policy = 'monthly'.
     */
    public function generate(string $branchId): string
    {
        $ids = $this->generatePair($branchId);
        return $ids['number'];
    }

    /**
     * Generate dua nomor sekaligus dalam satu transaksi DB:
     * - number     : {PREFIX}-{YYYYMM}-{SEQ6}  (tetap untuk kompatibilitas)
     * - invoice_no : INV-{DD}-{MM}-{####}      (untuk ditampilkan ke user/struk)
     */
    public function generatePair(string $branchId, ?Carbon $now = null): array
    {
        // Pastikan branch ada
        $branch = Branch::query()->find($branchId);
        if (!$branch) {
            throw new ModelNotFoundException('Branch not found');
        }

        $now = $now ?: Carbon::now('Asia/Jakarta');
        $nowYm = $now->format('Ym'); // contoh: 202511
        $dd = $now->format('d');  // 25
        $mm = $now->format('m');  // 11
        $prefix = $branch->invoice_prefix ?? 'SLV';

        return DB::transaction(function () use ($branch, $prefix, $nowYm, $dd, $mm) {
            // Lock row counter by (branch_id, prefix)
            $counter = InvoiceCounter::query()
                ->where('branch_id', $branch->id)
                ->where('prefix', $prefix)
                ->lockForUpdate()
                ->first();

            if (!$counter) {
                $counter = new InvoiceCounter([
                    'branch_id' => $branch->id,
                    'prefix' => $prefix,
                    'seq' => 0,
                    'reset_policy' => $branch->reset_policy ?? 'monthly',
                    'last_reset_month' => null,
                ]);
                $counter->save();
                $counter->refresh();
            }

            // Reset jika perlu (monthly)
            if ($counter->reset_policy === 'monthly') {
                if ($counter->last_reset_month !== $nowYm) {
                    $counter->seq = 0;
                    $counter->last_reset_month = $nowYm;
                }
            }

            // Naikkan sequence
            $counter->seq = (int) $counter->seq + 1;
            $counter->save();

            // number: PREFIX-YYYYMM-SEQ6
            $seq6 = str_pad((string) $counter->seq, 6, '0', STR_PAD_LEFT);
            $number = "{$counter->prefix}-{$nowYm}-{$seq6}";

            // invoice_no: INV-DD-MM-#### (gunakan 4 digit terakhir seq)
            $seq4 = substr(str_pad((string) $counter->seq, 4, '0', STR_PAD_LEFT), -4);
            $invoiceNo = "INV-{$dd}-{$mm}-{$seq4}";

            return ['number' => $number, 'invoice_no' => $invoiceNo];
        });
    }

    public function preview(string $branchId, ?Carbon $now = null): array
    {
        $now ??= now('Asia/Jakarta');
        $dd = $now->format('d');
        $mm = $now->format('m');
        $ym = $now->format('Ym');

        /** @var Branch $branch */
        $branch = Branch::query()->findOrFail($branchId);
        $prefix = $branch->invoice_prefix ?? 'SLV';

        /** @var InvoiceCounter|null $counter */
        $counter = InvoiceCounter::query()
            ->where('branch_id', $branch->id)
            ->where('prefix', $prefix)
            ->first();

        $seq = $counter?->seq ?? 0;

        // Jika kebijakan reset bulanan aktif dan bulan terakhir berbeda, maka preview mulai dari 0 lagi
        if (($counter?->reset_policy ?? $branch->reset_policy ?? 'monthly') === 'monthly') {
            if (($counter?->last_reset_month) !== $ym) {
                $seq = 0;
            }
        }

        $next = $seq + 1;
        $seq6 = str_pad((string) $next, 6, '0', STR_PAD_LEFT);
        $seq4 = substr(str_pad((string) $next, 4, '0', STR_PAD_LEFT), -4);

        return [
            'number' => "{$prefix}-{$ym}-{$seq6}",
            'invoice_no' => "INV-{$dd}-{$mm}-{$seq4}",
        ];
    }
}

```
</details>

### app/Services/LoyaltyService.php

- SHA: `73dcead351ef`  
- Ukuran: 5 KB  
- Namespace: `App\Services`
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Services;

use App\Models\LoyaltyAccount;
use App\Models\LoyaltyLog;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class LoyaltyService
{
    public const CYCLE = 10;

    public function getOrCreateAccount(string $customerId, string $branchId): LoyaltyAccount
    {
        return DB::transaction(function () use ($customerId, $branchId) {
            $acc = LoyaltyAccount::query()
                ->where('customer_id', $customerId)
                ->where('branch_id', $branchId)
                ->lockForUpdate()
                ->first();

            if (!$acc) {
                $acc = LoyaltyAccount::create([
                    'id' => (string) Str::uuid(),
                    'customer_id' => $customerId,
                    'branch_id' => $branchId,
                    'stamps' => 0,
                    'lifetime' => 0,
                ]);
            }
            return $acc;
        });
    }

    /** Hitung reward untuk order baru (tanpa mengubah state). */
    public function previewReward(?string $customerId, string $branchId, float $subtotal): array
    {
        if (!$customerId || $subtotal <= 0) {
            return ['reward' => 'NONE', 'discount' => 0.0];
        }
        $acc = $this->getOrCreateAccount($customerId, $branchId);
        $next = ($acc->stamps % self::CYCLE) + 1; // 1..10

        if ($next === 5)  return ['reward' => 'DISC25',  'discount' => round($subtotal * 0.25, 2)];
        if ($next === 10) return ['reward' => 'FREE100', 'discount' => round($subtotal, 2)];
        return ['reward' => 'NONE', 'discount' => 0.0];
    }

    /** Terapkan loyalti pada draft order (mengisi kolom meta & perhitungan total). */
    public function applyToOrder(Order $order, string $branchId): void
    {
        $subtotal = (float) $order->getAttribute('subtotal');
        $p = $this->previewReward($order->customer_id, $branchId, $subtotal);

        $order->loyalty_reward   = $p['reward'];     // NONE|DISC25|FREE100
        $order->loyalty_discount = $p['discount'];   // angka rupiah
        $order->discount         = (float) $order->discount + $p['discount'];
        $order->grand_total      = max(0, $subtotal - (float) $order->discount);
        $order->due_amount       = max(0, (float) $order->grand_total - (float) $order->paid_amount);
    }

    /** Earn + logging saat order dinyatakan selesai (PICKED_UP). */
    public function finalizeEarn(Order $order): void
    {
        if (!$order->customer_id) return;

        DB::transaction(function () use ($order) {
            // idempotensi per order
            if (LoyaltyLog::query()->where('order_id', (string) $order->getKey())->exists()) {
                return;
            }

            $acc = $this->getOrCreateAccount((string) $order->customer_id, (string) $order->branch_id);
            $before = (int) $acc->stamps;

            // catat reward yang dipakai di order ini
            if ($order->loyalty_reward === 'DISC25') {
                LoyaltyLog::create([
                    'id' => (string) Str::uuid(),
                    'order_id' => null,
                    'customer_id' => (string) $order->customer_id,
                    'branch_id' => (string) $order->branch_id,
                    'action' => 'REWARD25',
                    'before' => $before,
                    'after' => $before,
                ]);
            } elseif ($order->loyalty_reward === 'FREE100') {
                LoyaltyLog::create([
                    'id' => (string) Str::uuid(),
                    'order_id' => null,
                    'customer_id' => (string) $order->customer_id,
                    'branch_id' => (string) $order->branch_id,
                    'action' => 'REWARD100',
                    'before' => $before,
                    'after' => $before,
                ]);
            }

            // earn +1, reset jika mencapai 10
            $afterEarn = $before + 1;     // nilai setelah earn
            $didReset  = $afterEarn >= self::CYCLE;
            $after     = $didReset ? 0 : $afterEarn;

            $acc->update(['stamps' => $after, 'lifetime' => (int) $acc->lifetime + 1]);

            // SATU-SATUNYA log yang mengikat order_id: EARN
            LoyaltyLog::create([
                'id' => (string) Str::uuid(),
                'order_id' => (string) $order->getKey(),
                'customer_id' => (string) $order->customer_id,
                'branch_id' => (string) $order->branch_id,
                'action' => 'EARN',
                'before' => $before,
                'after' => $after,
            ]);

            // Jika terjadi reset (menyentuh ke-10), catat RESET sebagai histori tambahan TANPA order_id
            if ($didReset) {
                LoyaltyLog::create([
                    'id' => (string) Str::uuid(),
                    'order_id' => null,
                    'customer_id' => (string) $order->customer_id,
                    'branch_id' => (string) $order->branch_id,
                    'action' => 'RESET',
                    'before' => $afterEarn,
                    'after' => 0,
                ]);
            }
        });
    }
}

```
</details>

### app/Services/OrderNumberService.php

- SHA: `f822f2ab3c8b`  
- Ukuran: 2 KB  
- Namespace: `App\Services`

**Class `OrderNumberService`**

Metode Publik:
- **next**(string $branchId, ?Carbon $now = null) : *string*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

// app/Services/OrderNumberService.php
namespace App\Services;

use App\Models\InvoiceCounter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class OrderNumberService
{
    public function next(string $branchId, ?Carbon $now = null): string
    {
        $now ??= now('Asia/Jakarta');
        $period = $now->format('Ym');       // 202510
        $prefix = 'SLV';                    // atau ambil dari setting

        return DB::transaction(function () use ($branchId, $period, $prefix) {
            // lock baris counter per cabang+periode
            $row = InvoiceCounter::query()
                ->where('branch_id', $branchId)
                ->where('period', $period)
                ->lockForUpdate()
                ->first();

            if (!$row) {
                // buat dulu lalu lock lagi untuk mencegah race
                $row = InvoiceCounter::create([
                    'branch_id' => $branchId,
                    'period' => $period,   // CHAR(6) 'YYYYMM'
                    'prefix' => $prefix,
                    'last_no' => 0,
                ]);

                $row = InvoiceCounter::query()
                    ->whereKey($row->getKey())
                    ->lockForUpdate()
                    ->first();
            }

            $row->last_no = (int) $row->last_no + 1;
            $row->save();

            $seq = str_pad((string) $row->last_no, 6, '0', STR_PAD_LEFT);
            // Format: SLV-202510-000123
            return "{$row->prefix}-{$row->period}-{$seq}";
        });
    }
}


```
</details>

### app/Services/OrderService.php

- SHA: `4152ab7d4bc5`  
- Ukuran: 12 KB  
- Namespace: `App\Services`

**Class `OrderService`**

Metode Publik:
- **__construct**(private PricingService $pricing, private InvoiceService $invoice, private LoyaltyService $loyalty,)
- **createDraft**(array $data, User $actor) : *Order* — Create order (draft/queue) — hitung total dan harga per cabang.
- **attachPhotos**(Order $order, array $photos) : *Order* — Create order (draft/queue) — hitung total dan harga per cabang.
- **transition**(Order $order, string $next, User $actor) : *Order* — Create order (draft/queue) — hitung total dan harga per cabang.
- **update**(Order $order, array $data, User $actor) : *Order* — Create order (draft/queue) — hitung total dan harga per cabang.
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPhoto;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Delivery;
use App\Services\DeliveryService;
use Illuminate\Support\Facades\Schema;

class OrderService
{
    public function __construct(
        private PricingService $pricing,
        private InvoiceService $invoice,
        private LoyaltyService $loyalty,
    ) {}

    /**
     * Create order (draft/queue) — hitung total dan harga per cabang.
     * @param array{
     *   branch_id?:string|null,
     *   customer_id?:string|null,
     *   notes?:string|null,
     *   received_at?:string|\DateTimeInterface|null,
     *   ready_at?:string|\DateTimeInterface|null,
     *   items: array<int, array{service_id:string, qty:float|int, note?:string|null}>
     * } $data
     */
    public function createDraft(array $data, User $actor): Order
    {
        $branchId = (string) ($data['branch_id'] ?? $actor->branch_id);

        return DB::transaction(function () use ($data, $actor, $branchId) {
            // Generate dua nomor sekaligus (number & invoice_no)
            $ids = $this->invoice->generatePair($branchId);
            $number = $ids['number'];

            $order = new Order([
                'id' => (string) Str::uuid(),
                'branch_id' => $branchId,
                'customer_id' => $data['customer_id'] ?? null,
                'number' => $ids['number'],
                'invoice_no' => $ids['invoice_no'],
                'status' => 'QUEUE',
                'subtotal' => $this->dec(0),
                'discount' => $this->dec(0),
                'grand_total' => $this->dec(0),
                'paid_amount' => $this->dec(0),
                'due_amount' => $this->dec(0),
                'notes' => $data['notes'] ?? null,
                'created_by' => $actor->id,
            ]);
            $order->received_at = $data['received_at'] ?? now(); // default: sekarang
            $order->ready_at    = $data['ready_at']    ?? null;
            $order->save();

            $subtotal = 0.0;

            foreach ($data['items'] as $row) {
                $price = (float) $this->pricing->getPrice($row['service_id'], $branchId);
                $qty = (float) $row['qty'];
                $line = $price * $qty;
                $subtotal += $line;

                OrderItem::query()->create([
                    'id' => (string) Str::uuid(),
                    'order_id' => $order->id,
                    'service_id' => $row['service_id'],
                    'qty' => $this->dec($qty),
                    'price' => $this->dec($price),
                    'total' => $this->dec($line),
                    'note' => $row['note'] ?? null,
                ]);
            }

            $order->subtotal = $this->dec($subtotal);
            $order->discount = $this->dec(0);
            $this->loyalty->applyToOrder($order, $branchId);
            $order->save();

            $this->loyalty->finalizeEarn($order);

            if (Schema::hasTable('receivables')) {
                $grand = (float) $order->getAttribute('grand_total');
                if ($grand > 0) {
                    $existing = DB::table('receivables')
                        ->where('order_id', (string) $order->getKey())
                        ->first();

                    if (!$existing) {
                        DB::table('receivables')->insert([
                            'id' => (string) Str::uuid(),
                            'order_id' => (string) $order->getKey(),
                            'remaining_amount' => $grand,
                            'status' => 'OPEN',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            return $order->load(['items.service', 'customer']);
        });
    }

    /**
     * Lampirkan foto before/after ke order.
     * @param array{kind:'before'|'after', path:string}[] $photos
     */
    public function attachPhotos(Order $order, array $photos): Order
    {
        DB::transaction(function () use ($order, $photos) {
            foreach ($photos as $p) {
                OrderPhoto::query()->create([
                    'id' => (string) Str::uuid(),
                    'order_id' => $order->id,
                    'kind' => $p['kind'],
                    'path' => $p['path'],
                ]);
            }
            // TODO: audit('ORDER_ATTACH_PHOTO', ['order_id' => $order->id, 'count' => count($photos)]);
        });

        return $order->load('photos');
    }

    /**
     * Transisi status: validasi transisi legal & simpan.
     */
    public function transition(Order $order, string $next, User $actor): Order
    {
        $allowed = $this->allowedNext($order->status);
        if (!in_array($next, $allowed, true)) {
            abort(422, 'Invalid status transition');
        }

        DB::transaction(function () use ($order, $next, $actor) {
            $from = $order->status;
            $order->status = $next;
            // ===== Tambahan: set otomatis tgl selesai ketika READY =====
            if ($next === 'READY' && !$order->ready_at) {
                $order->ready_at = now();
            }
            // ===========================================================
            $order->save();

            if ($next === 'DELIVERING') {
                $exists = Delivery::query()
                    ->where('order_id', $order->id)
                    ->exists();

                if (!$exists) {
                    app(DeliveryService::class)->create(
                        $order,
                        [
                            'type' => 'delivery',
                            'zone_id' => null,
                            'fee' => 0,
                        ],
                        $actor
                    );
                }

                // Auto-assign kurir (round-robin per cabang) — method ini memang ada
                app(DeliveryService::class)->autoAssign($order->id);
            }

            // TODO: audit('ORDER_STATUS', ['order_id' => $order->id, 'from' => $from, 'to' => $next, 'actor' => $actor->id]);
        });

        return $order;
    }

    /**
     * Update isi order (re-hitung total).
     * @param array{
     *   customer_id?:string|null,
     *   notes?:string|null,
     *   discount?:float|int|null,
     *   items?: array<int, array{id?:string, service_id:string, qty:float|int, note?:string|null}>
     * } $data
     */
    public function update(Order $order, array $data, User $actor): Order
    {
        return DB::transaction(function () use ($order, $data, $actor) {
            // pastikan state fresh & terkunci selama perhitungan
            $order->refresh();
            if (in_array($order->status, ['DELIVERING', 'PICKED_UP', 'CANCELED'], true)) {
                abort(403, 'Order pada status ini terkunci dan tidak dapat diedit.');
            }

            if (array_key_exists('customer_id', $data)) {
                $order->customer_id = $data['customer_id'];
            }
            if (array_key_exists('notes', $data)) {
                $order->notes = $data['notes'];
            }

            if (array_key_exists('discount', $data)) {
                // normalisasi di Request; di sini cukup set
                $order->discount = $this->dec((float) max(0, (float) $data['discount']));
            }

            // ===== Tambahan: tanggal masuk & tanggal selesai =====
            if (array_key_exists('received_at', $data)) {
                $order->received_at = $data['received_at'];
            }
            if (array_key_exists('ready_at', $data)) {
                $order->ready_at = $data['ready_at'];
            }
            // =====================================================


            $recalcSubtotal = null;
            if (!empty($data['items'])) {
                // strategi sederhana: hapus & tulis ulang
                $order->items()->delete();

                $subtotal = 0.0;
                foreach ($data['items'] as $row) {
                    $price = (float) $this->pricing->getPrice($row['service_id'], $order->branch_id);
                    $qty   = (float) $row['qty'];
                    $line  = $price * $qty;
                    $subtotal += $line;

                    OrderItem::query()->create([
                        'id'        => (string) Str::uuid(),
                        'order_id'  => $order->id,
                        'service_id' => $row['service_id'],
                        'qty'       => $this->dec($qty),
                        'price'     => $this->dec($price),
                        'total'     => $this->dec($line),
                        'note'      => $row['note'] ?? null,
                    ]);
                }
                $recalcSubtotal = $subtotal;
            }

            $effectiveSubtotal = $recalcSubtotal !== null ? $recalcSubtotal : (float) $order->subtotal;
            $baseDiscount      = (float) max(0, (float) $order->discount);

            // Re-preview loyalti berdasarkan subtotal terbaru
            $preview = $this->loyalty->previewReward($order->customer_id, (string) $order->branch_id, $effectiveSubtotal);
            $order->loyalty_reward   = $preview['reward'];
            $order->loyalty_discount = $this->dec($preview['discount']);
            $effectiveDiscount       = $baseDiscount + (float) $preview['discount'];

            $grand = max(0, $effectiveSubtotal - $effectiveDiscount);
            $due   = max(0, $grand - (float) $order->paid_amount);

            $order->subtotal    = $this->dec($effectiveSubtotal);
            $order->discount    = $this->dec($effectiveDiscount);
            $order->grand_total = $this->dec($grand);
            $order->due_amount  = $this->dec($due);

            $order->save();

            if (Schema::hasTable('receivables')) {
                $existing = DB::table('receivables')
                    ->where('order_id', (string) $order->getKey())
                    ->first();

                if ($grand <= 0.0) {
                    if ($existing) {
                        DB::table('receivables')
                            ->where('id', $existing->id)
                            ->update([
                                'remaining_amount' => 0,
                                'status' => 'SETTLED',
                                'updated_at' => now(),
                            ]);
                    }
                } else {
                    if ($existing) {
                        DB::table('receivables')
                            ->where('id', $existing->id)
                            ->update([
                                'remaining_amount' => $due,
                                'status' => $due <= 0 ? 'SETTLED' : ($due < $grand ? 'PARTIAL' : 'OPEN'),
                                'updated_at' => now(),
                            ]);
                    } else {
                        // jika sebelumnya belum ada, buat baru saat kini grand_total > 0
                        DB::table('receivables')->insert([
                            'id' => (string) Str::uuid(),
                            'order_id' => (string) $order->getKey(),
                            'remaining_amount' => $due,
                            'status' => $due <= 0 ? 'SETTLED' : 'OPEN',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            // TODO: audit('ORDER_UPDATE', ['order_id' => $order->id, 'actor' => $actor->id]);

            return $order->load(['items.service', 'customer', 'receivable']);
        });
    }

    /**
     * Format angka menjadi string desimal dengan presisi tetap (default 2).
     */
    private function dec(float|int|string|null $n, int $scale = 2): string
    {
        $v = is_numeric($n) ? (float) $n : 0.0;
        return number_format($v, $scale, '.', '');
    }

    /**
     * State machine sederhana.
     * @return array<int,string>
     */
    private function allowedNext(string $current): array
    {
        return match ($current) {
            'QUEUE' => ['WASHING', 'CANCELED'],
            'WASHING' => ['DRYING', 'CANCELED'],
            'DRYING' => ['IRONING', 'READY', 'CANCELED'],
            'IRONING' => ['READY', 'CANCELED'],
            'READY' => ['DELIVERING', 'PICKED_UP', 'CANCELED'],
            'DELIVERING' => ['PICKED_UP', 'CANCELED'],
            default => [],
        };
    }
}

```
</details>

### app/Services/PaymentService.php

- SHA: `4c5ee7cba903`  
- Ukuran: 4 KB  
- Namespace: `App\Services`

**Class `PaymentService`**

Metode Publik:
- **apply**(Order $order, string $method, float $amount, ?string $paidAt = null, ?string $note = null) : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Order;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class PaymentService
{
    public function apply(Order $order, string $method, float $amount, ?string $paidAt = null, ?string $note = null): array
    {
        return DB::transaction(function () use ($order, $method, $amount, $paidAt, $note) {
            $orderId = (string) $order->getKey();

            // [BARU] Normalisasi paid_at ke format SQL (YYYY-mm-dd HH:ii:ss)
            $paidAtDb = $paidAt ? Carbon::parse($paidAt)->format('Y-m-d H:i:s') : null;

            // 1) Idempotency check (kombinasi unik)
            $exists = DB::table('payments')->where([
                'order_id' => $orderId,
                'method' => $method,
                'amount' => $amount,
            ])
                // ganti $paidAt -> $paidAtDb
                ->when($paidAtDb, fn($q) => $q->where('paid_at', $paidAtDb))
                ->exists();

            if ($exists) {
                return ['ok' => true, 'order' => $order->fresh(), 'payment' => null, 'idempotent' => true];
            }

            // 2) Create payment row
            $paymentId = (string) Str::uuid();
            DB::table('payments')->insert([
                'id' => $paymentId,
                'order_id' => $orderId,
                'method' => $method,
                'amount' => $amount,
                // ganti $paidAt -> $paidAtDb
                'paid_at' => $paidAtDb,
                'note' => $note,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3) Update order payment aggregates
            $paidAmount = (float) $order->getAttribute('paid_amount') + $amount;
            $grand = (float) $order->getAttribute('grand_total');

            $paymentStatus = 'PENDING';
            if ($method === 'DP' || $paidAmount < $grand) {
                $paymentStatus = 'DP';
            }
            if ($paidAmount >= $grand && $grand > 0) {
                $paymentStatus = 'PAID';
            }

            $newDp = (float) $order->getAttribute('dp_amount');
            if ($method === 'DP') {
                $newDp += $amount;
            }

            $order->forceFill([
                'paid_amount' => $paidAmount,
                'dp_amount' => $newDp,
                'payment_status' => $paymentStatus,
                // ganti $paidAt -> $paidAtDb
                'paid_at' => ($paymentStatus === 'PAID' && !$order->getAttribute('paid_at'))
                    ? ($paidAtDb ?: now())
                    : $order->getAttribute('paid_at'),
                'due_amount' => max($grand - $paidAmount, 0),
            ])->save();

            // 4) Receivables upsert (tetap sama) ...
            // (biarkan bagian receivables apa adanya)
            $remaining = max($grand - $paidAmount, 0);
            if (Schema::hasTable('receivables')) {
                $row = DB::table('receivables')->where('order_id', $orderId)->first();
                if (!$row && $remaining > 0) {
                    DB::table('receivables')->insert([
                        'id' => (string) Str::uuid(),
                        'order_id' => $orderId,
                        'remaining_amount' => $remaining,
                        'status' => $remaining >= $grand ? 'OPEN' : 'PARTIAL',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    DB::table('receivables')->where('order_id', $orderId)->update([
                        'remaining_amount' => $remaining,
                        'status' => $remaining > 0 ? 'PARTIAL' : 'SETTLED',
                        'updated_at' => now(),
                    ]);
                }
            }

            $payment = DB::table('payments')->where('id', $paymentId)->first();

            return ['ok' => true, 'order' => $order->fresh(['items']), 'payment' => $payment, 'idempotent' => false];
        });
    }
}

```
</details>

### app/Services/PricingService.php

- SHA: `99964ed09888`  
- Ukuran: 804 B  
- Namespace: `App\Services`

**Class `PricingService`**

Metode Publik:
- **getPrice**(string $serviceId, string $branchId) : *string* — Ambil harga layanan untuk cabang tertentu.
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Services;

use App\Models\Service;
use App\Models\ServicePrice;

class PricingService
{
    /**
     * Ambil harga layanan untuk cabang tertentu.
     * Urutan: service_prices(price) → fallback services.price_default.
     * Return string decimal 2 digit.
     */
    public function getPrice(string $serviceId, string $branchId): string
    {
        $override = ServicePrice::query()
            ->where('service_id', $serviceId)
            ->where('branch_id', $branchId)
            ->value('price');

        if ($override !== null) {
            return number_format((float) $override, 2, '.', '');
        }

        $default = Service::query()->where('id', $serviceId)->value('price_default') ?? '0.00';
        return number_format((float) $default, 2, '.', '');
    }
}

```
</details>

### app/Services/ReceivableService.php

- SHA: `082890134330`  
- Ukuran: 2 KB  
- Namespace: `App\Services`

**Class `ReceivableService`**

Metode Publik:
- **createForDP**(Order $order, ?Carbon $dueDate = null) : *Receivable* — Dipanggil saat DP dibuat.
- **settle**(Order $order, string $method, float $amount, ?Carbon $paidAt = null, ?string $note = null) : *array* — Dipanggil saat DP dibuat.
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Receivable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ReceivableService
{
    /**
     * Dipanggil saat DP dibuat.
     * Jika belum ada receivable, buat; jika sudah, sinkron sisa dari order.
     */
    public function createForDP(Order $order, ?Carbon $dueDate = null): Receivable
    {
        return DB::transaction(function () use ($order, $dueDate) {
            $rcv = Receivable::query()->firstOrNew(['order_id' => $order->id]);
            $rcv->due_date = $dueDate;
            $rcv->remaining_amount = (float) $order->grand_total - (float) $order->paid_amount;
            $rcv->status = $rcv->remaining_amount <= 0.000001 ? 'SETTLED' : ($rcv->remaining_amount < (float) $order->grand_total ? 'PARTIAL' : 'OPEN');
            $rcv->save();

            return $rcv->refresh();
        });
    }

    /**
     * Pelunasan: delegasikan ke PaymentService agar audit & idempoten seragam.
     * PaymentService sudah meng-update order & upsert receivable sesuai sisa.
     */
    public function settle(Order $order, string $method, float $amount, ?Carbon $paidAt = null, ?string $note = null): array
    {
        return DB::transaction(function () use ($order, $method, $amount, $paidAt, $note) {
            /** @var \App\Services\PaymentService $pay */
            $pay = app(\App\Services\PaymentService::class);

            $resultOrder = $pay->apply($order, $method, $amount, $paidAt, $note);

            $rcv = Receivable::query()->where('order_id', $order->id)->first();

            return ['order' => $resultOrder, 'receivable' => $rcv];
        });
    }
}

```
</details>

### app/Services/ReportService.php

- SHA: `f82a5e138459`  
- Ukuran: 6 KB  
- Namespace: `App\Services`

**Class `ReportService`**

Metode Publik:
- **buildServiceItemsQuery**(Carbon $from, Carbon $to, ?string $branchId)
- **buildSalesQuery**(Carbon $from, Carbon $to, ?string $branchId, ?string $method = null) — SALES (basis kas) – window: payments.paid_at
- **buildOrdersQuery**(Carbon $from, Carbon $to, ?string $branchId, ?string $status = null) — SALES (basis kas) – window: payments.paid_at
- **buildReceivablesQuery**(Carbon $from, Carbon $to, ?string $branchId, ?string $status = null) — SALES (basis kas) – window: payments.paid_at
- **buildExpensesQuery**(Carbon $from, Carbon $to, ?string $branchId) — SALES (basis kas) – window: payments.paid_at
- **paginate**($builder, int $perPage = 20) : *LengthAwarePaginator* — SALES (basis kas) – window: payments.paid_at
- **streamCsv**($builder, array $headers, string $filename, string $delimiter = ';') — SALES (basis kas) – window: payments.paid_at
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function buildServiceItemsQuery(Carbon $from, Carbon $to, ?string $branchId)
    {
        return DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->join('services as s', 's.id', '=', 'oi.service_id')
            ->leftJoin('branches as b', 'b.id', '=', 'o.branch_id')
            ->when($branchId, fn($qq) => $qq->where('o.branch_id', $branchId))
            ->whereBetween('o.created_at', [$from, $to])
            ->selectRaw("
                b.name AS branch,
                s.name AS service,
                s.unit AS unit,
                SUM(oi.qty)            AS qty,
                SUM(oi.qty * oi.price) AS amount
            ")
            ->groupBy('b.name', 's.name', 's.unit')
            ->orderByDesc(DB::raw('SUM(oi.qty)'));
    }
    /** SALES (basis kas) – window: payments.paid_at */
    public function buildSalesQuery(Carbon $from, Carbon $to, ?string $branchId, ?string $method = null)
    {
        $q = DB::table('payments')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->leftJoin('branches', 'branches.id', '=', 'orders.branch_id')
            ->leftJoin('users', 'users.id', '=', 'orders.created_by') // kasir (opsional)
            ->when($branchId, fn($qq) => $qq->where('orders.branch_id', $branchId))
            ->whereBetween('payments.paid_at', [$from, $to])
            ->selectRaw("
                branches.name AS branch,
                payments.paid_at,
                COALESCE(orders.invoice_no, orders.number) AS invoice,
                payments.method,
                payments.amount,
                users.name AS cashier
            ");

        if ($method) {
            $q->where('payments.method', $method);
        }

        return $q->orderBy('payments.paid_at', 'asc');
    }

    /** ORDERS (basis transaksi dibuat) – window: orders.created_at */
    public function buildOrdersQuery(Carbon $from, Carbon $to, ?string $branchId, ?string $status = null)
    {
        $q = DB::table('orders')
            ->leftJoin('branches', 'branches.id', '=', 'orders.branch_id')
            ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
            ->when($branchId, fn($qq) => $qq->where('orders.branch_id', $branchId))
            ->whereBetween('orders.created_at', [$from, $to])
            ->selectRaw("
                branches.name AS branch,
                orders.created_at,
                orders.number,
                orders.invoice_no,
                customers.name AS customer,
                orders.status,
                orders.grand_total,
                orders.paid_amount,
                orders.payment_status
            ")
            ->orderBy('orders.created_at', 'asc');

        if ($status) {
            $q->where('orders.status', $status);
        }

        return $q;
    }

    /** RECEIVABLES (Piutang) – window: receivables.due_date (atau created_at bila due_date null) */
    public function buildReceivablesQuery(Carbon $from, Carbon $to, ?string $branchId, ?string $status = null)
    {
        $q = DB::table('receivables')
            ->join('orders', 'orders.id', '=', 'receivables.order_id')
            ->leftJoin('branches', 'branches.id', '=', 'orders.branch_id')
            ->when($branchId, fn($qq) => $qq->where('orders.branch_id', $branchId))
            ->where(function ($w) use ($from, $to) {
                $w->whereBetween('receivables.due_date', [$from->toDateString(), $to->toDateString()])
                    ->orWhereBetween('receivables.created_at', [$from, $to]);
            })
            ->selectRaw("
                branches.name AS branch,
                COALESCE(receivables.due_date::text, to_char(receivables.created_at, 'YYYY-MM-DD')) AS date,
                COALESCE(orders.invoice_no, orders.number) AS invoice,
                receivables.remaining_amount,
                receivables.status
            ")
            ->orderByRaw("COALESCE(receivables.due_date, receivables.created_at) ASC");

        if ($status) {
            $q->where('receivables.status', $status);
        }

        return $q;
    }

    /** EXPENSES – window: expenses.created_at */
    public function buildExpensesQuery(Carbon $from, Carbon $to, ?string $branchId)
    {
        return DB::table('expenses')
            ->leftJoin('branches', 'branches.id', '=', 'expenses.branch_id')
            ->when($branchId, fn($qq) => $qq->where('expenses.branch_id', $branchId))
            ->whereBetween('expenses.created_at', [$from, $to])
            ->selectRaw("
                branches.name AS branch,
                expenses.created_at,
                expenses.category,
                expenses.amount,
                expenses.note
            ")
            ->orderBy('expenses.created_at', 'asc');
    }

    /** Paginate untuk preview JSON */
    public function paginate($builder, int $perPage = 20): LengthAwarePaginator
    {
        return $builder->paginate($perPage);
    }

    /** Stream CSV dengan header kolom berurutan */
    public function streamCsv($builder, array $headers, string $filename, string $delimiter = ';')
    {
        return response()->streamDownload(function () use ($builder, $headers, $delimiter) {
            $out = fopen('php://output', 'w');
            // BOM agar Excel nyaman membaca UTF-8 (opsional)
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, $headers, $delimiter);
            foreach ($builder->cursor() as $row) {
                // urutkan nilai sesuai headers
                $line = [];
                foreach ($headers as $h) {
                    $key = $this->normalizeKey($h);
                    $line[] = $row->{$key} ?? null;
                }
                fputcsv($out, $line, $delimiter);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function normalizeKey(string $header): string
    {
        // "Tanggal Bayar" -> "tanggal_bayar" (mapping sederhana: gunakan alias di SELECT agar sudah snake_case)
        return str_replace(' ', '_', strtolower($header));
    }
}

```
</details>

### app/Services/UserService.php

- SHA: `cc2814ba0167`  
- Ukuran: 6 KB  
- Namespace: `App\Services`

**Class `UserService`**

Metode Publik:
- **paginate**(array $filters = [], int $perPage = 15) : *LengthAwarePaginator* — @param array{search?:string, branch_id?:string|null, role?:string} $filters
- **create**(array $data) : *User* — @param array{search?:string, branch_id?:string|null, role?:string} $filters
- **update**(User $user, array $data) : *User* — @param array{search?:string, branch_id?:string|null, role?:string} $filters
- **delete**(User $user) : *void* — @param array{search?:string, branch_id?:string|null, role?:string} $filters
- **resetPassword**(User $user, string $plain) : *void* — @param array{search?:string, branch_id?:string|null, role?:string} $filters
- **setActive**(User $user, bool $isActive) : *User* — @param array{search?:string, branch_id?:string|null, role?:string} $filters
- **setRoles**(User $user, array $roles) : *User* — @param array{search?:string, branch_id?:string|null, role?:string} $filters
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @param array{search?:string, branch_id?:string|null, role?:string} $filters
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return User::query()
            ->with('roles:id,name')
            ->when(!empty($filters['search']), function ($q) use ($filters) {
                $s = $filters['search'];
                $q->where(function ($w) use ($s) {
                    $w->where('name', 'like', "%{$s}%")
                        ->orWhere('email', 'like', "%{$s}%")
                        ->orWhere('username', 'like', "%{$s}%");
                });
            })
            ->when(!empty($filters['branch_id']), fn($q) => $q->where('branch_id', $filters['branch_id']))
            ->when(
                !empty($filters['role']),
                fn($q) =>
                $q->whereHas('roles', fn($r) => $r->where('name', $filters['role']))
            )
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * @param array{
     *   name:string,
     *   email:string,
     *   username?:string,
     *   password:string,
     *   is_active?:bool,
     *   branch_id?:string|null,
     *   role?:string|null,
     *   roles?:array<int,string>|null
     * } $data
     */
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            if (array_key_exists('username', $data)) {
                $user->username = strtolower(trim((string) $data['username'])); // NEW
            }

            // PILIH SALAH SATU:
            // 1) Jika model punya casts: 'password' => 'hashed', boleh langsung assign:
            // $user->password  = $data['password'];
            // 2) Paling aman (tanpa asumsi casts):
            $user->password = Hash::make($data['password']);

            $user->is_active = (bool) ($data['is_active'] ?? true);
            $user->branch_id = $data['branch_id'] ?? null;
            $user->save();

            // roles bisa string (role) atau array (roles)
            $roles = $this->normalizeRoles($data);
            if (!empty($roles)) {
                $user->syncRoles($roles);
            }

            return $user->load('roles:id,name');
        });
    }

    /**
     * @param array{
     *   name?:string,
     *   email?:string,
     *   username?:string,
     *   password?:string|null,
     *   is_active?:bool,
     *   branch_id?:string|null,
     *   role?:string|null,
     *   roles?:array<int,string>|null
     * } $data
     */
    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            if (array_key_exists('name', $data)) {
                $user->name = $data['name'];
            }
            if (array_key_exists('email', $data)) {
                $user->email = $data['email'];
            }
            if (array_key_exists('username', $data)) {
                $user->username = strtolower(trim((string) $data['username'])); // NEW
            }
            if (!empty($data['password'])) {
                // Sama catatan seperti create():
                // $user->password = $data['password'];
                $user->password = Hash::make($data['password']);
            }
            if (array_key_exists('is_active', $data)) {
                $user->is_active = (bool) $data['is_active'];
            }
            if (array_key_exists('branch_id', $data)) {
                $user->branch_id = $data['branch_id'];
            }

            $user->save();

            if (array_key_exists('role', $data) || array_key_exists('roles', $data)) {
                $roles = $this->normalizeRoles($data);
                $user->syncRoles($roles ?: []);
            }

            return $user->load('roles:id,name');
        });
    }

    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            $user->syncRoles([]); // detach
            $user->delete();
        });
    }

    public function resetPassword(User $user, string $plain): void
    {
        DB::transaction(function () use ($user, $plain) {
            // $user->password = $plain; // jika pakai casts hashed
            $user->password = Hash::make($plain); // aman tanpa asumsi casts
            $user->save();
        });
    }

    public function setActive(User $user, bool $isActive): User
    {
        return DB::transaction(function () use ($user, $isActive) {
            $user->is_active = $isActive;
            $user->save();
            return $user;
        });
    }

    /**
     * @param array<int,string> $roles
     */
    public function setRoles(User $user, array $roles): User
    {
        return DB::transaction(function () use ($user, $roles) {
            $user->syncRoles($roles);
            return $user->load('roles:id,name');
        });
    }

    /**
     * Terima 'role' (string) atau 'roles' (string[]) → kembalikan string[] unik.
     *
     * @param array{role?:string|null, roles?:array<int,string>|null} $data
     * @return array<int,string>
     */
    private function normalizeRoles(array $data): array
    {
        $roles = [];
        if (!empty($data['role'])) {
            $roles[] = (string) $data['role'];
        }
        if (!empty($data['roles']) && is_array($data['roles'])) {
            $roles = array_merge($roles, $data['roles']);
        }
        // unik & bersih
        $roles = array_values(array_unique(array_filter(array_map('strval', $roles))));
        return $roles;
    }
}

```
</details>

### app/Services/VoucherService.php

- SHA: `9c61b4aaaa85`  
- Ukuran: 4 KB  
- Namespace: `App\Services`

**Class `VoucherService`**

Metode Publik:
- **validate**(Order $order, Voucher $voucher) : *void* — Validasi bisnis: aktif, periode, cabang, min_total, usage_limit.
- **apply**(Order $order, Voucher $voucher, User $actor) : *Order* — Validasi bisnis: aktif, periode, cabang, min_total, usage_limit.
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use App\Models\OrderVoucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class VoucherService
{
    /**
     * Validasi bisnis: aktif, periode, cabang, min_total, usage_limit.
     * Lempar ValidationException bila tidak valid.
     */
    public function validate(Order $order, Voucher $voucher): void
    {
        if (!$voucher->active) {
            throw ValidationException::withMessages(['code' => 'Voucher tidak aktif.']);
        }

        // Scope cabang: voucher global atau sama dengan cabang order
        if ($voucher->branch_id !== null && $voucher->branch_id !== $order->branch_id) {
            throw ValidationException::withMessages(['code' => 'Voucher tidak berlaku untuk cabang ini.']);
        }

        // Periode
        $now = now();
        if ($voucher->start_at && $now->lt($voucher->start_at)) {
            throw ValidationException::withMessages(['code' => 'Voucher belum berlaku.']);
        }
        if ($voucher->end_at && $now->gt($voucher->end_at)) {
            throw ValidationException::withMessages(['code' => 'Voucher telah kedaluwarsa.']);
        }

        // Minimum total dibanding subtotal (sebelum diskon)
        if ((float) $order->subtotal < (float) $voucher->min_total) {
            throw ValidationException::withMessages(['code' => 'Subtotal belum memenuhi minimum voucher.']);
        }

        // Usage limit global: hitung dari pivot
        if ($voucher->usage_limit !== null) {
            $used = OrderVoucher::query()->where('voucher_id', $voucher->id)->count();
            if ($used >= (int) $voucher->usage_limit) {
                throw ValidationException::withMessages(['code' => 'Batas pemakaian voucher sudah tercapai.']);
            }
        }
    }

    /**
     * Terapkan voucher ke order (idempotent).
     * - Satu voucher per order (pivot unique order_id).
     * - Recalculate discount, grand_total, due_amount.
     */
    public function apply(Order $order, Voucher $voucher, User $actor): Order
    {
        return DB::transaction(function () use ($order, $voucher, $actor) {
            // Idempotensi: jika sudah ada pivot, kembalikan apa adanya
            $existing = $order->vouchers()->first();
            if ($existing) {
                return $order->refresh()->load(['items.service', 'vouchers']);
            }

            $this->validate($order, $voucher);

            // Hitung nilai potongan
            $subtotal = (float) $order->subtotal;
            $amount = match ($voucher->type) {
                'PERCENT' => round($subtotal * ((float) $voucher->value / 100), 2),
                default => (float) $voucher->value,
            };
            // Batasi tidak melebihi subtotal
            $amount = max(0.0, min($amount, $subtotal));

            // Simpan pivot
            OrderVoucher::query()->create([
                'id' => (string) Str::uuid(),
                'order_id' => $order->id,
                'voucher_id' => $voucher->id,
                'applied_amount' => number_format($amount, 2, '.', ''),
                'applied_by' => $actor->id ?? null,
                'applied_at' => now(),
            ]);

            // Update kolom diskon & total
            $order->discount = number_format(((float) $order->discount) + $amount, 2, '.', '');
            $order->grand_total = number_format(((float) $order->subtotal) - ((float) $order->discount), 2, '.', '');
            $order->due_amount = number_format(((float) $order->grand_total) - ((float) $order->paid_amount), 2, '.', '');
            $order->save();

            // TODO: audit('ORDER_VOUCHER_APPLY', ['order_id' => $order->id, 'voucher' => $voucher->code, 'amount' => $amount, 'actor' => $actor->id]);

            return $order->load(['items.service', 'vouchers']);
        });
    }
}

```
</details>



## Database (seeders)

### database/seeders/BranchSeeder.php

- SHA: `ec126954600d`  
- Ukuran: 781 B  
- Namespace: `Database\Seeders`

**Class `BranchSeeder` extends `Seeder`**

Metode Publik:
- **run**() : *void*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('branches')) {
            // modul cabang belum dimigrasi pada M0 → skip aman
            return;
        }

        $exists = DB::table('branches')->count() > 0;
        if ($exists)
            return;

        DB::table('branches')->insert([
            'id' => (string) Str::uuid(),
            'code' => 'CBG-001',
            'name' => 'Cabang Utama',
            'address' => 'Jl. Contoh No. 1',
            'invoice_prefix' => 'SLV',
            'reset_policy' => 'monthly',
        ]);
    }
}

```
</details>

### database/seeders/DatabaseSeeder.php

- SHA: `98af7f354a9a`  
- Ukuran: 452 B  
- Namespace: `Database\Seeders`

**Class `DatabaseSeeder` extends `Seeder`**

Metode Publik:
- **run**() : *void* — Seed the application's database.
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            UserSeeder::class,
            BranchSeeder::class,
        ]);
    }
}

```
</details>

### database/seeders/RolesTableSeeder.php

- SHA: `ce7284d0c443`  
- Ukuran: 542 B  
- Namespace: `Database\Seeders`

**Class `RolesTableSeeder` extends `Seeder`**

Metode Publik:
- **run**() : *void*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Superadmin',
            'Admin Cabang',
            'Kasir',
            'Petugas Cuci',
            'Kurir',
        ];

        foreach ($roles as $name) {
            Role::query()->firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'], // guard default
                []
            );
        }
    }
}

```
</details>

### database/seeders/UserSeeder.php

- SHA: `8fe599307505`  
- Ukuran: 5 KB  
- Namespace: `Database\Seeders`

**Class `UserSeeder` extends `Seeder`**

Metode Publik:
- **run**() : *void*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // --- Pastikan roles tersedia (sesuai dump & guard 'web') ---
        $roleNames = ['Superadmin', 'Admin Cabang', 'Kasir', 'Petugas Cuci', 'Kurir'];
        foreach ($roleNames as $r) {
            Role::query()->firstOrCreate(['name' => $r, 'guard_name' => 'web']);
        }

        // --- Siapkan branch default (jika tabel branches ada dan masih kosong) ---
        $defaultBranchId = null;
        if (Schema::hasTable('branches')) {
            $defaultBranchId = DB::table('branches')->value('id');
            if (!$defaultBranchId) {
                $defaultBranchId = (string) Str::uuid();
                DB::table('branches')->insert([
                    'id'            => $defaultBranchId,
                    'code'          => 'CBG-001',
                    'name'          => 'Cabang Utama',
                    'address'       => 'Alamat Cabang Utama',
                    'invoice_prefix'=> 'SLV',
                    'reset_policy'  => 'monthly',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // --- Data seed user inti ---
        $users = [
            [
                'name'       => 'Superadmin',
                'email'      => 'superadmin@gmail.com',
                'password'   => 'password',
                'role'       => 'Superadmin',
                'branch_id'  => null,               // Superadmin tanpa cabang
                'is_active'  => true,
            ],
            [
                'name'       => 'Admin Cabang',
                'email'      => 'admincabang@gmail.com',
                'password'   => 'password',
                'role'       => 'Admin Cabang',
                'branch_id'  => $defaultBranchId,   // wajib punya cabang
                'is_active'  => true,
            ],
            [
                'name'       => 'Kasir',
                'email'      => 'kasir@gmail.com',
                'password'   => 'password',
                'role'       => 'Kasir',
                'branch_id'  => $defaultBranchId,
                'is_active'  => true,
            ],
            [
                'name'       => 'Petugas Cuci',
                'email'      => 'petugascuci@gmail.com',
                'password'   => 'password',
                'role'       => 'Petugas Cuci',
                'branch_id'  => $defaultBranchId,
                'is_active'  => true,
            ],
            [
                'name'       => 'Kurir',
                'email'      => 'kurir@gmail.com',
                'password'   => 'password',
                'role'       => 'Kurir',
                'branch_id'  => $defaultBranchId,
                'is_active'  => true,
            ],
        ];

        // --- Boolean: apakah kolom branch_uuid ada? (beberapa dump masih menyertakan kolom ini) ---
        $hasBranchUuid = Schema::hasColumn('users', 'branch_uuid');

        DB::transaction(function () use ($users, $hasBranchUuid) {
            foreach ($users as $u) {
                /** @var \App\Models\User $user */
                $user = User::query()->firstOrCreate(
                    ['email' => $u['email']],
                    [
                        'name'       => $u['name'],
                        'password'   => Hash::make($u['password']),
                        'is_active'  => (bool) $u['is_active'],
                        'branch_id'  => $u['branch_id'],
                        // jangan set email_verified_at; biarkan null
                    ]
                );

                // Jika user sudah ada, update nama/is_active/branch saja (JANGAN overwrite password)
                $dirty = false;
                if ($user->name !== $u['name']) { $user->name = $u['name']; $dirty = true; }
                if ((bool)$user->is_active !== (bool)$u['is_active']) { $user->is_active = (bool)$u['is_active']; $dirty = true; }
                if ((string)$user->branch_id !== (string)$u['branch_id']) { $user->branch_id = $u['branch_id']; $dirty = true; }
                if ($dirty) { $user->save(); }

                // Sinkronkan branch_uuid jika kolom tersedia
                if ($hasBranchUuid) {
                    $branchUuid = $u['branch_id'] ?: null;
                    if ($user->branch_uuid !== $branchUuid) {
                        $user->forceFill(['branch_uuid' => $branchUuid])->save();
                    }
                }

                // Pastikan role terpasang (tanpa duplikasi)
                if (method_exists($user, 'syncRoles')) {
                    // Banyak project lebih suka 1 role/user; kalau mau multi-role ganti ke syncWithoutDetaching
                    $user->syncRoles([$u['role']]);
                } elseif (method_exists($user, 'assignRole')) {
                    if (!$user->hasRole($u['role'])) {
                        $user->assignRole($u['role']);
                    }
                }
            }
        });
    }
}

```
</details>



## resources (resources)

### resources/views/orders/receipt.blade.php

- SHA: `620374e4da34`  
- Ukuran: 14 KB  
- Namespace: ``
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
{{-- resources/views/orders/receipt.blade.php --}}
<!doctype html>
<html lang="id" data-theme="light">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1" />

  @php
  // Logika asli dipertahankan
  $sisa = max((float) $order->grand_total - (float) $order->paid_amount, 0);
  $isLunas = $sisa <= 0 && $order->payment_status === 'PAID';
    $docTitle = $isLunas ? 'KUITANSI PEMBAYARAN' : 'TAGIHAN / INVOICE';
    @endphp

    <title>{{ $docTitle }} {{ $order->invoice_no ?? $order->number }}</title>

    <style>
      /* ========= Design tokens SALVE (screen view) ========= */
      :root {
        --brand: #0000FF;
        --on-brand: #FFFFFF;
        --text: #0B1220;
        --surface: #F5F7FB;
        --border: #E6EAF2;
        --success: #11A362;
        --warning: #EF9300;
        --danger: #D92D20;
        --info: #2E7CF6;
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --shadow-1: 0 1px 2px rgba(16, 24, 40, .06), 0 1px 3px rgba(16, 24, 40, .10);
        --shadow-2: 0 8px 16px rgba(16, 24, 40, .10), 0 2px 6px rgba(16, 24, 40, .08);
        --focus: 0 0 0 3px rgba(0, 0, 255, .25);
      }

      [data-theme="dark"] {
        --text: #F5F7FB;
        --surface: #0F172A;
        --border: #1E293B;
      }

      @media (prefers-color-scheme:dark) {
        html:not([data-theme="light"]) {
          --text: #F5F7FB;
          --surface: #0F172A;
          --border: #1E293B;
        }
      }

      /* ========= Base ========= */
      * {
        box-sizing: border-box;
      }

      html,
      body {
        height: 100%;
      }

      body {
        margin: 0;
        font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji";
        color: var(--text);
        background: var(--surface);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
      }

      a {
        color: var(--brand);
        text-decoration: none;
      }

      a:hover {
        text-decoration: underline;
      }

      :focus-visible {
        outline: 0;
        box-shadow: var(--focus);
        border-radius: 6px;
      }

      /* ========= Layout ========= */
      .container {
        max-width: 720px;
        margin-inline: auto;
        padding: 24px;
        display: grid;
        gap: 16px;
      }

      .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
      }

      .brand {
        font-weight: 700;
        font-size: 18px;
        line-height: 1.2;
      }

      .doctype {
        font-weight: 700;
        font-size: 12px;
        color: #1A4BFF;
        background: #E6EDFF;
        padding: 6px 10px;
        border-radius: 999px;
      }

      .card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-1);
      }

      .section {
        padding: 16px;
      }

      .section+.section {
        border-top: 1px solid var(--border);
      }

      /* ========= Meta grid ========= */
      .meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        font-size: 14px;
      }

      .meta dt {
        color: #475569;
        font-weight: 500;
      }

      .meta dd {
        margin: 0;
      }

      /* ========= Badge status ========= */
      .badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
      }

      .badge--paid {
        color: #065F46;
        background: rgba(17, 163, 98, .12);
      }

      .badge--unpaid {
        color: #7C2D12;
        background: rgba(217, 45, 32, .12);
      }

      /* ========= Table items ========= */
      .table-wrap {
        overflow: auto;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
      }

      table {
        border-collapse: collapse;
        width: 100%;
        font-size: 14px;
        background: #fff;
      }

      thead th {
        text-align: left;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #334155;
        background: #E6EDFF;
        padding: 10px 12px;
        position: sticky;
        top: 0;
        z-index: 1;
      }

      tbody td {
        padding: 12px;
        border-top: 1px solid var(--border);
        vertical-align: top;
      }

      tbody tr:hover {
        background: rgba(0, 0, 0, .04);
      }

      .right {
        text-align: right;
      }

      .muted {
        color: #475569;
      }

      .bold {
        font-weight: 700;
      }

      /* ========= Totals ========= */
      .totals {
        display: grid;
        gap: 8px;
      }

      .row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
      }

      .row--strong .label {
        font-weight: 700;
      }

      .row--strong .value {
        font-weight: 800;
        font-size: 18px;
      }

      /* ========= QRIS ========= */
      .qris-box {
        display: grid;
        justify-items: center;
        gap: 8px;
        text-align: center;
      }

      .qris {
        width: 100%;
        max-width: 220px;
        height: auto;
        image-rendering: -webkit-optimize-contrast;
        border-radius: var(--radius-sm);
        box-shadow: var(--shadow-1);
      }

      .qris-caption {
        font-size: 12px;
        color: #334155;
      }

      /* ========= Footer ========= */
      .footer {
        font-size: 12px;
        color: #64748B;
      }

      /* ========= Stamp Loyalty ========= */
      .stamps {
        display: grid;
        grid-template-columns: repeat(10, 1fr);
        gap: 6px;
        margin-top: 8px;
      }

      .stamp {
        height: 22px;
        border: 1px dashed var(--border);
        border-radius: var(--radius-sm);
        background: #fff;
        box-shadow: var(--shadow-1);
      }

      .stamp--filled {
        border-style: solid;
        border-color: #1A4BFF;
        background: #E6EDFF;
      }

      .stamp--milestone {
        position: relative;
      }

      .stamp--milestone::after {
        content: attr(data-m);
        position: absolute;
        inset: 0;
        display: grid;
        place-items: center;
        font-size: 10px;
        font-weight: 700;
        color: #1A4BFF;
      }

      /* Card helpers */
      .stack {
        display: grid;
        gap: 12px;
      }

      .split {
        display: grid;
        gap: 16px;
        grid-template-columns: 1fr;
      }

      @media (min-width:720px) {
        .split {
          grid-template-columns: 1.2fr .8fr;
        }
      }
    </style>
</head>

<body>
  <main class="container" role="main">
    <!-- Header -->
    <div class="header">
      <div class="stack">
        <div class="brand">{{ $branch?->name ?? 'Salve Laundry' }}</div>
        <div class="doctype">{{ $docTitle }}</div>
      </div>
      <div>
        <span class="badge {{ $isLunas ? 'badge--paid' : 'badge--unpaid' }}">
          {{ $isLunas ? 'Lunas' : 'Belum Lunas' }}
        </span>
      </div>
    </div>

    <!-- Identitas & Status -->
    <section class="card">
      <div class="section">
        <dl class="meta">
          <div>
            <dt>No. Dokumen</dt>
            <dd>{{ $order->invoice_no ?? $order->number }}</dd>
          </div>
          <div>
            <dt>Tgl Dibuat</dt>
            <dd>{{ \Illuminate\Support\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</dd>
          </div>
          <div>
            <dt>Status Bayar</dt>
            <dd>{{ $order->payment_status }}</dd>
          </div>
          <div>
            <dt>Pelanggan</dt>
            <dd>{{ $order->customer->name ?? '—' }}</dd>
          </div>
          <div>
            <dt>Tgl Masuk</dt>
            <dd>
              {{ $order->received_at ? \Illuminate\Support\Carbon::parse($order->received_at)->format('d/m/Y H:i') : '—' }}
            </dd>
          </div>
          <div>
            <dt>Tgl Selesai</dt>
            <dd>
              {{ $order->ready_at ? \Illuminate\Support\Carbon::parse($order->ready_at)->format('d/m/Y H:i') : '—' }}
            </dd>
          </div>
        </dl>
      </div>
    </section>

    <!-- Items + Ringkasan -->
    <section class="split">
      <!-- Items -->
      <div class="card">
        <div class="section">
          <div class="muted" style="font-size:12px; margin-bottom:8px;">Rincian Layanan</div>
          <div class="table-wrap">
            <table aria-label="Tabel item layanan">
              <thead>
                <tr>
                  <th>Layanan</th>
                  <th class="right">Qty</th>
                  <th class="right">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @foreach($order->items as $it)
                <tr>
                  <td>
                    <div class="bold">{{ $it->service->name ?? 'Layanan' }}</div>
                  </td>
                  <td class="right">{{ (float) $it->qty }}</td>
                  <td class="right">{{ number_format((float) $it->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Ringkasan Pembayaran -->
      <div class="card">
        <div class="section stack">
          <div class="muted" style="font-size:12px;">Ringkasan Pembayaran</div>
          <div class="totals">
            <div class="row">
              <div class="label">Subtotal</div>
              <div class="value">{{ number_format((float) $order->subtotal, 0, ',', '.') }}</div>
            </div>
            <div class="row">
              <div class="label">Diskon</div>
              <div class="value">{{ number_format((float) $order->discount, 0, ',', '.') }}</div>
            </div>
            <div class="row row--strong">
              <div class="label">Grand Total</div>
              <div class="value">{{ number_format((float) $order->grand_total, 0, ',', '.') }}</div>
            </div>
            <div class="row">
              <div class="label">Dibayar</div>
              <div class="value">{{ number_format((float) $order->paid_amount, 0, ',', '.') }}</div>
            </div>
            <div class="row">
              <div class="label">{{ $isLunas ? 'Sisa' : 'Sisa Tagihan' }}</div>
              <div class="value">{{ number_format($sisa, 0, ',', '.') }}</div>
            </div>
          </div>
        </div>

        {{-- === Stamp Loyalty (tampil hanya jika data tersedia dari controller) === --}}
        @if(isset($loy) && $loy)
        <div class="section">
          <div class="muted" style="font-size:12px; margin-bottom:8px;">Stamp Loyalty</div>

          <div class="row">
            <div class="label">Progress</div>
            <div class="value">{{ $loy['stamps'] }} / {{ $loy['cycle'] }}</div>
          </div>

          @php
          $cycle = (int)($loy['cycle'] ?? 10);
          $stamps = (int)($loy['stamps'] ?? 0);
          $filled = $cycle > 0 ? ($stamps % $cycle) : 0;
          // Jika tepat jatuh reward pada transaksi ini (mod 0), tampilkan penuh:
          if ($filled === 0 && !empty($order->loyalty_reward)) {
          $filled = $cycle;
          }
          @endphp
          <div class="stamps" role="list" aria-label="Progres stamp loyalty">
            @for ($i = 1; $i <= $cycle; $i++)
              @php
              $isFilled=$i <=$filled;
              $milestone=($i===5 || $i===10) ? $i : null; // penanda 5 & 10
              @endphp
              <div
              role="listitem"
              class="stamp {{ $isFilled ? 'stamp--filled' : '' }} {{ $milestone ? 'stamp--milestone' : '' }}"
              @if($milestone) data-m="{{ $milestone }}" @endif>
          </div>
          @endfor
        </div>

        @if(!empty($order->loyalty_reward))
        <div style="font-size:12px; margin:6px 0 8px 0;">
          Reward diterapkan pada transaksi ini:
          @if($order->loyalty_reward === 'DISC25') <strong>Diskon 25%</strong>.
          @elseif($order->loyalty_reward === 'FREE100') <strong>Gratis 100%</strong>.
          @else <strong>{{ $order->loyalty_reward }}</strong>.
          @endif
        </div>
        @endif

        <div class="row">
          <div class="label">Sisa ke Diskon 25%</div>
          <div class="value">{{ $loy['sisa25'] }}</div>
        </div>
        <div class="row">
          <div class="label">Sisa ke Gratis 100%</div>
          <div class="value">{{ $loy['sisa100'] }}</div>
        </div>
      </div>
      @endif

      @php
      $qrisPath = 'qris.png';
      $hasQris = \Illuminate\Support\Facades\Storage::disk('public')->exists($qrisPath);
      @endphp

      @if(!$isLunas && $hasQris)
      <div class="section qris-box">
        <div class="qris-caption">Scan untuk bayar (QRIS)</div>
        <img class="qris" src="{{ asset('storage/'.$qrisPath) }}" alt="QRIS">
      </div>
      @endif
      </div>
    </section>

    <!-- PERHATIAN / Ketentuan Layanan -->
    <section class="card">
      <div class="section">
        <div class="muted" style="font-size:12px; font-weight:700; margin-bottom:8px;">PERHATIAN!</div>
        <ul style="margin:0; padding-left:18px; font-size:12px; color:#334155; display:grid; gap:4px;">
          <li>sampaikan pada petugas jika ada sepatu yang perlu perlakuan khusus.</li>
          <li>pengambilan harus di sertai link struk, jika link struk hilang atau terhapus bawa kartu identitas diri (KTP/SIM).</li>
          <li>pengaduan maksimal 1x24 jam setelah sepatu di terima, disertai link struk.</li>
          <li>sepatu tidak di ambil setelah tanggal kesepakatan pengambilan, jika mengalami kerusakan, kehilangan dan Kembali kotor bukan menjadi tanggung jawab kami.</li>
          <li>jika terjadi kerusakan dalam pengerjaan sepatu, penggantian kerugian maksimal 3x Harga pengerjaan sepatu tsb.</li>
          <li>jika terjadi hal yang bersifat force majeure, sepatu yang berada di store bukan menjadi tanggung jawab kami!</li>
        </ul>
      </div>
    </section>

    <!-- Footer -->
    <section class="card">
      <div class="section footer">
        Dicetak: {{ $printedAt->format('d/m/Y H:i') }}
      </div>
    </section>
  </main>
</body>

</html>
```
</details>



## routes/api.php

- SHA: `95d2492a1b99`  
- Ukuran: 7 KB

**Ringkasan Routes (deteksi heuristik):**

| Method | Path | Controller | Action |
|---|---|---|---|
| POST | `/login` | `AuthController` | `login` |
| GET | `/me` | `AuthController` | `me` |
| POST | `/logout` | `AuthController` | `logout` |
| GET | `/users` | `UserController` | `index` |
| GET | `/users/{user}` | `UserController` | `show` |
| POST | `/users` | `UserController` | `store` |
| PUT | `/users/{user}` | `UserController` | `update` |
| DELETE | `/users/{user}` | `UserController` | `destroy` |
| POST | `/users/{user}/reset-password` | `UserController` | `resetPassword` |
| POST | `/users/{user}/active` | `UserController` | `setActive` |
| POST | `/users/{user}/roles` | `UserController` | `setRoles` |
| GET | `/branches` | `BranchController` | `index` |
| POST | `/branches` | `BranchController` | `store` |
| GET | `/branches/{branch}` | `BranchController` | `show` |
| PUT | `/branches/{branch}` | `BranchController` | `update` |
| DELETE | `/branches/{branch}` | `BranchController` | `destroy` |
| GET | `/invoice-counters` | `InvoiceCounterController` | `index` |
| POST | `/invoice-counters` | `InvoiceCounterController` | `store` |
| DELETE | `/invoice-counters/{id}` | `InvoiceCounterController` | `destroy` |
| PUT | `/invoice-counters/{id}` | `InvoiceCounterController` | `update` |
| GET | `/invoice-counters/preview` | `InvoiceCounterController` | `preview` |
| POST | `/invoice-counters/{id}/reset-now` | `InvoiceCounterController` | `resetNow` |
| GET | `/service-categories` | `CategoryController` | `index` |
| POST | `/service-categories` | `CategoryController` | `store` |
| GET | `/service-categories/{category}` | `CategoryController` | `show` |
| PUT | `/service-categories/{category}` | `CategoryController` | `update` |
| DELETE | `/service-categories/{category}` | `CategoryController` | `destroy` |
| GET | `/services` | `ServiceController` | `index` |
| POST | `/services` | `ServiceController` | `store` |
| GET | `/services/{service}` | `ServiceController` | `show` |
| PUT | `/services/{service}` | `ServiceController` | `update` |
| DELETE | `/services/{service}` | `ServiceController` | `destroy` |
| POST | `/service-prices/set` | `ServicePriceController` | `set` |
| GET | `/service-prices/by-service` | `ServicePriceController` | `listByService` |
| GET | `/customers` | `CustomerController` | `index` |
| GET | `/customers/search-wa` | `CustomerController` | `searchByWhatsapp` |
| GET | `/customers/{customer}` | `CustomerController` | `show` |
| POST | `/customers` | `CustomerController` | `store` |
| PUT | `/customers/{customer}` | `CustomerController` | `update` |
| DELETE | `/customers/{customer}` | `CustomerController` | `destroy` |
| GET | `/wash-notes/candidates` | `WashNoteController` | `candidates` |
| GET | `/reports/{kind}` | `ReportController` | `preview` |
| GET | `/reports/{kind}/export` | `ReportController` | `export` |
| GET | `/loyalty/{customer}` | `LoyaltyController` | `summary` |
| GET | `/loyalty/{customer}/history` | `LoyaltyController` | `history` |
| GET | `/orders/{order}/receipt` | `OrderController` | `receipt` |
| POST | `/orders/{order}/share-link` | `OrderController` | `shareLink` |
| POST | `/orders/{order}/payments` | `OrderPaymentsController` | `store` |
| POST | `/orders/{order}/apply-voucher` | `` | `` |
| GET | `/orders` | `OrderController` | `index` |
| GET | `/orders/{order}` | `OrderController` | `show` |
| POST | `/orders` | `OrderController` | `store` |
| PUT | `/orders/{order}` | `OrderController` | `update` |
| POST | `/orders/{order}/status` | `OrderController` | `transitionStatus` |
| POST | `/orders/{order}/photos` | `OrderPhotosController` | `store` |
| GET | `/deliveries` | `DeliveryController` | `index` |
| GET | `/deliveries/{delivery}` | `DeliveryController` | `show` |
| POST | `/deliveries` | `DeliveryController` | `store` |
| PUT | `/deliveries/{delivery}/assign` | `DeliveryController` | `assign` |
| PUT | `/deliveries/{delivery}/status` | `DeliveryController` | `updateStatus` |
| GET | `/vouchers` | `` | `` |
| POST | `/vouchers` | `` | `` |
| GET | `/vouchers/{voucher}` | `` | `` |
| PUT | `/vouchers/{voucher}` | `` | `` |
| DELETE | `/vouchers/{voucher}` | `` | `` |
| GET | `/receivables` | `ReceivableController` | `index` |
| POST | `/receivables/{id}/settle` | `ReceivableController` | `settle` |
| GET | `dashboard/summary` | `DashboardController` | `summary` |

<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\InvoiceCounterController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ServicePriceController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderPaymentsController;
use App\Http\Controllers\Api\OrderPhotosController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\ReceivableController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\LoyaltyController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\WashNoteController;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        // User routes
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);   // was {id}
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{user}', [UserController::class, 'update']); // was {id}
        Route::delete('/users/{user}', [UserController::class, 'destroy']); // was {id}

        // Aksi khusus sudah benar (sudah {user})
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword']);
        Route::post('/users/{user}/active', [UserController::class, 'setActive']);
        Route::post('/users/{user}/roles', [UserController::class, 'setRoles']);

        // Branches (CRUD)
        Route::get('/branches', [BranchController::class, 'index']);
        Route::post('/branches', [BranchController::class, 'store']);
        Route::get('/branches/{branch}', [BranchController::class, 'show']);
        Route::put('/branches/{branch}', [BranchController::class, 'update']);
        Route::delete('/branches/{branch}', [BranchController::class, 'destroy']);

        // Invoice Counters (get list per branch + update per id)
        Route::get('/invoice-counters', [InvoiceCounterController::class, 'index']);
        Route::post('/invoice-counters', [InvoiceCounterController::class, 'store']);
        Route::delete('/invoice-counters/{id}', [InvoiceCounterController::class, 'destroy']);
        Route::put('/invoice-counters/{id}', [InvoiceCounterController::class, 'update']);

        Route::get('/invoice-counters/preview', [InvoiceCounterController::class, 'preview']);
        Route::post('/invoice-counters/{id}/reset-now', [InvoiceCounterController::class, 'resetNow']);

        Route::get('/service-categories', [CategoryController::class, 'index']);
        Route::post('/service-categories', [CategoryController::class, 'store']);
        Route::get('/service-categories/{category}', [CategoryController::class, 'show']);
        Route::put('/service-categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/service-categories/{category}', [CategoryController::class, 'destroy']);

        Route::get('/services', [ServiceController::class, 'index']);
        Route::post('/services', [ServiceController::class, 'store']);
        Route::get('/services/{service}', [ServiceController::class, 'show']);
        Route::put('/services/{service}', [ServiceController::class, 'update']);
        Route::delete('/services/{service}', [ServiceController::class, 'destroy']);

        Route::post('/service-prices/set', [ServicePriceController::class, 'set']);
        Route::get('/service-prices/by-service', [ServicePriceController::class, 'listByService']);

        Route::get('/customers', [CustomerController::class, 'index']);
        Route::get('/customers/search-wa', [CustomerController::class, 'searchByWhatsapp']);
        Route::get('/customers/{customer}', [CustomerController::class, 'show']);
        Route::post('/customers', [CustomerController::class, 'store']);
        Route::put('/customers/{customer}', [CustomerController::class, 'update']);
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy']);

        // Wash Notes
        Route::get('/wash-notes/candidates', [WashNoteController::class, 'candidates']);
        Route::apiResource('wash-notes', WashNoteController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

        // Reports
        Route::get('/reports/{kind}', [ReportController::class, 'preview']);
        Route::get('/reports/{kind}/export', [ReportController::class, 'export']);

        // Loyalty (Stamp) — butuh login & scope cabang
        Route::get('/loyalty/{customer}', [LoyaltyController::class, 'summary']);
        Route::get('/loyalty/{customer}/history', [LoyaltyController::class, 'history']);

        // Receipt (HTML)
        Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt']);
        Route::post('/orders/{order}/share-link', [OrderController::class, 'shareLink']);

        // Payments
        Route::post('/orders/{order}/payments', [OrderPaymentsController::class, 'store']);

        // Apply voucher ke order
        Route::post('/orders/{order}/apply-voucher', [\App\Http\Controllers\Api\VoucherController::class, 'applyToOrder']);

        // Orders
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::put('/orders/{order}', [OrderController::class, 'update']);
        Route::post('/orders/{order}/status', [OrderController::class, 'transitionStatus']);
        Route::post('/orders/{order}/photos', [OrderPhotosController::class, 'store']);

        // Deliveries
        Route::get('/deliveries', [DeliveryController::class, 'index']);
        Route::get('/deliveries/{delivery}', [DeliveryController::class, 'show']);
        Route::post('/deliveries', [DeliveryController::class, 'store']);
        Route::put('/deliveries/{delivery}/assign', [DeliveryController::class, 'assign']);
        Route::put('/deliveries/{delivery}/status', [DeliveryController::class, 'updateStatus']);

        Route::get('/vouchers', [\App\Http\Controllers\Api\VoucherController::class, 'index']);
        Route::post('/vouchers', [\App\Http\Controllers\Api\VoucherController::class, 'store']);
        Route::get('/vouchers/{voucher}', [\App\Http\Controllers\Api\VoucherController::class, 'show']);
        Route::put('/vouchers/{voucher}', [\App\Http\Controllers\Api\VoucherController::class, 'update']);
        Route::delete('/vouchers/{voucher}', [\App\Http\Controllers\Api\VoucherController::class, 'destroy']);

        Route::get('/receivables', [ReceivableController::class, 'index']);
        Route::post('/receivables/{id}/settle', [ReceivableController::class, 'settle']);

        Route::apiResource('expenses', ExpenseController::class)
            ->only(['index', 'store', 'show', 'update', 'destroy']);

        Route::get('dashboard/summary', [DashboardController::class, 'summary']);

        // Tambahkan route lain di sini sesuai kebutuhan
    });
});

```
</details>



## AuthServiceProvider.php

- SHA: `4dd5fe74ab5e`  
- Ukuran: 3 KB

**$policies**
- `User` => `UserPolicy`
- `Branch` => `BranchPolicy`
- `ServiceCategory` => `CategoryPolicy`
- `Service` => `ServicePolicy`
- `Customer` => `CustomerPolicy`
- `Order` => `OrderPolicy`
- `Delivery` => `DeliveryPolicy`
- `Voucher` => `VoucherPolicy`
- `Receivable` => `ReceivablePolicy`
- `Expense` => `ExpensePolicy`
- `WashNote` => `WashNotePolicy`

**Gate::define()**
- `user.assignRole`
- `user.viewAny`
- `user.view`
- `user.create`
- `user.update`
- `dashboard.summary`
- `user.delete`

<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
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
use App\Models\Expense;
use App\Policies\ExpensePolicy;
use App\Models\WashNote;
use App\Policies\WashNotePolicy;

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
        Expense::class => ExpensePolicy::class,
        WashNote::class => WashNotePolicy::class,
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

```
</details>
