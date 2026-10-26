<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    // Danh sách sự kiện
    public function index(Request $request)
    {
        $events = Event::with(['category', 'creator'])
            ->withCount([
                'registrations as registered_count' => function ($query) {
                    $query->where('status', 'registered');
                }
            ])
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(
                    'title', 'like',
                    '%' . $request->input('q') . '%'
                );
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('admin.events.index', compact(
            'events', 'categories'
        ));
    }

    // Form tạo sự kiện
    public function create()
    {
        $event = new Event(['status' => 'draft']);

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.events.form', compact(
            'event', 'categories'
        ));
    }

    // Lưu sự kiện mới
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        unset($data['cover_image']);

        $data['slug'] = $this->makeSlug($data['title']);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request
                ->file('cover_image')
                ->store('events', 'public');
        }

        $event = new Event();
        $event->fill($data);

        // Luôn lấy Admin từ phiên đăng nhập
        $event->created_by = $request->user()->id;

        $event->save();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Tạo sự kiện thành công!');
    }

    // Form chỉnh sửa sự kiện
    public function edit(Event $event)
    {
        $categories = Category::where('is_active', true)
            ->orWhere('id', $event->category_id)
            ->orderBy('name')
            ->get();

        return view('admin.events.form', compact(
            'event', 'categories'
        ));
    }

    // Cập nhật sự kiện
    
public function update(Request $request, Event $event)
{
    $data = $this->validateData($request);

    unset($data['cover_image']);

    $oldImage = $event->cover_image;
    $newImage = null;

    if ($request->hasFile('cover_image')) {
        $newImage = $request->file('cover_image')
            ->store('events', 'public');

        $data['cover_image'] = $newImage;
    }

    try {
        DB::transaction(function () use ($event, $data) {

            $lockedEvent = Event::whereKey($event->id)
                ->lockForUpdate()
                ->firstOrFail();

            $registeredCount = $lockedEvent->registrations()
                ->where('status', 'registered')
                ->count();

            if ($data['capacity'] < $registeredCount) {
                throw ValidationException::withMessages([
                    'capacity' => 'Sức chứa không thể nhỏ hơn số người đã đăng ký.'
                ]);
            }

            $lockedEvent->update($data);

        }, 3);
    } catch (\Throwable $e) {
        if ($newImage) {
            Storage::disk('public')->delete($newImage);
        }

        throw $e;
    }

    if ($newImage && $oldImage) {
        Storage::disk('public')->delete($oldImage);
    }

    return redirect()
        ->route('admin.events.index')
        ->with('success', 'Cập nhật sự kiện thành công!');
}


    // Xóa sự kiện
    
public function destroy(Event $event)
{
    $image = null;

    DB::transaction(function () use ($event, &$image) {

        $lockedEvent = Event::whereKey($event->id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($lockedEvent->registrations()->exists()) {
            throw ValidationException::withMessages([
                'event' => 'Sự kiện đã có người đăng ký. Hãy hủy sự kiện thay vì xóa!'
            ]);
        }

        $image = $lockedEvent->cover_image;
        $lockedEvent->delete();

    }, 3);

    if ($image) {
        Storage::disk('public')->delete($image);
    }

    return back()->with('success', 'Xóa sự kiện thành công!');
}


    // Kiểm tra dữ liệu form
    private function validateData(Request $request): array
    {
        return $request->validate([
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id'),
            ],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:10000'],
            'location' => ['required', 'string', 'max:255'],

            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'registration_deadline' => [
                'required',
                'date',
                'before_or_equal:starts_at'
            ],

            'capacity' => ['required', 'integer', 'min:1'],
            'status' => [
                'required',
                Rule::in(['draft', 'published', 'cancelled']),
            ],

            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],
        ]);
    }

    // Sinh slug duy nhất cho sự kiện mới
    private function makeSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'su-kien';
        $slug = $base;
        $number = 2;

        while (Event::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $number++;
        }

        return $slug;
    }
}
