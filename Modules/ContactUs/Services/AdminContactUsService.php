<?php

namespace Modules\ContactUs\Services;

use Modules\ContactUs\Entities\ContactUs;

class AdminContactUsService
{
    public ContactUs $contactUsModel;

    public function __construct()
    {
        $this->contactUsModel = new ContactUs();
    }

    public function index()
    {
        return $this->contactUsModel::query()
            ->latest()
            ->searchable(['name', 'email', 'phone'])
            ->PaginatedCollection();

    }

    public function changeStatus($id): bool
    {
        $testimonials = $this->contactUsModel::whereId($id)->firstOrFail();

        $testimonials->update(['status' => (! $testimonials->status)]);

        if ($testimonials->status == 0) {
            return false;
        }

        return true;
    }
}
