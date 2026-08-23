<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contacts\ContactRequest;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query()->with('categories');

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if (($active = $request->query('is_active')) !== null) {
            $query->where('is_active', filter_var($active, FILTER_VALIDATE_BOOLEAN));
        }

        if ($categoryId = $request->query('category_id')) {
            $query->whereHas('categories', fn($q) => $q->where('contact_categories.id', $categoryId));
        }

        $sortMap = [
            'name'    => 'name',
            'phone'   => 'phone',
            'code'    => 'code',
            'address' => 'address',
        ];

        $sortBy  = $sortMap[(string) $request->query('sort_by')] ?? 'name';
        $sortDir = strtolower((string) $request->query('sort_dir')) === 'desc' ? 'desc' : 'asc';

        $query->orderBy($sortBy, $sortDir);

        $items = $query->paginate((int) $request->query('per_page', 20));

        return $this->ok($items->items(), [
            'current_page' => $items->currentPage(),
            'per_page'     => $items->perPage(),
            'total'        => $items->total(),
            'last_page'    => $items->lastPage(),
        ]);
    }

    public function show(Contact $contact)
    {
        return $this->ok($contact->load('categories'));
    }

    public function store(ContactRequest $request)
    {
        $payload     = $request->validated();
        $categoryIds = $payload['category_ids'] ?? [];
        unset($payload['category_ids']);

        $contact = DB::transaction(function () use ($payload, $categoryIds) {
            $payload['code'] = $this->nextCode();
            $contact         = Contact::create($payload);
            $contact->categories()->sync($categoryIds);

            return $contact;
        });

        return $this->ok($contact->load('categories'), [], 'Created', 201);
    }

    private function nextCode(): string
    {
        $last = Contact::query()
            ->where('code', 'like', 'VND-%')
            ->lockForUpdate()
            ->orderByDesc('code')
            ->value('code');

        $next = $last ? ((int) Str::afterLast($last, '-')) + 1 : 1;

        return 'VND-' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    public function update(ContactRequest $request, Contact $contact)
    {
        $payload     = $request->validated();
        $categoryIds = $payload['category_ids'] ?? null;
        unset($payload['category_ids']);

        DB::transaction(function () use ($contact, $payload, $categoryIds) {
            $contact->fill($payload)->save();

            if ($categoryIds !== null) {
                $contact->categories()->sync($categoryIds);
            }
        });

        return $this->ok($contact->refresh()->load('categories'), [], 'Updated');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return $this->ok(null, [], 'Deleted');
    }
}
