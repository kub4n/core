/* eslint-disable linebreak-style */
import SliderTableServerSide from './components/SliderTableServerSide.vue';
import SliderForm from './components/SliderForm.vue';
import SlideForm from './components/SlideForm.vue';

const locales = window.AsgardCMS.locales;

export default [
    {
        path: '/slider/sliders',
        name: 'admin.slider.sliders.index',
        component: SliderTableServerSide
    },
    {
        path: '/slider/sliders/create',
        name: 'admin.slider.sliders.create',
        component: SliderForm,
        props: {
            locales,
            sliderTitle: 'create slider',
        }
    },
    {
        path: '/slider/sliders/:sliderId/edit',
        name: 'admin.slider.slider.edit',
        component: SliderForm,
        props: {
            locales,
            sliderTitle: 'edit slider',
        }
    },
    {
        path: '/slider/slides/:parent_id/create',
        name: 'admin.slider.slide.create',
        component: SlideForm,
        props: {
            locales,
            slideTitle: 'create slide',
        },
    },
    {
        path: '/slider/slides/:slideId/edit',
        name: 'admin.slider.slide.edit',
        component: SlideForm,
        props: {
            locales,
            slideTitle: 'edit slide',
        }
    }
];
