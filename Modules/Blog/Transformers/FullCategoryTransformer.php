<?php

namespace Modules\Blog\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class FullCategoryTransformer extends Resource
{
    public function toArray($request)
    {
        $categoryData = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            
        ];

        foreach (LaravelLocalization::getSupportedLocales() as $locale => $supportedLocale) {
            $categoryData[$locale] = [];
            foreach ($this->translatedAttributes as $translatedAttribute) {
                $categoryData[$locale][$translatedAttribute] = $this->translateOrNew($locale)->$translatedAttribute;
            }
        }
        return $categoryData;
        
    }
}
