<?php

namespace Modules\Order\Entities;

use App\Models\User;
use App\Traits\PaginationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Order\Database\factories\OrderFactory;
use Modules\Order\Entities\Builders\OrderQueryBuilder;
use Modules\Product\Entities\Product;

class Order extends Model
{
    use HasFactory, PaginationTrait;

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'price',
        'status',
        'from_date',
        'to_date',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'price' => 'double',
    ];

    public static function newFactory()
    {
        return OrderFactory::new();
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function newEloquentBuilder($query): OrderQueryBuilder
    {
        return new OrderQueryBuilder($query);
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id')->where('id', '<>', auth()->id());
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id')->where('id', '<>', auth()->id());
    }
}
