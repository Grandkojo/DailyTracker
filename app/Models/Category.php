<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function activities()
    {
        return $this->hasMany(Activity::class, 'category_id', 'category_id');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            if (empty($category->category_id)) {
                $category->category_id = (string) Str::uuid();
            }
        });
    }
}
