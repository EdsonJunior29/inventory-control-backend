<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Product extends Model
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    /**
     * The suppliers that the product Belongs To Many.
     */
    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class);
    }


    /**
     * The category that the product belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * The status of the product.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}