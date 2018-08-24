<template>
    <div class="div">
        <div class="box-body">
            <div class="content-header">
                <h1>
                    {{ trans('sliders.slides') }}
                </h1>
            </div>

            <router-link :to="{name: 'admin.slider.slide.create', params:{ parent_id: this.parentId}}">
                <el-button type="primary"><i class="el-icon-edit"></i>
                    {{ trans('slides.button.create slide') }}
                </el-button>
            </router-link>
            <div class="search el-col el-col-5">

            </div>

            <el-table
                    :data="data"
                    stripe
                    style="width: 100%"
                    ref="SlideTable"
                    v-loading.body="tableIsLoading"
                    @sort-change="handleSortChange"
                    @selection-change="handleSelectionChange">
                <el-table-column
                        type="selection"
                        width="55">
                </el-table-column>
                <!--<el-table-column :label="trans('posts.status')" width="100">-->
                <!--<template slot-scope="scope">-->
                <!--<i class="fa fa-circle" :class="(scope.row.translations.status === true) ? 'text-success':'text-danger'"></i>-->
                <!--</template>-->
                <!--</el-table-column>-->
                <el-table-column prop="id" label="Id" width="75" sortable="custom">
                </el-table-column>
                <el-table-column prop="translations.title" :label="trans('slides.title')">
                    <template slot-scope="scope">
                        <a @click.prevent="goToEdit(scope)" href="#">
                            {{ scope.row.translations.title }}
                        </a>
                    </template>
                </el-table-column>
                <el-table-column prop="actions" :label="trans('core.table.actions')">
                    <template slot-scope="scope">
                        <el-button-group>
                            <edit-button
                                    :to="{name: 'admin.slider.slide.edit', params: {slideId: scope.row.id}}"></edit-button>
                            <delete-button :scope="scope" :rows="data"></delete-button>
                        </el-button-group>
                    </template>
                </el-table-column>
            </el-table>

            <button v-shortkey="['c']" @shortkey="pushRoute({name: 'admin.slider.slide.create'})"
                    v-show="false"></button>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import _ from 'lodash';

    import ShortcutHelper from '../../../../Core/Assets/js/mixins/ShortcutHelper';

    let data;

    export default {
        mixins: [ShortcutHelper],
        props:['parentId'],
        data() {
            return {
                sliderId: '',
                data,
                meta: {
                    current_page: 1,
                    per_page: 10,
                },
                order_meta: {
                    order_by: '',
                    order: '',
                },
                links: {},
                searchQuery: '',
                tableIsLoading: false,
                showExtraButtons: false,
                selectedSliders: {},
            };
        },
        watch: {
            selectedPages() {
                this.showExtraButtons = this.selectedPages.length >= 1;
            },
        },
        methods: {
            queryServer(customProperties) {
                const properties = {
                    page: this.meta.current_page,
                    per_page: this.meta.per_page,
                    order_by: this.order_meta.order_by,
                    order: this.order_meta.order,
                    search: this.searchQuery,
                    sliderId: this.$route.params.sliderId

                };

                axios.get(route('api.slider.slide.index', _.merge(properties, customProperties,)))
                    .then((response) => {
                        this.tableIsLoading = false;
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;

                        this.order_meta.order_by = properties.order_by;
                        this.order_meta.order = properties.order;
                        console.log('response', response);
                    });
            },
            fetchData() {
                this.tableIsLoading = true;
                this.queryServer();
            },
            handleSizeChange(event) {
                console.log(`per page :${event}`);
                this.tableIsLoading = true;
                this.queryServer({per_page: event});
            },
            handleCurrentChange(event) {
                console.log(`current page :${event}`);
                this.tableIsLoading = true;
                this.queryServer({page: event});
            },
            handleSortChange(event) {
                console.log('sorting', event);
                this.tableIsLoading = true;
                this.queryServer({order_by: event.prop, order: event.order});
            },
            performSearch: _.debounce(function (query) {
                console.log(`searching:${query.target.value}`);
                this.tableIsLoading = true;
                this.queryServer({search: query.target.value});
            }, 300),
            handleExtraActions(action) {
                const slideIds = _.map(this.selectedSliders, elem => elem.id);
                axios.get(route('api.slider.slide.mark-status', {action, slideIds: JSON.stringify(slideIds)}))
                    .then((response) => {
                        this.$message({
                            type: 'success',
                            message: response.data.message,
                        });
                        this.$refs.SlideTable.clearSelection();
                        this.data.filter(slide => slideIds.indexOf(slide.id) >= 0)
                            .map((p) => {
                                const slide = p;
                                slide.translations.status = action === 'mark-online';
                                return slide;
                            });
                    })
                    .catch(() => {
                        this.$message({
                            type: 'error',
                            message: this.trans('core.something went wrong'),
                        });
                    });
            },
            handleSelectionChange(selectedSliders) {
                this.selectedSliders = selectedSliders;
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
<style>
    .text-success {
        color: #13ce66;
    }

    .text-danger {
        color: #ff4949;
    }
</style>
