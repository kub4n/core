<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <data-tables :data="data" :actions-def="actionsDef">
                        <el-table-column prop="id" label="Id" width="100">
                        </el-table-column>
                        <el-table-column prop="title" :label="trans('slides.title')">
                        </el-table-column>
                        <el-table-column prop="slug" label="Slug">
                        </el-table-column>
                        <el-table-column prop="created_at" label="Created at">
                        </el-table-column>
                        <el-table-column fixed="right" prop="actions" label="Actions">
                            <template slot-scope="scope">
                                <a class="btn btn-default btn-flat" @click.prevent="goToEdit(scope)"><i
                                        class="fa fa-pencil-alt"></i></a>

                                <delete-button :scope="scope" :rows="data" :translations="translations">
                                </delete-button>
                            </template>
                        </el-table-column>
                    </data-tables>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    let data;

    export default {
        data() {
            return {
                data,
                meta: {},
                order_meta: {},
                links: {},
                actionsDef: {
                    def: [{
                        name: this.trans('slides.create-slide'),
                        icon: 'edit',
                        handler: () => {
                            this.$router.push({name: 'admin.slider.slide.create'});
                        },
                    }],
                },
            };
        },
        methods: {
            fetchData() {
                axios.get(route('api.slider.slide.index'))
                    .then((response) => {
                        console.log(response);
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;
                    });
            },
            goToEdit(scope) {
                this.$router.push({name: 'admin.slider.slide.edit', params: {slideId: scope.row.id}});
            },
        },
        mounted() {
            this.fetchData();
        },
    };
</script>
