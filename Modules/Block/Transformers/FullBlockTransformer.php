<?php

namespace Modules\Block\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class FullBlockTransformer extends Resource
{
    public function toArray($request)
    {
        $blockData = [
            'id' => $this->id,
            'name' => $this->name,
        ];
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $supportedLocale) {
            $blockData[$locale] = [];

            if ($this->translatedAttributes !== null) {
                foreach ($this->translatedAttributes as $translatedAttribute) {
                    $blockData[$locale][$translatedAttribute] = $this->translateOrNew($locale)->$translatedAttribute;
                }
            }
        }


        return $blockData;
    }
}
