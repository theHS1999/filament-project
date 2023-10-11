<?php

namespace App\Models;

use App\Models\RepositoryModel as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use SoftDeletes, Searchable;

    protected $table = 'products';

    protected $fillable = [
        'title',
        'url',
        'website',
        'price',
        'images',
        'description',
        'specifications',
        'ean'
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public static $websites = [
        'plieger',
        'bouwmaat',
        'gamma',
        'hornbach',
        'karwei',
        'praxis'
    ];

    public static $website_logos_path = [
        'plieger' => '/img/plieger-logo.jpg',
        'bouwmaat' => '/img/bouwmaat-logo.jpg',
        'gamma' => '/img/gamma-logo.jpg',
        'hornbach' => '/img/hornbach-logo.jpg',
        'karwei' => '/img/karwei-logo.jpg',
        'praxis' => '/img/praxis-logo.jpg',
    ];

    public function searchableAs()
    {
        return 'products_index';
    }

    public function toSearchableArray()
    {
        return $this->only(['title', 'ean']);
    }

    protected function makeAllSearchableUsing($query)
    {
        return $query->with('categories');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }
}
