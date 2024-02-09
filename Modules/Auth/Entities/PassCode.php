<?php

namespace Modules\Auth\Entities;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Auth\Entities\PassCode
 *
 * @property string $id
 * @property string $user_id
 * @property string $token
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PassCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PassCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PassCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|PassCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassCode whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PassCode whereUserId($value)
 *
 * @mixin \Eloquent
 */
class PassCode extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
        'user_id',
        'token',
    ];

    public $timestamps = false;
}
