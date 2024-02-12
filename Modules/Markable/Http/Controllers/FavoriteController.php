<?php

namespace Modules\Markable\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Routing\Controller;
use Modules\Markable\Helpers\FavoriteHelper;
use Modules\Markable\Http\Requests\FavoriteToggleRequest;
use Modules\Product\Entities\BranchProduct;

class FavoriteController extends Controller
{
    use HttpResponse;

    public static array $allowedTypes = [
        'product' => BranchProduct::class,
    ];

    public static array $hasStatus = [
        'product' => true,
    ];

    public function __invoke(FavoriteToggleRequest $request)
    {
        $modelType = $request->input('model_type');
        $modelID = $request->input('model_id');
        $errors = [];

        $modelObject = (static::$allowedTypes[$modelType])::query()
            ->whereId($modelID)
            ->when(static::$hasStatus[$modelType], fn ($query) => $query->whereStatus(true))
            ->firstOr(function () use (&$errors) {
                $errors['model_id'] = translate_error_message('model', 'not_exists');
            });

        if ($modelObject) {
            FavoriteHelper::model()::toggle($modelObject, auth()->user());

            return $this->okResponse(
                message: translate_success_message('model', 'toggled')
            );
        }

        return $this->validationErrorsResponse($errors);
    }
}
