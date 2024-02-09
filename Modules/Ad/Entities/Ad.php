<?php

namespace Modules\Ad\Entities;

use App\Traits\PaginationTrait;
use App\Traits\Searchable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Modules\Ad\Http\Controllers\AdminAdController;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Modules\Ad\Entities\Ad
 *
 * @property int $id
 * @property string $title
 * @property string $company_name
 * @property int $discount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MediaCollection<int, Media> $image
 * @property-read int|null $image_count
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 *
 * @method static Builder|Ad newModelQuery()
 * @method static Builder|Ad newQuery()
 * @method static Builder|Ad query()
 * @method static Builder|Ad whereCompanyName($value)
 * @method static Builder|Ad whereCreatedAt($value)
 * @method static Builder|Ad whereDiscount($value)
 * @method static Builder|Ad whereId($value)
 * @method static Builder|Ad whereTitle($value)
 * @method static Builder|Ad whereUpdatedAt($value)
 * @method static Builder|Ad formatResult()
 * @method static Builder|Ad paginatedCollection()
 * @method static Builder|Ad searchable(array $columns = [], array $translatedKeys = [], string $handleKeyName = 'handle')
 *
 * @property string $description
 *
 * @method static Builder|Ad whereDescription($value)
 *
 * @mixin Eloquent
 */
class Ad extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, PaginationTrait, Searchable;

    protected $fillable = [
        'title',
        'description',
        'discount',
    ];

    public function image(): MorphMany
    {
        return $this
            ->media()
            ->where('collection_name', AdminAdController::$collectionName)
            ->select(['id', 'model_id', 'disk', 'file_name']);
    }
}
