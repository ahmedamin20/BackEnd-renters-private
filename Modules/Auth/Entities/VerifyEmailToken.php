<?php

namespace Modules\Auth\Entities;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Auth\Entities\VerifyEmailToken
 *
 * @method static Builder|VerifyEmailToken newModelQuery()
 * @method static Builder|VerifyEmailToken newQuery()
 * @method static Builder|VerifyEmailToken query()
 *
 * @mixin Eloquent
 */
class VerifyEmailToken extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'verify_emails_tokens';

    /**
     * @var string[]
     */
    protected $fillable = [
        'hash',
        'expire_at',
        'user_id',
    ];
}
