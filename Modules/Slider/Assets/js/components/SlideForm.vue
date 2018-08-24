<template>
    <div class="div">
        <div class="content-header">
            <h1>
                {{ trans(`slides.${slideTitle}`) }}
            </h1>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <a href="/backend">Home</a>
                </el-breadcrumb-item>
                <el-breadcrumb-item v-if="slide.parentName!=null" :to="{name: 'admin.slider.slider.edit', params: {sliderId: this.slide.slider_id}}">{{ this.slide.parentName }}
                </el-breadcrumb-item>
                <el-breadcrumb-item  :to="{name: 'admin.slider.slides.index'}">{{ trans('slides.slides') }}
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.slider.slides.create'}">{{ trans(`slides.${slideTitle}`) }}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <el-form ref="form" :model="slide" label-width="120px" label-position="top"
                 v-loading.body="loading"
                 @keydown="form.errors.clear($event.target.name);">
            <el-row :gutter="30">
                <el-col :lg="24" :md="24">
                    <div class="box box-primary">
                        <div class="box-body">
                            <el-tabs v-model="activeTab">
                                <el-tab-pane :label="localeArray.name" v-for="(localeArray, locale) in locales"
                                    :key="localeArray.name" :name="locale">
                                    <span slot="label" :class="{'error' : form.errors.has(locale)}">{{ localeArray.name
                                          }}</span>
                                    <div class="el-row" :gutter="30">
                                        <el-col  :md="16">
                                            <el-form-item :label="trans('slides.title')"
                                                          :class="{'el-form-item is-error': form.errors.has(locale + '.title') }">
                                                <el-input v-model="slide[locale].title"></el-input>
                                                <div class="el-form-item__error" v-if="form.errors.has(locale + '.title')"
                                                     v-text="form.errors.first(locale + '.title')"></div>
                                            </el-form-item>
                                            <el-form-item :label="trans('slides.caption')"
                                                          :class="{'el-form-item is-error': form.errors.has(locale + '.caption') }">
                                                <el-input :autosize="{ minRows: 4}" type="textarea" v-model="slide[locale].caption"></el-input>

                                                <div class="el-form-item__error" v-if="form.errors.has(locale + '.caption')"
                                                     v-text="form.errors.first(locale + '.caption')"></div>
                                            </el-form-item>

                                            <br>
                                            <br>
                                            <el-form-item :label="trans('slides.uri')"
                                                          :class="{'el-form-item is-error': form.errors.has(locale + '.uri') }">
                                                <el-input v-model="slide[locale].uri"></el-input>
                                                <div class="el-form-item__error" v-if="form.errors.has(locale + '.uri')"
                                                     v-text="form.errors.first(locale + '.uri')"></div>
                                            </el-form-item>
                                            <el-form-item :label="trans('slides.url')"
                                                          :class="{'el-form-item is-error': form.errors.has(locale + '.url') }">
                                                <el-input v-model="slide[locale].url"></el-input>
                                                <div class="el-form-item__error" v-if="form.errors.has(locale + '.url')"
                                                     v-text="form.errors.first(locale + '.url')"></div>
                                            </el-form-item>
                                            <el-form-item>
                                                <el-button type="primary" @click="onSubmit()" :loading="loading">
                                                    {{ trans('core.save') }}
                                                </el-button>
                                                <el-button @click="onCancel()">{{ trans('core.button.back to list') }}
                                                </el-button>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :lg="8" :md="24">
                                                    <single-media zone="image" @singleFileSelected="selectSingleFile($event, 'slide')"
                                                                  entity="Modules\Slider\Entities\Slide" :entity-id="$route.params.slideId"></single-media>
                                        </el-col>
                                    </div>

                                    
                                </el-tab-pane>
                            </el-tabs>
                        </div>
                    </div>
                </el-col>
            </el-row>
        </el-form>
        <button v-shortkey="['b']" @shortkey="pushRoute({name: 'admin.slider.slide.index'})" v-show="false"></button>
    </div>
</template>

<script>
    import axios from 'axios';
    import Form from 'form-backend-validation';
    import Slugify from '../../../../Core/Assets/js/mixins/Slugify';
    import ShortcutHelper from '../../../../Core/Assets/js/mixins/ShortcutHelper';
    import ActiveEditor from '../../../../Core/Assets/js/mixins/ActiveEditor';
    import SingleFileSelector from '../../../../Media/Assets/js/mixins/SingleFileSelector';

    export default {
        mixins: [Slugify, ShortcutHelper, ActiveEditor, SingleFileSelector],
        props: {
            locales: {
                default: null
            },
            slideTitle: {
                default: null,
                String
            }
        },
        data() {
            return {
                slide: _(this.locales)
                    .keys()
                    .map(locale => [locale, {
                        title: '',
                        caption: '',
                        url: '',
                        uri: '',
                        custom_html: ''
                    }])
                    .fromPairs()
                    .merge({
                        medias_single: {},
                        slider_id: '',
                    })
                    .value(),

                form: new Form(),
                loading: false,
                tags: {},
                activeTab: window.AsgardCMS.currentLocale || 'en',
            };
        },
        methods: {
            onSubmit() {
                this.form = new Form(_.merge(this.slide));
                this.loading = true;

                this.form.post(this.getRoute())
                    .then((response) => {
                        console.log('submit', response);
                        this.loading = false;
                        this.$message({
                            type: 'success',
                            message: response.message,
                        });
                        // this.$router.push({name: 'admin.slider.slider.edit', params: {sliderId: scope.row.id}});
                    })
                    .catch((error) => {
                        console.log(error);
                        this.loading = false;
                        this.$notify.error({
                            title: 'Error',
                            message: 'There are some errors in the form.',
                        });
                    });
            },

            onCancel() {
                this.$router.push({
                    name: 'admin.slider.slider.edit',
                    params: {
                        sliderId: this.slide.slider_id
                    }
                });
            },
            fetchSlide() {
                this.loading = true;
                axios.post(route('api.slider.slide.find', {
                        slide: this.$route.params.slideId
                    }))
                    .then((response) => {
                        this.loading = false;
                        this.slide = response.data.data;
                        console.log('this.slide', this.slide);
                    });
            },
            getRoute() {
                if (this.$route.params.slideId !== undefined) {
                    return route('api.slider.slide.update', {
                        slide: this.$route.params.slideId
                    });
                }
                return route('api.slider.slide.store');
            },
        },
        mounted() {
            if (this.$route.params.slideId !== undefined) {
                this.fetchSlide();
            } else {
                this.slide.slider_id = this.$route.params.parent_id;
            }
            console.log('$route.params.sliderId', this.$route.params.parent_id);
        },
        destroyed() {
            $('.publicUrl').hide();
        },
    };

</script>
