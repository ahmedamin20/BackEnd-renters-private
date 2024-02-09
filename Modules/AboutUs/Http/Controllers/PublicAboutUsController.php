<?php

namespace Modules\AboutUs\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\AboutUs\Entities\AboutUs;
use Modules\AboutUs\Transformers\AboutUsResource;

class PublicAboutUsController extends Controller
{
    use HttpResponse;

    public function show(): JsonResponse
    {
        $aboutUs = AboutUs::with('image')->first();

        return $this->resourceResponse(AboutUsResource::make($aboutUs));
    }
}
