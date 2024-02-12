<?php

namespace Modules\Category\Entities;

use App\Traits\PaginationTrait;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Category\Http\Controllers\AdminCategoryController;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Modules\Category\Entities\Category
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $image
 * @property-read int|null $image_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Category formatResult()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category paginatedCollection()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category searchByForeignKey(string $foreignKeyColumn, ?string $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category searchable(array $columns = [], array $translatedKeys = [], string $handleKeyName = 'handle')
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Category extends Model implements HasMedia
{
    use InteractsWithMedia, PaginationTrait, Searchable;

    protected $fillable = ['name'];

    public function image(): MorphMany
    {
        return $this
            ->media()
            ->where('collection_name', AdminCategoryController::$collectionName)
            ->select(['id', 'model_id', 'disk', 'file_name']);
    }
}
