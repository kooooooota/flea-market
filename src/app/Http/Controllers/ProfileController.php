<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function store(ProfileRequest $request)
    {
        $data = $request->validated();

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('profiles', 'public');
        $data['image_path'] = $path;
    }
        $data['user_id'] = auth()->id();

        Profile::create($data);
        return view('index');
    }

    public function edit()
    {
        return view('profile');
    }

    public function update(ProfileRequest $request, Profile $profile)
    {
        $profile->update($request->validated());
        return view('mypage');
    }

    public function show()
    {
        $profile = Profile::where('user_id', auth()->id())->first();

        return view('mypage', compact('profile'));
    }
}
