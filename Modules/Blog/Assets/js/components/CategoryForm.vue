<template>
    <div class="div">
        <div class="content-header">
            <h1>
                {{ trans(`categories.${categoryTitle}`) }}
            </h1>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <a href="/backend">Home</a>
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.blog.category.index'}">{{ trans('categories.categories') }}
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.blog.category.create'}">{{ trans(`categories.${categoryTitle}`) }}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <el-form ref="form" :model="category" label-width="120px" label-position="top"
                 v-loading.body="loading"
                 @keydown="form.errors.clear($event.target.name);">
            <div class="row">
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-body">
                            <el-tabs v-model="activeTab">
                                <el-tab-pane :label="localeArray.name" v-for="(localeArray, locale) in locales"
                                    :key="localeArray.name" :name="locale">
                                    <span slot="label" :class="{'error' : form.errors.has(locale)}">{{ localeArray.name
                                          }}</span>
                                    <el-form-item :label="trans('categories.name')"
                                        :class="{'el-form-item is-error': form.errors.has(locale + '.name') }">
                                        <el-input v-model="category[locale].name"></el-input>
                                        <div class="el-form-item__error" v-if="form.errors.has(locale + '.name')"
                                             v-text="form.errors.first(locale + '.name')"></div>
                                    </el-form-item>

                                    <el-form-item :label="trans('categories.slug')"
                                        :class="{'el-form-item is-error': form.errors.has(locale + '.slug') }">
                                        <el-input v-model="category[locale].slug">
                                            <el-button slot="prepend" @click="generateSlug($event, locale)">Generate</el-button>
                                        </el-input>
                                        <div class="el-form-item__error" v-if="form.errors.has(locale + '.slug')"
                                             v-text="form.errors.first(locale + '.slug')"></div>
                                    </el-form-item>

                                   

                                    <el-form-item>
                                        <el-button type="primary" @click="onSubmit()" :loading="loading">
                                            {{ trans('core.save') }}
                                        </el-button>
                                        <el-button @click="onCancel()">{{ trans('core.button.cancel') }}
                                        </el-button>
                                    </el-form-item>

                                </el-tab-pane>
                            </el-tabs>
                        </div>
                    </div>
                </div>
            </div>
        </el-form>
        <button v-shortkey="['b']" @shortkey="pushRoute({name: 'admin.blog.category.index'})" v-show="false"></button>
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
            locales: {default: null},
            categoryTitle: {default: null, String},
        },
        data() {
            return {
                value: '',
                category: _(this.locales)
                        .keys()
                        .map(locale => [locale, {
                                    name: '',
                                    slug: '',
                                    content: ''
                                }])
                        .fromPairs()
                        .value(),

                form: new Form(),
                loading: false,
                tags: {},
                activeTab: window.AsgardCMS.currentLocale || 'en',
            };
        },
        methods: {
            onSubmit() {
                this.form = new Form(_.merge(this.category, {tags: this.tags}));
                this.loading = true;

                this.form.post(this.getRoute())
                        .then((response) => {
                            console.log(response);
                            this.loading = false;
                            this.$message({
                                type: 'success',
                                message: response.message,
                            });
                            this.$router.push({name: 'admin.blog.category.index'});
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
                this.$router.push({name: 'admin.blog.category.index'});
            },
            generateSlug(event, locale) {
                this.category[locale].slug = this.slugify(this.category[locale].name);
            },
            fetchCategory() {
                this.loading = true;
                axios.post(route('api.blog.category.find', {category: this.$route.params.categoryId}))
                        .then((response) => {
                            this.loading = false;
                            this.category = response.data.data;
                        });
            },
            getRoute() {
                if (this.$route.params.categoryId !== undefined) {
                    return route('api.blog.category.update', {category: this.$route.params.categoryId});
                }
                return route('api.blog.category.store');
            },
        },
        mounted() {
            if (this.$route.params.categoryId !== undefined) {
                this.fetchCategory();
            }
        },
        destroyed() { 
            $('.publicUrl').hide();
        }
    };
</script>
