<?php

namespace Modules\AboutUs\Entities;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\AboutUs\Http\Controllers\AdminAboutUsController;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\AboutUs
 *
 * @property int $id
 * @property array $name
 * @property array $description
 * @property-read MediaCollection<int, Media> $image
 * @property-read int|null $image_count
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 *
 * @method static Builder|AboutUs newModelQuery()
 * @method static Builder|AboutUs newQuery()
 * @method static Builder|AboutUs query()
 * @method static Builder|AboutUs whereDescription($value)
 * @method static Builder|AboutUs whereId($value)
 * @method static Builder|AboutUs whereName($value)
 *
 * @mixin Eloquent
 *
 * @method static Builder|AboutUs whereLocale(string $column, string $locale)
 * @method static Builder|AboutUs whereLocales(string $column, array $locales)
 *
 * @property string $youtube_video_url
 *
 * @method static Builder|AboutUs whereYoutubeVideoUrl($value)
 *
 * @mixin \Eloquent
 */
class AboutUs extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'youtube_video_url',
        'description',
    ];

    public function image(): MorphMany
    {
        return $this
            ->media()
            ->where('collection_name', AdminAboutUsController::$collectionName)
            ->select(['id', 'model_id', 'disk', 'file_name']);
    }
}
