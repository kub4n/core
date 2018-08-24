<?php

namespace Modules\Slider\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class FullSlideTransformer extends Resource
{
    public function toArray($request)
    {
        $slideData = [
            'id' => $this->id,
            'slider_id' => $this->slider->id,
            'parentName' => $this->slider->name,
//            'title' => $this->title,
//            'caption' => $this->caption
        ];
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $supportedLocale) {
            $slideData[$locale] = [];

            if ($this->translatedAttributes !== null) {
                foreach ($this->translatedAttributes as $translatedAttribute) {
                    $slideData[$locale][$translatedAttribute] = $this->translateOrNew($locale)->$translatedAttribute;
                }
            }
        }


        return $slideData;
    }
}
