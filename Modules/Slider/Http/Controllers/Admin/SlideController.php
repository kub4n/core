<?php

namespace Modules\Slider\Http\Controllers\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Media\Repositories\FileRepository;
use Modules\Page\Repositories\PageRepository;
use Modules\Slider\Entities\Slide;
use Modules\Slider\Entities\Slider;
use Modules\Slider\Http\Requests\CreateSlideRequest;
use Modules\Slider\Http\Requests\UpdateSlideRequest;
use Modules\Slider\Repositories\SlideRepository;

class SlideController extends AdminBaseController
{
    /**
     * @var SlideRepository
     */
    private $slide;

    /**
     * @var PageRepository
     */
    private $page;

    /**
     * @var FileRepository
     */
    private $file;

    public function __construct(SlideRepository $slide, PageRepository $page, FileRepository $file)
    {
        parent::__construct();
        $this->slide = $slide;
        $this->page = $page;
        $this->file = $file;
    }

    public function create()
    {

        return view('slider::admin.slides.create');
    }

    public function store(Slider $slider, CreateSlideRequest $request)
    {
        $this->slide->create($this->addSliderId($slider, $request));

        return redirect()
            ->route('admin.slider.slider.edit', [$slider->id])
            ->withSuccess(trans('slider::messages.slide created'));
    }

    public function edit(Slide $slide)
    {
        return view('slider::admin.slides.edit', compact('slide'));
    }

    public function update(Slider $slider, Slide $slide, UpdateSlideRequest $request)
    {
        $this->slide->update($slide, $this->addSliderId($slider, $request));

        return redirect()
            ->route('admin.slider.slider.edit', [$slider->id])
            ->withSuccess(trans('slider::messages.slide updated'));
    }

    /**
     * @param  Slider $slider
     * @param  \Illuminate\Foundation\Http\FormRequest $request
     * @return array
     */
    private function addSliderId(Slider $slider, FormRequest $request)
    {
        return array_merge($request->all(), ['slider_id' => $slider->id]);
    }
}
