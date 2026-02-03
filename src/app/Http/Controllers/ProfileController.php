<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use App\Models\Item;
use App\Models\PurchasedItem;

class ProfileController extends Controller
{
    public function store(ProfileRequest $request)
    {
        $data = $request->validated();

        $isFirstTime = !auth()->user()->profile()->exists();

    if ($request->hasFile('image')) {
        $profile = Profile::where('user_id', auth()->id())->first();
        if ($profile && $profile->image_path) {
            Storage::disk('public')->delete($profile->image_path);
        }
        $path = $request->file('image')->store('profiles', 'public');
        $data['image_path'] = $path;
    }
        
        Profile::updateOrCreate(
            ['user_id' => auth()->id()],
            $data
        );

        if ($isFirstTime) {
            return redirect()->route('items.index');
        }

        return redirect()->route('profile.show');
    }

    public function edit()
    {
        $profile = auth()->user()->profile ?? new Profile();
        return view('profile', compact('profile'));
    }

    public function update(ProfileRequest $request, Profile $profile)
    {
        $profile->update($request->validated());
        return view('mypage');
    }

    public function show(Request $request)
    {
        $page = $request->query('page', 'sell');
        $profile = Profile::where('user_id', auth()->id())->first();

        if ($page === 'sell') {
            $items = auth()->user()->items;
        }
        else {
            $items = auth()->user()->purchased_items()->get();
        }

        return view('mypage', compact('profile', 'items', 'page'));
    }
}
