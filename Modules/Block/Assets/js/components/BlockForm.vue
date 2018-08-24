<template>
    <div class="div">
        <div class="content-header">
            <h1>
                {{ trans(`blocks.${blockTitle}`) }}
            </h1>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <a href="/backend">Home</a>
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.block.block.index'}">{{ trans('blocks.blocks') }}
                </el-breadcrumb-item>
                <el-breadcrumb-item :to="{name: 'admin.block.block.create'}">{{ trans(`blocks.${blockTitle}`) }}
                </el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <el-form ref="form" :model="block" label-width="120px" label-position="top"
                 v-loading.body="loading"
                 @keydown="form.errors.clear($event.target.name);">
            <div class="box box-primary">
                <div class="box-body">
                    <el-tabs v-model="activeTab">
                        <el-tab-pane :label="localeArray.name" v-for="(localeArray, locale) in locales"
                                     :key="localeArray.name" :name="locale">
                                    <span slot="label" :class="{'error' : form.errors.has(locale)}">{{ localeArray.name
                                          }}</span>
                            <el-form-item :label="trans('blocks.name')"
                                          :class="{'el-form-item is-error': form.errors.has('.name') }">
                                <el-input v-model="block.name"></el-input>
                                <div class="el-form-item__error" v-if="form.errors.has('.name')"
                                     v-text="form.errors.first('.name')"></div>
                            </el-form-item>

                            <el-form-item :label="trans('blocks.body')"
                                          :class="{'el-form-item is-error': form.errors.has(locale + '.body') }">
                                <component :is="getCurrentEditor()" v-model="block[locale].body"
                                           :value="block[locale].body">
                                </component>

                                <div class="el-form-item__error" v-if="form.errors.has(locale + '.body')"
                                     v-text="form.errors.first(locale + '.body')"></div>
                            </el-form-item>

                            <!--<el-form-item :label="trans('blocks.online')"-->
                                          <!--:class="{'el-form-item is-error': form.errors.has(locale + '.online') }">-->
                                <!--<el-checkbox v-model="block[locale].online">{{ trans('blocks.online') }}</el-checkbox>-->
                                <!--<div class="el-form-item__error" v-if="form.errors.has(locale + '.online')"-->
                                     <!--v-text="form.errors.first(locale + '.online')"></div>-->
                            <!--</el-form-item>-->

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
        </el-form>
        <button v-shortkey="['b']" @shortkey="pushRoute({name: 'admin.block.block.index'})" v-show="false"></button>
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
            blockTitle: {default: null, String},
        },
        data() {
            return {

                block: _(this.locales)
                    .keys()
                    .map(locale => [locale, {
                        online: false,
                        body: '',
                    }])
                    .fromPairs()
                    .value(),

                form: new Form(),
                loading: false,
                activeTab: window.AsgardCMS.currentLocale || 'en',
            };
        },
        methods: {
            onSubmit() {
                this.form = new Form(_.merge(this.block));
                this.loading = true;

                this.form.post(this.getRoute())
                    .then((response) => {
                        console.log('submit', response);
                        this.loading = false;
                        this.$message({
                            type: 'success',
                            message: response.message,
                        });
                        this.$router.push({name: 'admin.block.block.index'});
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
                this.$router.push({name: 'admin.block.block.index'});
            },
            fetchBlock() {
                this.loading = true;
                axios.post(route('api.block.block.find', {block: this.$route.params.blockId}))
                    .then((response) => {
                        this.loading = false;
                        this.block = response.data.data;
                        this.tags = response.data.data.tags;
                    });
            },
            getRoute() {
                if (this.$route.params.blockId !== undefined) {
                    return route('api.block.block.update', {block: this.$route.params.blockId});
                }
                return route('api.block.block.store');
            },
        },
        mounted() {
            if (this.$route.params.blockId !== undefined) {
                this.fetchBlock();
            }
        },
        destroyed() {
            $('.publicUrl').hide();
        },
    };
</script>
