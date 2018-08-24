/* eslint-disable linebreak-style */
import BlockTableServerSide from './components/BlockTableServerSide.vue';
import BlockForm from './components/BlockForm.vue';

const locales = window.AsgardCMS.locales;

export default [
    {
        path: '/block/blocks',
        name: 'admin.block.block.index',
        component: BlockTableServerSide,
    },
    {
        path: '/block/blocks/create',
        name: 'admin.block.block.create',
        component: BlockForm,
        props: {
            locales,
            blockTitle: 'create block',
        },
    },
    {
        path: '/block/blocks/:blockId/edit',
        name: 'admin.block.block.edit',
        component: BlockForm,
        props: {
            locales,
            blockTitle: 'edit block',
        },
    }
];
