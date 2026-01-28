<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;


class ItemController extends Controller
{
    public function index(Request $request)
{
    $tab = $request->query('tab', 'all'); // デフォルトは 'all'
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

public function show(Item $item)
{
    return view('item', compact('item'));
}

public function checkout(Item $item)
{
    return view('purchase', compact('item'));
}

public function toggle(Item $item)
{
    auth()->user()->favoriteProducts()->toggle($item->id);

    return back();
}


}
