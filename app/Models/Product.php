<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_uuid',
        'title',
        'uuid',
        'price',
        'description',
        'metadata',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * @return BelongsTo<Category, Product>
     */
    public function category(): BelongsTo
    {
        return $this->BelongsTo(Category::class, 'category_uuid', 'uuid');
    }

    /**
     * Get the product's brand.
     */
    protected function getBrandAttribute(): Brand|null
    {
        $meta = json_decode($this->metadata);
        if (isset($meta?->brand)) {
            return Brand::whereUuid($meta->brand)->first();
        }
        return null;
    }
}
