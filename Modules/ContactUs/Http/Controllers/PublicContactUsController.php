<?php

namespace Modules\ContactUs\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\ContactUs\Entities\ContactUs;
use Modules\ContactUs\Http\Requests\ContactUsRequest;
use Modules\ContactUs\Transformers\ContactUsResource;

class PublicContactUsController extends Controller
{
    use HttpResponse;

    public function index(): JsonResponse
    {
        $contacts = ContactUs::whereStatus(1)
            ->latest('created_at')
            ->searchable(['name', 'email', 'phone'])
            ->PaginatedCollection();

        return $this->paginatedResponse($contacts, ContactUsResource::class);
    }

    public function store(ContactUsRequest $request): JsonResponse
    {
        ContactUs::create($request->validated());

        return $this->okResponse(
            message: translate_success_message('message', 'sent')
        );
    }
}
