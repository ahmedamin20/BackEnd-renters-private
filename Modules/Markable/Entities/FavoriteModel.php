<?php

namespace Modules\Markable\Entities;

use App\Models\User;
use App\Traits\PaginationTrait;
use App\Traits\UUID;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Maize\Markable\Mark;
use Modules\Markable\Helpers\FavoriteHelper;

/**
 * Modules\Markable\Entities\FavoriteModel
 *
 * @property string $id
 * @property string $user_id
 * @property string $markable_type
 * @property string $markable_id
 * @property string|null $value
 * @property mixed|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $markable
 * @property-read User $user
 *
 * @method static Builder|FavoriteModel newModelQuery()
 * @method static Builder|FavoriteModel newQuery()
 * @method static Builder|FavoriteModel query()
 * @method static Builder|FavoriteModel whereCreatedAt($value)
 * @method static Builder|FavoriteModel whereId($value)
 * @method static Builder|FavoriteModel whereMarkableId($value)
 * @method static Builder|FavoriteModel whereMarkableType($value)
 * @method static Builder|FavoriteModel whereMetadata($value)
 * @method static Builder|FavoriteModel whereUpdatedAt($value)
 * @method static Builder|FavoriteModel whereUserId($value)
 * @method static Builder|FavoriteModel whereValue($value)
 * @method static Builder|FavoriteModel formatResult()
 * @method static Builder|FavoriteModel paginatedCollection(?int $paginationCount = null)
 *
 * @mixin Eloquent
 */
class FavoriteModel extends Mark
{
    use PaginationTrait, UUID;

    public $table = 'markable_favorites';

    public static function markableRelationName(): string
    {
        return FavoriteHelper::RELATIONSHIP_NAME;
    }
}
