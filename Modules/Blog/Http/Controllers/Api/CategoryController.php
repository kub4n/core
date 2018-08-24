<?php

namespace Modules\Blog\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Blog\Entities\Category;
use Modules\Blog\Http\Requests\StoreCategoryRequest;
use Modules\Blog\Repositories\CategoryRepository;
use Modules\Blog\Transformers\MenuTransformer;
use Modules\Blog\Transformers\FullMenuTransformer;
use Modules\Blog\Http\Requests\UpdateCategoryRequest;
class CategoryController extends Controller {

    /**
     * @var CategoryRepository
     */
    private $category;

    public function __construct(CategoryRepository $category) {
        $this->category = $category;
    }

    public function index() {
        return MenuTransformer::collection($this->category->all());
    }

    public function indexServerSide(Request $request) {
        return MenuTransformer::collection($this->category->serverPaginationFilteringFor($request));
    }

    public function store(Request $request) {
        $this->category->create($request->all());

        return response()->json([
                    'errors' => false,
                    'message' => trans('blog::messages.category created'),
        ]);
    }
        public function find(Category $category)
    {
        return new FullMenuTransformer($category);
    }


    public function update(Category $category, UpdateCategoryRequest $request)
    {
        $this->category->update($category, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('blog::messages.category updated'),
        ]);
    }

    public function destroy(Category $category)
    {
        $this->category->destroy($category);

        return response()->json([
            'errors' => false,
            'message' => trans('blog::messages.category deleted'),
        ]);
    }

}
