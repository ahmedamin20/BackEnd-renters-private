<?php

namespace Modules\User\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Routing\Controller;
use Modules\Auth\Enums\UserTypeEnum;
use Modules\Auth\Transformers\UserResource;


class ClientController extends Controller
{
    use HttpResponse;

    public function index()
    {
        $users = User::whereType(UserTypeEnum::USER)->latest()->with(['avatar', 'frontNational', 'backNational'])->paginatedCollection();

        return $this->paginatedResponse($users, UserResource::class);
    }

    public function show($id)
    {
        $user = User::with(['frontNational', 'backNational'])->where('type', UserTypeEnum::USER)->findOrFail($id);

        return $this->resourceResponse(new UserResource($user));
    }
    public function changeStatus($id)
    {
        $user = User::where('type', UserTypeEnum::USER)->findOrFail($id);
        $user->status = (bool)request('status', false);
        $user->save();

        return $this->okResponse(message: translate_success_message('user', 'updated'));
    }
}
