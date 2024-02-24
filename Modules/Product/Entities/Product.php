<?php

namespace Modules\Product\Entities;

use App\Models\User;
use App\Traits\PaginationTrait;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Maize\Markable\Mark;
use Maize\Markable\Markable;
use Maize\Markable\Models\Favorite;
use Modules\Category\Entities\Category;
use Modules\Markable\Traits\Favorable;
use Modules\Product\Http\Controllers\ClientProductController;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Modules\Product\Entities\Product
 *
 * @property int $id
 * @property string $name
 * @property int $quantity
 * @property float $price
 * @property string $description
 * @property int $category_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Category $category
 * @property-read Collection<int, FeatureRelation> $features
 * @property-read int|null $features_count
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read MediaCollection<int, Media> $other_images
 * @property-read int|null $other_images_count
 * @property-read User $user
 *
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product whereCategoryId($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereHasFavorites()
 * @method static Builder|Product whereHasMark(Mark $mark, Model $user, ?string $value = null)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product wherePrice($value)
 * @method static Builder|Product whereQuantity($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @method static Builder|Product whereUserId($value)
 * @method static Builder|Product withFavorites()
 * @method static Builder|Product withCategory()
 * @method static Builder|Product withMainImage()
 * @method static Builder|Product withOtherImages()
 *
 */
class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Markable, PaginationTrait, Favorable, Searchable;

    /**
     * @var array|string[]
     */
    protected static array $marks = [
        Favorite::class,
    ];

    protected $casts = [
        'rating_average' => 'double',
    ];

    protected $fillable = [
        'name',
        'price',
        'description',
        'category_id',
        'user_id',
        'health',
        'minimum_days',
        'maximum_days',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function other_images(): MorphMany
    {
        return $this
            ->media()
            ->where('collection_name', 'other_images')
            ->select(['id', 'model_id', 'disk', 'file_name']);
    }

    public function main_image()
    {
        return $this
            ->media()
            ->where('collection_name', 'main_image')
            ->select(['id', 'model_id', 'disk', 'file_name']);
    }
}
