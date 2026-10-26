<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    // Hiển thị danh sách chuyên mục
    public function index(Request $request)
    {
        $categories = Category::withCount('events')
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(
                    'name',
                    'like',
                    '%' . $request->input('q') . '%'
                );
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    // Hiển thị form thêm chuyên mục
    public function create()
    {
        $category = new Category(['is_active' => true]);

        return view('admin.categories.form', compact('category'));
    }

    // Lưu chuyên mục mới
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('categories', 'name'),
            ],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $data['slug'] = $this->makeSlug($data['name']);

        Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Thêm chuyên mục thành công!');
    }

    // Hiển thị form sửa chuyên mục
    public function edit(Category $category)
    {
        return view('admin.categories.form', compact('category'));
    }

    // Cập nhật chuyên mục
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('categories', 'name')
                    ->ignore($category->id),
            ],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        if ($data['name'] !== $category->name) {
            $data['slug'] = $this->makeSlug(
                $data['name'],
                $category->id
            );
        }

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Cập nhật chuyên mục thành công!');
    }

    // Xóa chuyên mục
    public function destroy(Category $category)
    {
        if ($category->events()->exists()) {
            return back()->with(
                'error',
                'Không thể xóa chuyên mục đang có sự kiện!'
            );
        }

        $category->delete();

        return back()->with('success', 'Xóa chuyên mục thành công!');
    }

    // Tạo slug không trùng lặp
    private function makeSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'chuyen-muc';
        $slug = $base;
        $number = 2;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, function ($query) use ($ignoreId) {
                    $query->where('id', '!=', $ignoreId);
                })
                ->exists()
        ) {
            $slug = $base . '-' . $number++;
        }

        return $slug;
    }
}
