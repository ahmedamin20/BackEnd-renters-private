<?php

namespace Modules\Ad\Services;

use App\Services\FileOperationService;
use Modules\Ad\Entities\Ad;
use Modules\Ad\Http\Controllers\AdminAdController;

class AdminAdService
{
    protected Ad $adModel;

    public function __construct()
    {
        $this->adModel = new Ad();
    }

    public function index()
    {
        return Ad::latest('id')
            ->with('image')
            ->searchable(['title'])
            ->latest()
            ->paginatedCollection();
    }

    public function store(array $data): bool
    {
        return $this->createOrUpdate($data);
    }

    public function update(array $data, Ad $ad): bool
    {
        return $this->createOrUpdate($data, $ad);
    }

    protected function createOrUpdate(array $data, Ad $ad = null): bool
    {
        $inUpdate = (bool) $ad;
        $fileOperationService = new FileOperationService();

        if (! $inUpdate) {
            $ad = Ad::create($data);
        } else {
            $ad->update($data);
        }

        if (isset($data['image'])) {
            $ad->getFirstMedia(AdminAdController::$collectionName)?->delete();
            $fileOperationService->storeImageFromRequest(
                $ad,
                AdminAdController::$collectionName,
                'image'
            );
        }

        return true;
    }
}
