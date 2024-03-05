<?php

namespace Modules\Order\Entities\Builders;

use Illuminate\Database\Eloquent\Builder;

class OrderQueryBuilder extends Builder
{
    public function whereMine(): static
    {
        $this->where(function($query){
            $query->where('from_user_id', auth()->id())
                ->orWhere('to_user_id', auth()->id());
        });

        return $this;
    }
}
