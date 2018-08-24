/* eslint-disable linebreak-style */
import PostTableServerSide from './components/PostTableServerSide.vue';
import PostForm from './components/PostForm.vue';
import CategoryTableServerSide from './components/CategoryTableServerSide.vue';
import CategoryForm from './components/CategoryForm.vue';

const locales = window.AsgardCMS.locales;

export default [
    {
        path: '/blog/posts',
        name: 'admin.blog.post.index',
        component: PostTableServerSide,
    },
    {
        path: '/blog/posts/create',
        name: 'admin.blog.post.create',
        component: PostForm,
        props: {
            locales,
            postTitle: 'create post',
        },
    },
    {
        path: '/blog/posts/:postId/edit',
        name: 'admin.blog.post.edit',
        component: PostForm,
        props: {
            locales,
            postTitle: 'edit post',
        },
    },
    {
        path: '/blog/categories',
        name: 'admin.blog.category.index',
        component: CategoryTableServerSide,
    },
    {
        path: '/blog/categories/create',
        name: 'admin.blog.category.create',
        component: CategoryForm,
        props: {
            locales,
            categoryTitle: 'create category',
        },
    },
    {
        path: '/blog/categories/:categoryId/edit',
        name: 'admin.blog.category.edit',
        component: CategoryForm,
        props: {
            locales,
            categoryTitle: 'edit category',
        },
    },
];
