<?php

namespace Modules\Slider\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class SlideTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'active' => $this->active,
            'translations' => [
                'title' => $this->title
            ],
            'urls' => [
                'delete_url' => route('api.slider.slides.destroy', $this->id),
            ],
        ];
    }
}
