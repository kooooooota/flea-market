<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\ExhibitionRequest;
use App\Models\PaymentMethod;
use App\Models\PurchasedItem;
use App\Models\Category;
use App\Enums\Condition;


class ItemController extends Controller
{
    public function index(Request $request)
    {
    $tab = $request->query('tab', 'all');
    $keyword = $request->query('keyword');
    $userId = auth()->id();
    
    $query = Item::query();
    if (auth()->check()) {
        $query->where('user_id', '!=', $userId);
    }

    if (!empty($keyword)) {
        $query->where('name', 'like', "%{$keyword}%");
    }

    if ($tab === 'mylist') {
        if (!auth()->check()) return redirect()->route('login');

        $items = auth()->user()->favoriteProducts()
            ->where('items.user_id', '!=', $userId)
            ->where('items.name', 'like', "%{$keyword}%")
            ->get();
    } else {
        $items = $query->get();
    }

    if ($request->filled('keyword')) {
        $query->where('name', 'like', '%' . $request->keyword . '%');
    }
    
    return view('index', compact('items', 'tab'));
    }

    public function show($id)
    {
        $item = Item::with('categories', 'comments.user.profile')
        ->withCount('likes', 'comments')
        ->findOrFail($id);

        return view('item', compact('item'));
    }

    public function toggle(Item $item)
    {
        auth()->user()->favoriteProducts()->toggle($item->id);

        return back();
    }

    public function comment(CommentRequest $request, Item $item)
    {
        $request->validated();

        $item->comments()->create([
            'body' => $request->body,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'コメントを投稿しました');
    }

    public function checkout(Request $request, $id)
    {
        $shipping = $request->session()->get('shipping_address');
        $item = Item::with('user.profile')->findOrFail($id);
        $paymentMethods = PaymentMethod::all();

        return view('purchase', compact('item', 'paymentMethods', 'shipping'));
    }

    public function toAddressForm($item)
    {
        return view('address', compact('item'));
    }

    public function changeAddress(Request $request, $item)
    {
        $request->session()->put('shipping_address', $request->only([
            'zip_code', 'address', 'building'
        ]));

        return redirect()->route('items.checkout', $item);
    }

    public function exhibit()
    {
        $categories = Category::all();
        $conditions = Condition::cases();
        return view('sell', compact('conditions', 'categories'));
    }

    public function sell(ExhibitionRequest $request)
    {
        $data = $request;

        $path = $request->file('image')->store  ('items', 'public');
        $data['image_path'] = $path;
        $data['user_id'] = auth()->id();

        $data = $request->only(['user_id', 'image_path', 'name', 'brand_name', 'price', 'description', 'condition']);
        Item::create($data);

        return redirect()->route('items.index')->with('success', '商品の出品が完了しました');

    }
}
