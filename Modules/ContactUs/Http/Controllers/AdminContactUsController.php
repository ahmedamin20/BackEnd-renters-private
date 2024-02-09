<?php

namespace Modules\ContactUs\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Modules\ContactUs\Entities\ContactUs;
use Modules\ContactUs\Services\AdminContactUsService;
use Modules\ContactUs\Transformers\ContactUsResource;

class AdminContactUsController extends Controller
{
    public AdminContactUsService $admincontactUsService;

    public function __construct(AdminContactUsService $adminContactUsService)
    {
        $this->admincontactUsService = $adminContactUsService;
    }

    use HttpResponse;

    public function index(): JsonResponse
    {
        $result = $this->admincontactUsService->index();

        return $this->paginatedResponse($result, ContactUsResource::class);
    }

    public function destroy($id): JsonResponse
    {
        $contactUs = ContactUs::whereId($id)->firstOrFail();
        $contactUs->delete();

        return $this->okResponse(message: translate_success_message('message', 'deleted'));
    }

    public function changeStatus($id): JsonResponse
    {
        $result = $this->admincontactUsService->changeStatus($id);

        if (! $result) {
            return $this->okResponse(message: translate_success_message('testimonial', 'disabled'));
        }

        return $this->okResponse(message: translate_success_message('testimonial', 'enabled'));
    }
}
