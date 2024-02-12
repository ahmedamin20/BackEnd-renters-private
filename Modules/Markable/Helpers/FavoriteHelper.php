<?php

namespace Modules\Markable\Helpers;

use Modules\Markable\Entities\FavoriteModel;

class FavoriteHelper
{
    const RELATIONSHIP_NAME = 'favorites';

    public static function model(): string
    {
        return FavoriteModel::class;
    }

    public static function resourceFavorite($resource, string $relationName = self::RELATIONSHIP_NAME): ?bool
    {
        return $resource->relationLoaded($relationName) ? (bool) $resource->{$relationName}->first() : null;
    }
}
