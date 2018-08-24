<?php

namespace Modules\Blog\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class FullPostTransformer extends Resource
{
    public function toArray($request)
    {
        $postData = [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'category_id' => $this->category_id
        ];
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $supportedLocale) {
            $postData[$locale] = [];

            if ($this->translatedAttributes !== null) {
                foreach ($this->translatedAttributes as $translatedAttribute) {
                    $postData[$locale][$translatedAttribute] = $this->translateOrNew($locale)->$translatedAttribute;
                }
            }
        }

        foreach ($this->tags as $tag) {
            $postData['tags'][] = $tag->name;
        }
        return $postData;
    }
}
