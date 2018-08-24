<?php

namespace Modules\Slider\Http\Controllers\Api;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Slider\Repositories\SlideRepository;
use Modules\Slider\Services\SlideOrderer;
use Modules\Slider\Transformers\SlideTransformer;
use Modules\Slider\Entities\Slide;
use Modules\Slider\Transformers\FullSlideTransformer;
use Modules\Slider\Http\Requests\UpdateSlideRequest;
use Modules\Slider\Http\Requests\CreateSlideRequest;

class SlideController extends Controller
{
    /**
     * @var Repository
     */
    private $cache;

    /**
     * @var SlideOrderer
     */
    private $slideOrderer;

    /**
     * @var SlideRepository
     */
    private $slide;

    public function __construct(SlideOrderer $slideOrderer, Repository $cache, SlideRepository $slide)
    {
        $this->cache = $cache;
        $this->slideOrderer = $slideOrderer;
        $this->slide = $slide;
    }


    public function index(Request $request)
    {
        $sliderId = (int)$request->input('sliderId');
        return SlideTransformer::collection($this->slide->getSlidesById($sliderId));
    }

    public function indexServerSide(Request $request)
    {
        return SlideTransformer::collection($this->slide->serverPaginationFilteringFor($request));
    }



    /**
     * Delete a slide
     * @param Request $request
     * @return mixed
     */

    public function find(Slide $slide)
    {
        return new FullSlideTransformer($slide);
    }

    public function store(CreateSlideRequest $request)
    {
        $this->slide->create($request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('slider::messages.slide created'),
        ]);
    }


    public function update(Slide $slide, UpdateSlideRequest $request)
    {
        $this->slide->update($slide, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('slider::messages.slide updated'),
        ]);
    }

    public function destroy(Slide $slide)
    {
        $this->slide->destroy($slide);

        return response()->json([
            'errors' => false,
            'message' => trans('moonblog::messages.post deleted'),
        ]);
    }
}
