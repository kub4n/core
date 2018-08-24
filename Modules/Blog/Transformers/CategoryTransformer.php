<?php

namespace Modules\Blog\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class CategoryTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->format('d-m-Y'),
            'translations' => [
                'name' => optional($this->translate(locale()))->name,
                'slug' => optional($this->translate(locale()))->slug,
//                'status' => optional($this->translate(locale()))->status,
            ],
            'urls' => [
                'delete_url' => route('api.blog.category.destroy', $this->id),
            ],
        ];
    }
}
