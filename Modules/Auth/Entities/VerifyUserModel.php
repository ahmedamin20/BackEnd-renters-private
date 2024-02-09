<?php

namespace Modules\Auth\Entities;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Auth\Entities\VerifyUserModel
 *
 * @property int $id
 * @property string $handle
 * @property string $code
 * @property string $expire_at
 *
 * @method static Builder|VerifyUserModel newModelQuery()
 * @method static Builder|VerifyUserModel newQuery()
 * @method static Builder|VerifyUserModel query()
 * @method static Builder|VerifyUserModel whereCode($value)
 * @method static Builder|VerifyUserModel whereExpireAt($value)
 * @method static Builder|VerifyUserModel whereHandle($value)
 * @method static Builder|VerifyUserModel whereId($value)
 *
 * @mixin Eloquent
 */
class VerifyUserModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'handle',
        'code',
        'expire_at',
    ];

    public $timestamps = false;

    public $table = 'verify_user_tokens';
}
