<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contacts\ContactCategoryRequest;
use App\Models\ContactCategory;
use Illuminate\Http\Request;

class ContactCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactCategory::query()->orderBy('name');

        if ($search = $request->query('q')) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $this->ok($query->get());
    }

    public function show(ContactCategory $contactCategory)
    {
        return $this->ok($contactCategory);
    }

    public function store(ContactCategoryRequest $request)
    {
        $category = ContactCategory::create($request->validated());

        return $this->ok($category, [], 'Created', 201);
    }

    public function update(ContactCategoryRequest $request, ContactCategory $contactCategory)
    {
        $contactCategory->fill($request->validated())->save();

        return $this->ok($contactCategory->refresh(), [], 'Updated');
    }

    public function destroy(ContactCategory $contactCategory)
    {
        if ($contactCategory->contacts()->exists()) {
            return $this->fail(
                ['name' => ['Kategori masih dipakai kontak.']],
                'Kategori tidak dapat dihapus karena masih dipakai kontak.'
            );
        }

        $contactCategory->delete();

        return $this->ok(null, [], 'Deleted');
    }
}
