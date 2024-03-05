<?php

namespace Modules\Category\Entities;

use App\Traits\PaginationTrait;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Category\Http\Controllers\AdminCategoryController;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia, PaginationTrait, Searchable;

    protected $fillable = ['name'];

    public function image(): MorphMany
    {
        return $this
            ->media()
            ->where('collection_name', AdminCategoryController::$collectionName)
            ->select(['id', 'model_id', 'disk', 'file_name']);
    }
}
