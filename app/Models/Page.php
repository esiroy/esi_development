<?php

namespace App\Models;
use Str;
use Illuminate\Database\Eloquent\SoftDeletes;


use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
  
    use SoftDeletes;
    
    public $table = 'pages';

    protected $guarded = array('created_at', 'updated_at');

    protected static function boot()
    {
        parent::boot();

        // Automatically generate slug when creating a new page
        static::creating(function ($page) {
            $page->slug = self::generateUniqueSlug($page->title);
        });
    }

    // Generate a unique slug
    public static function generateUniqueSlug($title)
    {
        // Start with the basic slug
        $slug = Str::slug($title);
        $originalSlug = $slug;

        // Find if any page already uses the same slug
        $count = 1;

        // Loop until we find a unique slug, considering soft-deleted entries
        while (Page::withTrashed()->where('slug', $slug)->exists()) {
            // Append a dash and count to the original slug
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }    

}
