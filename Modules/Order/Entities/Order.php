<?php

namespace Modules\Order\Entities;

use App\Models\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Coupon\Entities\Coupon;
use Modules\DeliveryMan\Entities\DeliveryMan;
use Modules\Feature\Entities\FeatureRelation;
use Modules\Product\Entities\Product;

/**
 * Modules\Order\Entities\Order
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int|null $coupon_id
 * @property string $status
 * @property array $order_details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Coupon|null $coupon
 * @property-read \Illuminate\Database\Eloquent\Collection<int, FeatureRelation> $features
 * @property-read int|null $features_count
 * @property-read Product $product
 * @property-read User $user
 *
 * @method static Builder|Order newModelQuery()
 * @method static Builder|Order newQuery()
 * @method static Builder|Order query()
 * @method static Builder|Order whereCouponId($value)
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order whereOrderDetails($value)
 * @method static Builder|Order whereProductId($value)
 * @method static Builder|Order whereStatus($value)
 * @method static Builder|Order whereUpdatedAt($value)
 * @method static Builder|Order whereUserId($value)
 * @method static Builder|Order withProduct(string $selectedColumn = 'id,name,description')
 * @method static Builder|Order withProductDetails()
 *
 * @property int|null $delivery_man_id
 * @property-read DeliveryMan|null $deliveryMan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, FeatureRelation> $features
 *
 * @method static Builder|Order whereDeliveryManId($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, FeatureRelation> $features
 * @property-read \Illuminate\Database\Eloquent\Collection<int, FeatureRelation> $features
 * @property-read \Illuminate\Database\Eloquent\Collection<int, FeatureRelation> $features
 * @property-read \Illuminate\Database\Eloquent\Collection<int, FeatureRelation> $features
 *
 * @mixin Eloquent
 */
class Order extends Model
{
    use HasFactory;

    public $casts = [
        'order_details' => 'array',
    ];

    protected $fillable = [
        'product_id',
        'user_id',
        'status',
        'order_details',
        'coupon_id',
        'delivery_man_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function features(): MorphMany
    {
        return $this->morphMany(FeatureRelation::class, 'featurable');
    }

    public function scopeWithProduct(Builder $query, string $selectedColumns = 'id,name,description'): Builder
    {
        return $query->with("product:$selectedColumns");
    }

    public function scopeWithProductDetails(Builder $query): Builder
    {
        return $query->with(
            [
                'product:id,name,description,category_id' => [
                    'category:id,name',
                    'main_image',
                ],
            ]
        );
    }

    public function agencyProduct()
    {
        return $this->product()->whereUserId(auth()->id());
    }

    public function deliveryMan(): BelongsTo
    {
        return $this
            ->belongsTo(DeliveryMan::class)
            ->select(['id', 'name', 'delivery_license', 'phone']);
    }
}
