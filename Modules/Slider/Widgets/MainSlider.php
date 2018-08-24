<?php

namespace Modules\Slider\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Modules\Slider\Repositories\SliderRepository;
use Modules\Slider\Transformers\MainFrontSliderTransformer;
use function view;

class MainSlider extends AbstractWidget
{

    protected $config = [
        'model' => null,
        'count' => 4,
        'template' => 'main_slider',
        'slider_name' => null
    ];
    private $sliderRepo = null;

    public function __get($name)
    {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        } else {
            return null;
        }
    }

    public function run(SliderRepository $sliderRepo)
    {
        $this->sliderRepo = $sliderRepo;
        $slider = $this->sliderRepo->findBySystemName($this->config['slider_name'])->first();
        if(isset($slider->slides)){
            $slides = json_encode(MainFrontSliderTransformer::collection($slider->slides));
        } else {
            $slides = [];

        }
        return view('slider::widgets.slider_' . $this->config['template'], [
            'models' => $slides,
        ]);
    }

}
