# Dokumentasi Backend (FULL Source)

_Dihasilkan otomatis: 2025-11-28 15:46:46_  
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
  - [app/Http/Controllers/Api/OrderController.php](#file-apphttpcontrollersapiordercontrollerphp)
  - [app/Http/Controllers/Api/OrderPaymentsController.php](#file-apphttpcontrollersapiorderpaymentscontrollerphp)
  - [app/Http/Controllers/Api/OrderPhotosController.php](#file-apphttpcontrollersapiorderphotoscontrollerphp)
  - [app/Http/Controllers/Api/ReceivableController.php](#file-apphttpcontrollersapireceivablecontrollerphp)
  - [app/Http/Controllers/Api/ServiceController.php](#file-apphttpcontrollersapiservicecontrollerphp)
  - [app/Http/Controllers/Api/ServicePriceController.php](#file-apphttpcontrollersapiservicepricecontrollerphp)
  - [app/Http/Controllers/Api/UserController.php](#file-apphttpcontrollersapiusercontrollerphp)
  - [app/Http/Controllers/Api/VoucherController.php](#file-apphttpcontrollersapivouchercontrollerphp)

- [Models (app/Models)](#models-appmodels)
  - [app/Models/Branch.php](#file-appmodelsbranchphp)
  - [app/Models/Customer.php](#file-appmodelscustomerphp)
  - [app/Models/Delivery.php](#file-appmodelsdeliveryphp)
  - [app/Models/DeliveryEvent.php](#file-appmodelsdeliveryeventphp)
  - [app/Models/Expense.php](#file-appmodelsexpensephp)
  - [app/Models/InvoiceCounter.php](#file-appmodelsinvoicecounterphp)
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

- [Policies (app/Policies)](#policies-apppolicies)
  - [app/Policies/BranchPolicy.php](#file-apppoliciesbranchpolicyphp)
  - [app/Policies/CategoryPolicy.php](#file-apppoliciescategorypolicyphp)
  - [app/Policies/CustomerPolicy.php](#file-apppoliciescustomerpolicyphp)
  - [app/Policies/DeliveryPolicy.php](#file-apppoliciesdeliverypolicyphp)
  - [app/Policies/ExpensePolicy.php](#file-apppoliciesexpensepolicyphp)
  - [app/Policies/OrderPolicy.php](#file-apppoliciesorderpolicyphp)
  - [app/Policies/ReceivablePolicy.php](#file-apppoliciesreceivablepolicyphp)
  - [app/Policies/ServicePolicy.php](#file-apppoliciesservicepolicyphp)
  - [app/Policies/UserPolicy.php](#file-apppoliciesuserpolicyphp)
  - [app/Policies/VoucherPolicy.php](#file-apppoliciesvoucherpolicyphp)

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
  - [app/Http/Requests/ResetPasswordRequest.php](#file-apphttprequestsresetpasswordrequestphp)
  - [app/Http/Requests/ServicePriceSetRequest.php](#file-apphttprequestsservicepricesetrequestphp)
  - [app/Http/Requests/ServiceStoreRequest.php](#file-apphttprequestsservicestorerequestphp)
  - [app/Http/Requests/ServiceUpdateRequest.php](#file-apphttprequestsserviceupdaterequestphp)
  - [app/Http/Requests/UserStoreRequest.php](#file-apphttprequestsuserstorerequestphp)
  - [app/Http/Requests/UserUpdateRequest.php](#file-apphttprequestsuserupdaterequestphp)
  - [app/Http/Requests/Vouchers/VoucherStoreRequest.php](#file-apphttprequestsvouchersvoucherstorerequestphp)
  - [app/Http/Requests/Vouchers/VoucherUpdateRequest.php](#file-apphttprequestsvouchersvoucherupdaterequestphp)

- [Services (app/Services)](#services-appservices)
  - [app/Services/AuthService.php](#file-appservicesauthservicephp)
  - [app/Services/DashboardService.php](#file-appservicesdashboardservicephp)
  - [app/Services/DeliveryService.php](#file-appservicesdeliveryservicephp)
  - [app/Services/InvoiceNumberService.php](#file-appservicesinvoicenumberservicephp)
  - [app/Services/InvoiceService.php](#file-appservicesinvoiceservicephp)
  - [app/Services/OrderNumberService.php](#file-appservicesordernumberservicephp)
  - [app/Services/OrderService.php](#file-appservicesorderservicephp)
  - [app/Services/PaymentService.php](#file-appservicespaymentservicephp)
  - [app/Services/PricingService.php](#file-appservicespricingservicephp)
  - [app/Services/ReceivableService.php](#file-appservicesreceivableservicephp)
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

- SHA: `dcec2eec2c98`  
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
    public function __construct(private AuthService $auth)
    {
    }

    public function login(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $res = $this->auth->login($payload['email'], $payload['password']);
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

- SHA: `d6d7f3c70700`  
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

    private function branchScopeFor(Request $request): ?int
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

### app/Http/Controllers/Api/OrderController.php

- SHA: `6913294c2089`  
- Ukuran: 4 KB  
- Namespace: `App\Http\Controllers\Api`

**Class `OrderController` extends `Controller`**

Metode Publik:
- **__construct**(private OrderService $svc)
- **index**(Request $request)
- **show**(Order $order)
- **store**(OrderStoreRequest $request)
- **update**(OrderUpdateRequest $request, Order $order)
- **receipt**(Request $request, Order $order)
- **transitionStatus**(OrderStatusRequest $request, Order $order)
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function __construct(private OrderService $svc)
    {
    }

    // GET /orders
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        $me = $request->user();
        $q = Order::query()
            ->with(['customer', 'items.service'])
            ->orderByDesc('created_at');

        // scope cabang
        if ($me->hasRole('Superadmin')) {
            if ($branchId = (string) $request->query('branch_id')) {
                $q->where('branch_id', $branchId);
            }
        } else if ($me->branch_id) {
            $q->where('branch_id', $me->branch_id);
        }

        if ($s = $request->query('q')) {
            $q->where(function ($w) use ($s) {
                $w->where('number', 'like', "%{$s}%")
                    ->orWhere('notes', 'like', "%{$s}%");
            });
        }
        if ($st = $request->query('status')) {
            $q->where('status', $st);
        }

        $per = (int) $request->query('per_page', 10);
        $page = $q->paginate($per);

        return response()->json([
            'data' => $page->items(),
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

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        return response()->json([
            'data' => $order->load(['customer', 'items.service', 'photos']),
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    // POST /orders
    public function store(OrderStoreRequest $request)
    {
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
        $this->authorize('view', $order);

        $order->load([
            'items.service:id,name',
            'branch:id,name,address',
        ]);

        $html = view('orders.receipt', [
            'order' => $order,
            'branch' => $order->getRelation('branch'),
            'printedAt' => now(),
        ])->render();

        return new Response($html, 200, ['Content-Type' => 'text/html; charset=UTF-8']);
    }

    // POST /orders/{order}/status
    public function transitionStatus(OrderStatusRequest $request, Order $order)
    {
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

- SHA: `79e5a8187973`  
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
            }, 'order.customer:id,name'])
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

        return response()->json([
            'data' => [
                'order' => $result['order'],
                'receivable' => $result['receivable'],
            ],
            'meta' => (object) [],
            'message' => 'Pelunasan berhasil.',
            'errors' => null,
        ]);
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

- SHA: `9f3c27ef9c81`  
- Ukuran: 5 KB  
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
        ];
        $perPage = (int) $request->integer('per_page', 15);

        /** @var \Illuminate\Pagination\LengthAwarePaginator $page */
        $page = $this->svc->paginate($filters, $perPage);

        return response()->json([
            'data' => $page->items(),
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

        $user->load('roles:id,name');

        return response()->json([
            'data' => $user,
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

### app/Models/Order.php

- SHA: `c5abc8d66e16`  
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

- SHA: `52c875472c44`  
- Ukuran: 1 KB  
- Namespace: `App\Models`

**Class `User` extends `Authenticatable`**

Metode Publik:
- **branch**() — @use HasFactory<\Database\Factories\UserFactory>
- **getRolesListAttribute**() : *array* — @use HasFactory<\Database\Factories\UserFactory>
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

- SHA: `1e54bc29353f`  
- Ukuran: 1 KB  
- Namespace: `App\Policies`

**Class `CustomerPolicy`**

Metode Publik:
- **before**(User $user, $ability)
- **viewAny**(User $user) : *bool*
- **view**(User $user, Customer $c) : *bool*
- **create**(User $user) : *bool*
- **update**(User $user, Customer $c) : *bool*
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

### app/Policies/OrderPolicy.php

- SHA: `7ca1e4699484`  
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
        if ($user->hasAnyRole(['Admin Cabang', 'Kasir'])) {
            return (string) $user->branch_id === (string) $order->branch_id;
        }
        return false;
    }

    public function delete(User $user, Order $order): bool
    {
        if ($user->hasRole('Admin Cabang')) {
            return (string) $user->branch_id === (string) $order->branch_id;
        }
        return false;
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

- SHA: `055a43d71ad3`  
- Ukuran: 2 KB  
- Namespace: `App\Http\Requests`

**Class `OrderStoreRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
- **messages**() : *array*
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

    protected function prepareForValidation(): void
    {
        if ($this->has('customer_id')) {
            $this->merge([
                'customer_id' => trim((string) $this->input('customer_id')),
            ]);
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

- SHA: `2de209141a36`  
- Ukuran: 804 B  
- Namespace: `App\Http\Requests`

**Class `OrderUpdateRequest` extends `FormRequest`**

Metode Publik:
- **authorize**() : *bool*
- **rules**() : *array*
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $order = $this->route('order');
        return $this->user()?->can('update', $order) ?? false;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['sometimes', 'nullable', 'uuid', 'exists:customers,id'],
            'notes' => ['sometimes', 'nullable', 'string'],

            'items' => ['sometimes', 'array', 'min:1'],
            'items.*.id' => ['sometimes', 'uuid'], // jika edit baris
            'items.*.service_id' => ['required_with:items.*.qty', 'uuid', 'exists:services,id'],
            'items.*.qty' => ['required_with:items', 'numeric', 'gt:0'],
        ];
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

- SHA: `6256a71bb1eb`  
- Ukuran: 2 KB  
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

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
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
        ];
    }
}

```
</details>

### app/Http/Requests/UserUpdateRequest.php

- SHA: `2318df7a9525`  
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



## Services (app/Services)

### app/Services/AuthService.php

- SHA: `a675ff1ce9bd`  
- Ukuran: 1 KB  
- Namespace: `App\Services`

**Class `AuthService`**

Metode Publik:
- **login**(string $email, string $password) : *array*
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
    public function login(string $email, string $password): array
    {
        $user = User::query()->where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
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

- SHA: `a5574bfaf3e1`  
- Ukuran: 4 KB  
- Namespace: `App\Services`

**Class `DashboardService`**

Metode Publik:
- **summary**(Carbon $from, Carbon $to, ?string $branchId) : *array* — @param Carbon $from  mulai (awal hari)
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * @param Carbon $from  mulai (awal hari)
     * @param Carbon $to    akhir (akhir hari)
     * @param string|null $branchId
     * @return array<string,mixed>
     */
    public function summary(Carbon $from, Carbon $to, ?string $branchId): array
    {
        // OMZET (basis kas): sum payments.amount dalam window paid_at
        $omzet = DB::table('payments')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('payments.paid_at', [$from, $to])
            ->sum('payments.amount');

        // TRANSAKSI: count orders by created_at
        $transaksi = DB::table('orders')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('orders.created_at', [$from, $to])
            ->count();

        // ONGKIR: sum deliveries.fee by deliveries.created_at
        $ongkir = DB::table('deliveries')
            ->join('orders', 'orders.id', '=', 'deliveries.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('deliveries.created_at', [$from, $to])
            ->sum('deliveries.fee');

        // VOUCHER: jumlah order yang pakai voucher & total applied_amount by applied_at
        $voucher = DB::table('order_vouchers')
            ->join('orders', 'orders.id', '=', 'order_vouchers.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('order_vouchers.applied_at', [$from, $to])
            ->selectRaw('COUNT(DISTINCT order_vouchers.order_id) as used_count, COALESCE(SUM(order_vouchers.applied_amount),0) as used_amount')
            ->first();

        // PIUTANG: outstanding (OPEN/PARTIAL/OVERDUE) per due_date vs now()
        $now = now();
        $piutang = DB::table('receivables')
            ->join('orders', 'orders.id', '=', 'receivables.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereIn('receivables.status', ['OPEN', 'PARTIAL', 'OVERDUE'])
            ->selectRaw('
                COALESCE(SUM(receivables.remaining_amount),0) as remaining,
                COUNT(*) as open_count,
                COALESCE(SUM(CASE WHEN receivables.due_date < ? THEN receivables.remaining_amount ELSE 0 END),0) as overdue_amount,
                SUM(CASE WHEN receivables.due_date < ? THEN 1 ELSE 0 END) as overdue_count
            ', [$now, $now])
            ->first();

        // TOP LAYANAN: top 5 by omzet (order_items.total) dalam window orders.created_at
        $topServices = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('services', 'services.id', '=', 'order_items.service_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('orders.created_at', [$from, $to])
            ->groupBy('order_items.service_id', 'services.name')
            ->selectRaw('order_items.service_id, services.name, SUM(order_items.qty) as qty, SUM(order_items.total) as amount')
            ->orderByDesc('amount')
            ->limit(5)
            ->get();

        return [
            'omzet' => (float) $omzet,
            'transaksi' => (int) $transaksi,
            'ongkir' => (float) $ongkir,
            'voucher' => [
                'used_count' => (int) ($voucher->used_count ?? 0),
                'used_amount' => (float) ($voucher->used_amount ?? 0),
            ],
            'piutang' => [
                'remaining' => (float) ($piutang->remaining ?? 0),
                'open_count' => (int) ($piutang->open_count ?? 0),
                'overdue_amount' => (float) ($piutang->overdue_amount ?? 0),
                'overdue_count' => (int) ($piutang->overdue_count ?? 0),
            ],
            'top_services' => $topServices,
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

- SHA: `bfcc80303024`  
- Ukuran: 9 KB  
- Namespace: `App\Services`

**Class `OrderService`**

Metode Publik:
- **__construct**(private PricingService $pricing, private InvoiceService $invoice,)
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
    ) {
    }

    /**
     * Create order (draft/queue) — hitung total dan harga per cabang.
     * @param array{
     *   branch_id?:string|null,
     *   customer_id?:string|null,
     *   notes?:string|null,
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
            $order->grand_total = $this->dec(((float) $order->subtotal) - ((float) $order->discount));
            $order->due_amount = $this->dec(((float) $order->grand_total) - ((float) $order->paid_amount));
            $order->save();

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
            $order->save();

            // POLA 1 — server-driven: saat masuk DELIVERING, bikin delivery + auto-assign kurir
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
     *   items?: array<int, array{id?:string, service_id:string, qty:float|int, note?:string|null}>
     * } $data
     */
    public function update(Order $order, array $data, User $actor): Order
    {
        return DB::transaction(function () use ($order, $data, $actor) {
            if (array_key_exists('customer_id', $data)) {
                $order->customer_id = $data['customer_id'];
            }
            if (array_key_exists('notes', $data)) {
                $order->notes = $data['notes'];
            }

            if (!empty($data['items'])) {
                // strategi sederhana: hapus & tulis ulang
                $order->items()->delete();

                $subtotal = 0.0;

                foreach ($data['items'] as $row) {
                    $price = (float) app(PricingService::class)->getPrice($row['service_id'], $order->branch_id);
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
                $order->grand_total = $this->dec(((float) $order->subtotal) - ((float) $order->discount));
                $order->due_amount = $this->dec(((float) $order->grand_total) - ((float) $order->paid_amount));
            }

            $order->save();

            // TODO: audit('ORDER_UPDATE', ['order_id' => $order->id, 'actor' => $actor->id]);

            return $order->load(['items.service', 'customer']);
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

### app/Services/UserService.php

- SHA: `722bf91b8f62`  
- Ukuran: 5 KB  
- Namespace: `App\Services`

**Class `UserService`**

Metode Publik:
- **paginate**(array $filters = [], int $perPage = 15) : *LengthAwarePaginator* — @param array{search?:string, branch_id?:string|null} $filters
- **create**(array $data) : *User* — @param array{search?:string, branch_id?:string|null} $filters
- **update**(User $user, array $data) : *User* — @param array{search?:string, branch_id?:string|null} $filters
- **delete**(User $user) : *void* — @param array{search?:string, branch_id?:string|null} $filters
- **resetPassword**(User $user, string $plain) : *void* — @param array{search?:string, branch_id?:string|null} $filters
- **setActive**(User $user, bool $isActive) : *User* — @param array{search?:string, branch_id?:string|null} $filters
- **setRoles**(User $user, array $roles) : *User* — @param array{search?:string, branch_id?:string|null} $filters
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
     * @param array{search?:string, branch_id?:string|null} $filters
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return User::query()
            ->with('roles:id,name')
            ->when(!empty($filters['search']), function ($q) use ($filters) {
                $s = $filters['search'];
                $q->where(function ($w) use ($s) {
                    $w->where('name', 'like', "%{$s}%")
                        ->orWhere('email', 'like', "%{$s}%");
                });
            })
            ->when(!empty($filters['branch_id']), fn($q) => $q->where('branch_id', $filters['branch_id']))
            ->orderBy('name') // atau ->orderByDesc('id') sesuai preferensi
            ->paginate($perPage);
    }

    /**
     * @param array{
     *   name:string,
     *   email:string,
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

- SHA: `40442c028e98`  
- Ukuran: 3 KB  
- Namespace: ``
<details><summary><strong>Lihat Kode Lengkap</strong></summary>

```php
{{-- resources/views/orders/receipt.blade.php --}}
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    @php
        // Hitung sisa, lalu tentukan apakah sudah lunas atau belum
        $sisa = max((float) $order->grand_total - (float) $order->paid_amount, 0);
        $isLunas = $sisa <= 0 && $order->payment_status === 'PAID';
        $docTitle = $isLunas ? 'KUITANSI PEMBAYARAN' : 'TAGIHAN / INVOICE';
    @endphp
    <title>{{ $docTitle }} {{ $order->invoice_no ?? $order->number }}</title>
    <style>
        * {
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        }

        body {
            width: 280px;
            margin: 0;
        }

        h1 {
            font-size: 14px;
            margin: 0 0 4px;
            text-align: center;
        }

        .doc-type {
            font-size: 12px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .meta,
        .totals {
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        td {
            padding: 2px 0;
            vertical-align: top;
        }

        .right {
            text-align: right;
        }

        .sep {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
    </style>
</head>

<body>
    <h1>{{ $branch?->name ?? 'Salve Laundry' }}</h1>
    <div class="doc-type">
        {{ $docTitle }}
    </div>
    <div class="meta">
        No: {{ $order->invoice_no ?? $order->number }}<br>
        Tgl: {{ \Illuminate\Support\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}<br>
        Status Bayar: {{ $order->payment_status }}
    </div>
    <div class="sep"></div>
    <table>
        <tbody>
            @foreach($order->items as $it)
                <tr>
                    <td>{{ $it->service->name ?? 'Layanan' }} x{{ (float) $it->qty }}</td>
                    <td class="right">{{ number_format((float) $it->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="sep"></div>
    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td class="right">{{ number_format((float) $order->subtotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Diskon</td>
            <td class="right">{{ number_format((float) $order->discount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Grand Total</strong></td>
            <td class="right"><strong>{{ number_format((float) $order->grand_total, 0, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td>Dibayar</td>
            <td class="right">{{ number_format((float) $order->paid_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>{{ $isLunas ? 'Sisa' : 'Sisa Tagihan' }}</td>
            <td class="right">
                {{ number_format($sisa, 0, ',', '.') }}
            </td>
        </tr>
    </table>
    <div class="sep"></div>
    <div class="meta">Dicetak: {{ $printedAt->format('d/m/Y H:i') }}</div>
</body>

</html>

```
</details>



## routes/api.php

- SHA: `b4e5509bca81`  
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
| GET | `/orders/{order}/receipt` | `OrderController` | `receipt` |
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

        // Receipt (HTML)
        Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt']);

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

- SHA: `f488a92dd766`  
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
