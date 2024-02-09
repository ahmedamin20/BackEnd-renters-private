<?php

namespace Modules\Auth\Entities;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Auth\Entities\PasswordReset
 *
 * @property int $handle
 * @property string $code
 * @property string $expire_at
 *
 * @method static Builder|PasswordReset newModelQuery()
 * @method static Builder|PasswordReset newQuery()
 * @method static Builder|PasswordReset query()
 * @method static Builder|PasswordReset whereCode($value)
 * @method static Builder|PasswordReset whereExpireAt($value)
 * @method static Builder|PasswordReset whereHandle($value)
 *
 * @mixin Eloquent
 */
class PasswordReset extends Model
{
    use HasFactory;

    public $primaryKey = 'handle';

    protected $fillable = [
        'handle',
        'code',
        'expire_at',
    ];

    public $timestamps = false;
}
