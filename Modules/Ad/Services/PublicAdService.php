<?php

namespace Modules\Ad\Services;

use Illuminate\Support\Collection;
use Modules\Ad\Entities\Ad;

class PublicAdService
{
    private Ad $adModel;

    public function __construct()
    {
        $this->adModel = new Ad();
    }

    public function index(): Collection
    {
        return $this->adModel::with('image')->inRandomOrder()->limit(15)->get();
    }
}
