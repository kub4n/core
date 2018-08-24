<?php

namespace Modules\Slider\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Slider\Entities\Slider;
use Modules\Slider\Http\Requests\CreateSliderRequest;
use Modules\Slider\Http\Requests\UpdateSliderRequest;
use Modules\Slider\Repositories\SliderRepository;
use Modules\Slider\Transformers\FullSliderTransformer;
use Modules\Slider\Transformers\SliderTransformer;

class SliderController extends Controller
{
    /**
     * @var SliderRepository
     */
    private $slider;

    public function __construct(SliderRepository $slider)
    {
        $this->slider = $slider;
    }

    public function index()
    {
        return SliderTransformer::collection($this->slider->all());
    }

    public function indexServerSide(Request $request)
    {
        return SliderTransformer::collection($this->slider->serverPaginationFilteringFor($request));
    }

    public function store(CreateSliderRequest $request)
    {
        $this->slider->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('slider::messages.slider created'),
        ]);
    }

    public function find(Slider $slider)
    {
        return new FullSliderTransformer($slider);
    }

    public function update(Slider $slider, UpdateSliderRequest $request)
    {
        $this->slider->update($slider, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('slider::messages.slider updated'),
        ]);
    }

    public function destroy(Slider $slider)
    {
        $this->slider->destroy($slider);

        return response()->json([
            'errors' => false,
            'message' => trans('slider::messages.slider deleted'),
        ]);
    }
}
