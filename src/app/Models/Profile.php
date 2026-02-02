<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Casts\Attribute;

class Profile extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

//     protected function profileImageUrl(): Attribute
// {
//     return Attribute::get(fn () => 
//         $this->image_path 
//             ? asset('storage/' . $this->image_path) 
//             : asset('images/default-avatar.png')
//     );
// }
}
