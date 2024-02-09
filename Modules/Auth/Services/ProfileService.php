<?php

namespace Modules\Auth\Services;

use App\Models\User;
use App\Services\FileOperationService;
use Modules\Auth\Http\Controllers\ProfileController;

class ProfileService
{
    /**
     * @return true|array
     */
    public function handle(array $data): bool|array
    {
        $user = User::whereId(auth()->id())->first();
        $user->update($data);

        // Update User Avatar If Exists
        if (isset($data['avatar'])) {

            $fileOperationService = new FileOperationService();
            $oldAvatar = $user->getFirstMedia(ProfileController::getUsersCollectionName());
            $oldAvatar?->delete();
            $fileOperationService->storeImageFromRequest(
                $user, ProfileController::getUsersCollectionName(),
                'avatar'
            );
        }

        return true;
    }
}
