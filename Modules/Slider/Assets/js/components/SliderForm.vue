<template>
    <div class="div">
        <div class="content-header">
            <h1>
                {{ trans(`sliders.${sliderTitle}`) }}
            </h1>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <a href="/backend">Home</a>
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.slider.sliders.index'}">{{ trans('sliders.sliders') }}
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.slider.sliders.create'}">{{ trans(`sliders.${sliderTitle}`) }}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <el-form ref="form" :model="slider" label-width="120px" label-position="top"
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
                                        <el-col  :md="16" v-if="$route.params.sliderId !== undefined">
                                            <slide-table-server-side :parent-id="$route.params.sliderId" ref="slidesTable"></slide-table-server-side>
                                        </el-col>
                                        <el-col  :md="8" class="border-left">
                                            <el-form-item :label="trans('sliders.title')"
                                                          :class="{'el-form-item is-error': form.errors.has('.name') }">
                                                <el-input v-model="slider.name">

                                                </el-input>
                                                <div class="el-form-item__error" v-if="form.errors.has('.name')"
                                                     v-text="form.errors.first('.name')"></div>
                                            </el-form-item>

                                            <el-form-item :label="trans('sliders.system_name')"
                                                          :class="{'el-form-item is-error': form.errors.has('.system_name') }">
                                                <el-input v-model="slider.system_name">
                                                    <el-button slot="prepend" @click="generateSlug($event)">Generate</el-button>
                                                </el-input>
                                                <div class="el-form-item__error" v-if="form.errors.has('.system_name')"
                                                     v-text="form.errors.first('.system_name')"></div>
                                            </el-form-item>


                                            <el-form-item :label="trans('sliders.form.active')"
                                                          :class="{'el-form-item is-error': form.errors.has('.active') }">
                                                <el-checkbox v-model="slider.active">{{ trans('sliders.form.active') }}</el-checkbox>
                                                <div class="el-form-item__error" v-if="form.errors.has('.active')"
                                                     v-text="form.errors.first('.active')"></div>
                                            </el-form-item>

                                            <br>
                                            <br>
                                            <el-form-item>
                                                <el-button type="primary" @click="onSubmit()" :loading="loading">
                                                    {{ trans('core.save') }}
                                                </el-button>
                                                <el-button @click="onCancel()">{{ trans('core.button.cancel') }}
                                                </el-button>
                                            </el-form-item>
                                        </el-col>
                                    </div>

                                    
                                </el-tab-pane>
                            </el-tabs>
                        </div>
                    </div>
                </el-col>
            </el-row>
        </el-form>
        <button v-shortkey="['b']" @shortkey="pushRoute({name: 'admin.slider.sliders.index'})" v-show="false"></button>
    </div>
</template>

<script>
    import axios from 'axios';
    import Form from 'form-backend-validation';
    import Slugify from '../../../../Core/Assets/js/mixins/Slugify';
    import ShortcutHelper from '../../../../Core/Assets/js/mixins/ShortcutHelper';
    import ActiveEditor from '../../../../Core/Assets/js/mixins/ActiveEditor';
    import SingleFileSelector from '../../../../Media/Assets/js/mixins/SingleFileSelector';
    import SlideTableServerSide from './SlideTableServerSide';

    export default {
        mixins: [Slugify, ShortcutHelper, ActiveEditor, SingleFileSelector],
        props: {
            locales: {default: null},
            sliderTitle: {default: null, String},
        },
        components:{
            SlideTableServerSide
        },
        data() {
            return {
                showCategory: true,
                categories: [],

                slider: _(this.locales)
                        .keys()
                        .map(locale => [locale, {

                                }])
                        .fromPairs()
                        .merge({medias_single: [],
                                system_name: null})
                        .value(),

                form: new Form(),
                loading: false,
                tags: {},
                activeTab: window.AsgardCMS.currentLocale || 'en',
            };
        },
        methods: {
            onSubmit() {
                this.form = new Form(_.merge(this.slider));
                this.loading = true;
                this.form.post(this.getRoute())
                        .then((response) => {
                            this.loading = false;
                            this.$message({
                                type: 'success',
                                message: response.message,
                            });
                            this.$router.push({name: 'admin.slider.sliders.index'});
                            console.log('create-response', response);
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
                this.$router.push({name: 'admin.slider.sliders.index'});
            },
            fetchSlider() {
                this.loading = true;
                axios.post(route('api.slider.sliders.find', {slider: this.$route.params.sliderId}))
                        .then((response) => {
                            this.loading = false;
                            this.slider = response.data.data;
                            if(this.slider.active){
                                this.slider.active=true;
                            }
                            console.log('this.slider', this.slider);
                        });
            },
            generateSlug(event) {
                this.slider.system_name = this.slugify(this.slider.name);
                console.log(this.slider.system_name);
            },
            getRoute() {
                if (this.$route.params.sliderId !== undefined) {
                    return route('api.slider.sliders.update', {slider: this.$route.params.sliderId});
                }
                return route('api.slider.sliders.store');
            },
        },
        mounted() {

            if (this.$route.params.sliderId !== undefined) {
                this.fetchSlider();
            }
        },
        destroyed() {
            $('.publicUrl').hide();
        }
    };
</script>
