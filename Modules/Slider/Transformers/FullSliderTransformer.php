<?php

namespace Modules\Slider\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class FullSliderTransformer extends Resource
{
    public function toArray($request)
    {
        $sliderData = [
            'id' => $this->id,
            'name' => $this->name,
            'system_name' => $this->system_name,
            'active' => intVal($this->active)
        ];
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $supportedLocale) {
            $sliderData[$locale] = [];
            if ($this->translatedAttributes !== null) {
                foreach ($this->translatedAttributes as $translatedAttribute) {
                    $sliderData[$locale][$translatedAttribute] = $this->translateOrNew($locale)->$translatedAttribute;
                }
            }
        }


        return $sliderData;
    }
}
