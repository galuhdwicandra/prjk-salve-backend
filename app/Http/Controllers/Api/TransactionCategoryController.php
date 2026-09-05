<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionCategories\TransactionCategoryRequest;
use App\Models\Expense;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = TransactionCategory::query()
            ->with(['inAccount:id,code,name', 'outAccount:id,code,name'])
            ->orderBy('name');

        if (($active = $request->query('is_active')) !== null) {
            $query->where('is_active', filter_var($active, FILTER_VALIDATE_BOOLEAN));
        }

        if (filter_var($request->query('cash_in'), FILTER_VALIDATE_BOOLEAN)) {
            $query->where('cash_in', true);
        }

        if (filter_var($request->query('cash_out'), FILTER_VALIDATE_BOOLEAN)) {
            $query->where('cash_out', true);
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $this->ok($query->get());
    }

    public function store(TransactionCategoryRequest $request)
    {
        $category = TransactionCategory::create($request->validated());

        return $this->ok($category, [], 'Created', 201);
    }

    public function update(TransactionCategoryRequest $request, TransactionCategory $transactionCategory)
    {
        $transactionCategory->fill($request->validated())->save();

        return $this->ok($transactionCategory->refresh(), [], 'Updated');
    }

    public function setDefault(TransactionCategory $transactionCategory)
    {
        if (! $transactionCategory->cash_out || ! $transactionCategory->is_active) {
            return $this->fail(
                ['transaction_category_id' => ['Kategori harus aktif dan berjenis uang keluar.']],
                'Kategori tidak dapat dipakai sebagai biaya admin transfer.'
            );
        }

        DB::transaction(function () use ($transactionCategory) {
            TransactionCategory::query()
                ->where('cash_out', true)
                ->where('id', '!=', $transactionCategory->id)
                ->update(['is_default' => false]);

            $transactionCategory->forceFill(['is_default' => true])->save();
        });

        return $this->ok($transactionCategory->refresh(), [], 'Updated');
    }

    public function destroy(TransactionCategory $transactionCategory)
    {
        if ($transactionCategory->is_default) {
            return $this->fail(
                ['name' => ['Kategori bawaan tidak dapat dihapus, arsipkan saja.']],
                'Kategori bawaan tidak dapat dihapus.'
            );
        }

        $used = Expense::query()->where('category', $transactionCategory->name)->exists();

        if ($used) {
            return $this->fail(
                ['name' => ['Kategori masih dipakai transaksi.']],
                'Kategori tidak dapat dihapus karena masih dipakai transaksi.'
            );
        }

        $transactionCategory->delete();

        return $this->ok(null, [], 'Deleted');
    }
}
