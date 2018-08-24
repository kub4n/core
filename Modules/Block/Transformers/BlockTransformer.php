<?php

namespace Modules\Block\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class BlockTransformer extends Resource
{
    public function toArray($request)
    {

        return [

        'id' => $this->id,
            'name' => $this->name,
            'translations' => [
                'body' => optional($this->translate(locale()))->title,
            ],
            'created_at' => $this->created_at,
            'urls' => [
                'delete_url' => route('api.blog.post.destroy', $this->id),
            ],
        ];
    }
}
