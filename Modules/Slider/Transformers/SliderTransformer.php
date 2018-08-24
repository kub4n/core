<?php

namespace Modules\Slider\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class SliderTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->format('d-m-Y'),
            'active' => $this->active,
            'translations' => [
                'title' => $this->name,
                'system_name' => $this->system_name,
            ],
            'urls' => [
                'delete_url' => route('api.slider.sliders.destroy', $this->id),
            ],
        ];
    }
}
