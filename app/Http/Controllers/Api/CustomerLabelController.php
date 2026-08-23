<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerLabels\CustomerLabelRequest;
use App\Models\Customer;
use App\Models\CustomerLabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerLabelController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomerLabel::query()->orderBy('name');

        if (($active = $request->query('is_active')) !== null) {
            $query->where('is_active', filter_var($active, FILTER_VALIDATE_BOOLEAN));
        }

        $labels = $query->get();

        $usage = Customer::query()
            ->whereNotNull('tags')
            ->pluck('tags')
            ->flatten()
            ->countBy();

        $labels->each(function (CustomerLabel $label) use ($usage) {
            $label->setAttribute('usage_count', (int) ($usage->get($label->name, 0)));
        });

        return response()->json([
            'data' => $labels,
            'meta'    => [],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    public function store(CustomerLabelRequest $request)
    {
        $label = CustomerLabel::create($request->validated());

        return response()->json([
            'data'    => $label,
            'meta'    => [],
            'message' => 'Created',
            'errors'  => null,
        ], 201);
    }

    public function update(CustomerLabelRequest $request, CustomerLabel $customerLabel)
    {
        $payload = $request->validated();
        $oldName = (string) $customerLabel->name;

        DB::transaction(function () use ($customerLabel, $payload, $oldName) {
            $customerLabel->fill($payload)->save();

            if ((string) $customerLabel->name !== $oldName) {
                $this->renameOnCustomers($oldName, (string) $customerLabel->name);
            }
        });

        return response()->json([
            'data'    => $customerLabel->refresh(),
            'meta'    => [],
            'message' => 'Updated',
            'errors'  => null,
        ]);
    }

    public function destroy(CustomerLabel $customerLabel)
    {
        $used = Customer::query()
            ->whereJsonContains('tags', $customerLabel->name)
            ->exists();

        if ($used) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Label tidak dapat dihapus karena masih dipakai customer.',
                'errors'  => [
                    'name' => ['Label masih dipakai customer.'],
                ],
            ], 422);
        }

        $customerLabel->delete();

        return response()->json([
            'data'    => null,
            'meta'    => [],
            'message' => 'Deleted',
            'errors'  => null,
        ]);
    }

    private function renameOnCustomers(string $oldName, string $newName): void
    {
        Customer::query()
            ->whereJsonContains('tags', $oldName)
            ->chunkById(200, function ($customers) use ($oldName, $newName) {
                foreach ($customers as $customer) {
                    $customer->tags = collect($customer->tags ?? [])
                        ->map(fn($tag) => (string) $tag === $oldName ? $newName : $tag)
                        ->unique()
                        ->values()
                        ->all();

                    $customer->save();
                }
            });
    }
}
