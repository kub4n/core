<?php

namespace Modules\Slider\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class MainFrontSliderTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'img' => $this->thumbnail(),
            'translations' => [
                'title' => $this->title,
                'caption' => $this->caption,
                'uri' => $this->uri
            ],
        ];
    }
}
