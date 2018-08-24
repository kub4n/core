<template>
    <div class="div">
        <div class="content-header">
            <h1>
                {{ trans(`posts.${postTitle}`) }}
            </h1>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <a href="/backend">Home</a>
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.blog.post.index'}">{{ trans('posts.posts') }}
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.blog.post.create'}">{{ trans(`posts.${postTitle}`) }}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <el-form ref="form" :model="post" label-width="120px" label-position="top"
                 v-loading.body="loading"
                 @keydown="form.errors.clear($event.target.name);">
            <el-row :gutter="30">
                <el-col :lg="16" :md="24">
                    <div class="box box-primary">
                        <div class="box-body">
                            <el-tabs v-model="activeTab">
                                <el-tab-pane :label="localeArray.name" v-for="(localeArray, locale) in locales"
                                    :key="localeArray.name" :name="locale">
                                    <span slot="label" :class="{'error' : form.errors.has(locale)}">{{ localeArray.name
                                          }}</span>
                                    <el-form-item :label="trans('posts.title')"
                                        :class="{'el-form-item is-error': form.errors.has(locale + '.title') }">
                                        <el-input v-model="post[locale].title"></el-input>
                                        <div class="el-form-item__error" v-if="form.errors.has(locale + '.title')"
                                             v-text="form.errors.first(locale + '.title')"></div>
                                    </el-form-item>

                                    <el-form-item :label="trans('posts.slug')"
                                        :class="{'el-form-item is-error': form.errors.has(locale + '.slug') }">
                                        <el-input v-model="post[locale].slug">
                                            <el-button slot="prepend" @click="generateSlug($event, locale)">Generate</el-button>
                                        </el-input>
                                        <div class="el-form-item__error" v-if="form.errors.has(locale + '.slug')"
                                             v-text="form.errors.first(locale + '.slug')"></div>
                                    </el-form-item>

                                    <el-form-item :label="trans('posts.content')"
                                        :class="{'el-form-item is-error': form.errors.has(locale + '.content') }">
                                        <component :is="getCurrentEditor()" v-model="post[locale].content" :value="post[locale].content">
                                        </component>

                                        <div class="el-form-item__error" v-if="form.errors.has(locale + '.content')"
                                             v-text="form.errors.first(locale + '.content')"></div>
                                    </el-form-item>

                                    <el-form-item :label="trans('posts.status')"
                                        :class="{'el-form-item is-error': form.errors.has(locale + '.status') }">
                                        <el-checkbox v-model="post[locale].status">{{ trans('posts.status') }}</el-checkbox>
                                        <div class="el-form-item__error" v-if="form.errors.has(locale + '.status')"
                                             v-text="form.errors.first(locale + '.status')"></div>
                                    </el-form-item>
                                    <el-collapse accordion>
                                        <el-collapse-item :title="trans('posts.meta_data') " name="1">
                                            <el-form-item :label="trans('posts.meta_title')">
                                                <el-input v-model="post[locale].meta_title"></el-input>
                                            </el-form-item>
                                            <el-form-item :label="trans('posts.meta_description')">
                                                <el-input type="textarea"
                                                          v-model="post[locale].meta_description"></el-input>
                                            </el-form-item>
                                        </el-collapse-item>
                                        <el-collapse-item :title="trans('posts.facebook_data')" name="2">
                                            <el-form-item :label="trans('posts.og_title')">
                                                <el-input v-model="post[locale].og_title"></el-input>
                                            </el-form-item>
                                            <el-form-item :label="trans('posts.og_description')">
                                                <el-input type="textarea"
                                                          v-model="post[locale].og_description"></el-input>
                                            </el-form-item>
                                            <el-form-item :label="trans('posts.og_type')">
                                                <el-select v-model="post[locale].og_type"
                                                           :placeholder="trans('posts.og_type')">
                                                    <el-option :label="trans('posts.facebook-types.website')"
                                                        value="website"></el-option>
                                                    <el-option :label="trans('posts.facebook-types.product')"
                                                        value="product"></el-option>
                                                    <el-option :label="trans('posts.facebook-types.article')"
                                                        value="article"></el-option>
                                                </el-select>
                                            </el-form-item>
                                        </el-collapse-item>
                                    </el-collapse>

                                    <br>
                                    <br>
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
                </el-col>
                <el-col :lg="8" :md="24">
                    <div class="box box-primary">
                        <div class="box-body">
                            <el-form-item v-if="showCategory" label="Kategoria">
                                <el-select @change="onChange()" v-model="post.category_id" placeholder="Select">
                                    <el-option
                                        v-for="item in categories"
                                        :key="item.id"
                                        :label="item.translations.name"
                                        :value="item.id">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <tags-input namespace="asgardcms/post" v-model="tags" :current-tags="tags"></tags-input>

                            <single-media zone="image" @singleFileSelected="selectSingleFile($event, 'post')"
                                          entity="Modules\Blog\Entities\Post" :entity-id="$route.params.postId"></single-media>
                        </div>
                    </div>
                </el-col>
            </el-row>
        </el-form>
        <button v-shortkey="['b']" @shortkey="pushRoute({name: 'admin.blog.post.index'})" v-show="false"></button>
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
            postTitle: {default: null, String},
        },
        data() {
            return {
                showCategory: true,
                categories: [],

                post: _(this.locales)
                        .keys()
                        .map(locale => [locale, {
                                    title: '',
                                    slug: '',
                                    content: '',
                                    category_id: '',
                                    meta_title: '',
                                    meta_description: '',
                                    og_title: '',
                                    og_description: '',
                                    og_type: '',
                                }])
                        .fromPairs()
                        .merge({medias_single: []})
                        .value(),

                form: new Form(),
                loading: false,
                tags: {},
                activeTab: window.AsgardCMS.currentLocale || 'en',
            };
        },
        methods: {
            onSubmit() {
                this.form = new Form(_.merge(this.post, {tags: this.tags}));
                this.loading = true;

                this.form.post(this.getRoute())
                        .then((response) => {
                            console.log('submit', response);
                            this.loading = false;
                            this.$message({
                                type: 'success',
                                message: response.message,
                            });
                            this.$router.push({name: 'admin.blog.post.index'});
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
            onChange(){
                console.log(this.post.category_id);
            },
            onCancel() {
                this.$router.push({name: 'admin.blog.post.index'});
            },
            generateSlug(event, locale) {
                this.post[locale].slug = this.slugify(this.post[locale].title);
            },
            fetchPost() {
                this.loading = true;
                axios.post(route('api.blog.post.find', {post: this.$route.params.postId}))
                        .then((response) => {
                            this.loading = false;
                            this.post = response.data.data;
                            this.tags = response.data.data.tags;
                        });
            },
            fetchCategories(){
                this.loading = true;
                axios.post(route('api.blog.category.indexServerSide'))
                    .then((response) => {
                        this.loading = false;
                        this.categories = response.data.data;
                        console.log(this.response.data);
                    });

            },
            getRoute() {
                if (this.$route.params.postId !== undefined) {
                    return route('api.blog.post.update', {post: this.$route.params.postId});
                }
                return route('api.blog.post.store');
            },
        },
        mounted() {
            if (this.$route.params.postId !== undefined) {
                this.fetchPost();
            }
            this.fetchCategories();

        },
        destroyed() {
            $('.publicUrl').hide();
        },
    };
</script>
