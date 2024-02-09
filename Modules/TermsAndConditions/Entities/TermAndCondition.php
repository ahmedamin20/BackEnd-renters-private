<?php

namespace Modules\TermsAndConditions\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\TermsAndConditions\Entities\TermAndCondition
 *
 * @property int $id
 * @property string $content
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TermAndCondition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TermAndCondition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TermAndCondition query()
 * @method static \Illuminate\Database\Eloquent\Builder|TermAndCondition whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TermAndCondition whereId($value)
 *
 * @mixin \Eloquent
 */
class TermAndCondition extends Model
{
    use HasFactory;

    protected $fillable = ['content'];

    public $timestamps = false;
}
