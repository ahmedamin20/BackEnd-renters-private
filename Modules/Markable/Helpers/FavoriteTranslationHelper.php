<?php

namespace Modules\Markable\Helpers;

class FavoriteTranslationHelper
{
    public static function en(): array
    {
        return [
            'model' => 'Item',
            'toggled' => 'Toggeled Successfully',
        ];
    }

    public static function ar(): array
    {
        return [
            'model' => 'العنصر',
            'toggled' => 'تم التبديل بنجاح',
        ];
    }

    public static function fr(): array
    {
        return [
            'model' => 'Article',
            'toggled' => 'Basculé avec succès',
        ];
    }
}
