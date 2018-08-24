<?php

namespace Modules\Slider\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Traits\NamespacedEntity;
use Modules\Media\Support\Traits\MediaRelation;
use Modules\Page\Entities\Page;
use function app;
use function route;

class Slide extends Model
{
    use Translatable, MediaRelation, NamespacedEntity;

    public $translatedAttributes = [
        'title',
        'caption',
        'uri',
        'url',
        'active',
        'custom_html',
    ];

    protected $fillable = [
        'slider_id',
        'page_id',
        'position',
        'target',
        'title',
        'caption',
        'uri',
        'url',
        'active',
        'external_image_url',
        'custom_html',
    ];
    protected $table = 'slider__slides';

    /**
     * @var string
     */
    private $linkUrl;

    /**
     * @var string
     */
    private $imageUrl;

    public function slider()
    {
        return $this->belongsTo(Slider::class);
    }

    /**
     * Check if page_id is empty and returning null instead empty string
     * @return number
     */
    public function setPageIdAttribute($value)
    {
        $this->attributes['page_id'] = !empty($value) ? $value : null;
    }

    /**
     * @return BelongsTo
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }
    public function thumbnail($thumbname = 'slideThumb') {

        $thumbnail = $this->files()->first();

        if ($thumbnail === null) {

            return '/assets/resources/example-image.png';
        }
        return $thumbnail->getThumbnail($thumbname);
    }
    /**
     * returns slider image src
     * @return string|null full image path if image exists or null if no image is set
     */
    public function getImageUrl()
    {
        if ($this->imageUrl === null) {
            if (!empty($this->external_image_url)) {
                $this->imageUrl = $this->external_image_url;
            } elseif (isset($this->files[0]) && !empty($this->files[0]->path)) {
                $this->imageUrl = $this->files[0]->path;
            }
        }

        return $this->imageUrl;
    }

    /**
     * returns slider link URL
     * @return string|null
     */
    public function getLinkUrl()
    {
        if ($this->linkUrl === null) {
            if (!empty($this->url)) {
                $this->linkUrl = $this->url;
            } elseif (!empty($this->uri)) {
                $this->linkUrl = '/' . app()->getLocale() . '/' . $this->uri;
            } elseif (!empty($this->page)) {
                $this->linkUrl = route('page', ['uri' => $this->page->slug]);
            }
        }

        return $this->linkUrl;
    }
}
