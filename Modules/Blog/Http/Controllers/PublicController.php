<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Support\Facades\App;
use Modules\Blog\Repositories\PostRepository;
use Modules\Core\Http\Controllers\BasePublicController;
use Modules\Blog\Entities\Post;
use Modules\Blog\Entities\Status;
class PublicController extends BasePublicController {

    /**
     * @var PostRepository
     */
    private $post;

    public function __construct(PostRepository $post) {
        parent::__construct();
        $this->post = $post;
    }

    public function index() {
        $posts = Post::with('translations')->whereStatus(Status::PUBLISHED)->paginate(setting('blog::posts-per-page'));
        
        $breadcrumbs = array(
            'Blog' => ''
        );
        return view('blog.index', compact('posts', 'breadcrumbs'));
    }

    public function show($slug) {
        $post = $this->post->findBySlug($slug);
        $otherPosts = Post::with('translations')->where('id', '!=', $post->id)->whereStatus(Status::PUBLISHED)->take(2)->get();
        $products = \Modules\Product\Entities\Product::all()->take(2);
        $breadcrumbs = array(
            'Blog' => route(App::getLocale() . '.blog'),
            $post->title => ''
        );

        return view('blog.show', compact('post', 'otherPosts', 'breadcrumbs', 'products'));
    }

}
