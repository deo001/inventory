<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'quantity',
        'buying_price',
        'selling_price'
    ];

    protected $append = ['total_price'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class,);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function totalPrice(): Attribute
    {
        return new Attribute(
            get: fn () => $this->quantity * $this->buying_price,
        );
    }
    public static function boot(): void
    {
        parent::boot();

        self::creating(fn ($model) => $model->user_id = auth()->id());
    }

}
