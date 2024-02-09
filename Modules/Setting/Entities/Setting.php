<?php

namespace Modules\Setting\Entities;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Setting
 *
 * @property int $id
 * @property string $address
 * @property array $name
 * @property string $social_links
 * @property string $phones
 * @property-read MediaCollection<int, Media> $logo
 * @property-read int|null $logo_count
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 *
 * @method static Builder|Setting newModelQuery()
 * @method static Builder|Setting newQuery()
 * @method static Builder|Setting query()
 * @method static Builder|Setting whereAddress($value)
 * @method static Builder|Setting whereId($value)
 * @method static Builder|Setting whereName($value)
 * @method static Builder|Setting wherePhones($value)
 * @method static Builder|Setting whereSocialLinks($value)
 *
 * @mixin Eloquent
 *
 * @method static Builder|Setting whereLocale(string $column, string $locale)
 * @method static Builder|Setting whereLocales(string $column, array $locales)
 *
 * @property string $title
 * @property string $facebook
 * @property string $whatsapp
 * @property string|null $youtube
 *
 * @method static Builder|Setting whereFacebook($value)
 * @method static Builder|Setting whereTitle($value)
 * @method static Builder|Setting whereWhatsapp($value)
 * @method static Builder|Setting whereYoutube($value)
 *
 * @mixin \Eloquent
 */
class Setting extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title',
        'address',
        'phone',
        'email',
        'twitter',
        'instagram',
        'linkedin',
        'facebook',
        'whatsapp',
        'youtube',
        'working_hours',
    ];
}
