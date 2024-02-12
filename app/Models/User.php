<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Helpers\MediaHelper;
use App\Traits\PaginationTrait;
use App\Traits\Searchable;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Modules\Auth\Enums\AuthEnum;
use Modules\Auth\Enums\UserStatusEnum;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Auth\Traits\HasPassCode;
use Modules\Chat\Contracts\ChatInterface;
use Modules\Chat\Traits\ChatTrait;
use Modules\Payment\Traits\StripePaymentTrait;
use Modules\PricePlan\Entities\PricePlan;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @property string $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed|null $password
 * @property string $type
 * @property int $status
 * @property string|null $pass_code
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $avatar
 * @property-read int|null $avatar_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Auth\Entities\PassCode> $passCodes
 * @property-read int|null $pass_codes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Role\Entities\PermissionModel> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Role\Entities\RoleModel> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 *
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static Builder|User formatResult()
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User paginatedCollection()
 * @method static Builder|User permission($permissions, $without = false)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null, $without = false)
 * @method static Builder|User searchByForeignKey(string $foreignKeyColumn, ?string $value = null)
 * @method static Builder|User searchable(array $columns = [], array $translatedKeys = [], string $handleKeyName = 'handle')
 * @method static Builder|User whereActive()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassCode($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereStatus($value)
 * @method static Builder|User whereType($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereValidType(bool $isMobile)
 * @method static Builder|User withoutPermission($permissions)
 * @method static Builder|User withoutRole($roles, $guard = null)
 *
 * @property int|null $current_price_plan_id
 * @property string|null $last_time_active
 * @property string|null $stripe_customer_id
 * @property string|null $social_provider
 * @property string|null $price_plan_expires_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Payment\Entities\Card> $creditCards
 * @property-read int|null $credit_cards_count
 * @property-read PricePlan|null $pricePlan
 *
 * @method static Builder|User userChatInfo($userID = null, $otherUserID = null)
 * @method static Builder|User whereCurrentPricePlanId($value)
 * @method static Builder|User whereLastTimeActive($value)
 * @method static Builder|User wherePricePlanExpiresAt($value)
 * @method static Builder|User whereSocialProvider($value)
 * @method static Builder|User whereStripeCustomerId($value)
 * @method static Builder|User whereUserExists($userId)
 * @method static Builder|User withAvatar()
 *
 * @mixin \Eloquent
 */
class User extends Authenticatable implements HasMedia
{
    use HasApiTokens,
        HasFactory,
        HasPassCode,
        HasRoles,
        InteractsWithMedia,
        Notifiable,
        PaginationTrait,
        Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'pass_code',
        'type',
        'current_price_plan_id',
        'email_verified_at',
        'status',
        'social_provider',
        'price_plan_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function scopeWhereActive($query): Builder
    {
        return $query->whereStatus(UserStatusEnum::ACTIVE);
    }

    //    public function password(): Attribute
    //    {
    //        return Attribute::set(fn ($val) => ! Hash::check($val, $this->password) ? Hash::make($val) : $this->password);
    //    }

    public function avatar(): MorphMany
    {
        return MediaHelper::mediaRelationship($this, AuthEnum::AVATAR_RELATIONSHIP_NAME);
    }

    public function scopeWhereValidType(Builder $query, bool $isMobile): Builder
    {
        $types = [
            UserTypeEnum::USER,
        ];

        if ($isMobile) {
            return $query->whereIn('type', $types);
        }

        return $query->whereNotIn('type', $types);
    }

    public function pricePlan()
    {
        return $this->belongsTo(PricePlan::class, 'current_price_plan_id');
    }

    public function scopeWhereIsEmployee(Builder $query)
    {
        return $query->whereType(UserTypeEnum::ADMIN_EMPLOYEE);
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }

    public function scopeWithAvatar()
    {

    }
}
