<?php

namespace Modules\Markable\Traits;

use Illuminate\Database\Eloquent\Builder;
use Modules\Markable\Actions\MakeModelFavorable;
use Modules\Markable\Helpers\FavoriteHelper;

trait Favorable
{
    public function whereHasFavorites()
    {
        return $this->whereHas(FavoriteHelper::RELATIONSHIP_NAME, function ($query) {
            $query->whereUserId(auth()->id());
        });
    }

    public function withFavorites(): mixed
    {
        return $this->with([FavoriteHelper::RELATIONSHIP_NAME => function ($query) {
            $query->whereUserId(auth()->id());
        }]);
    }

    public function getFavorites()
    {
        return (new MakeModelFavorable())->handle($this);
    }
}
