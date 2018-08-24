<?php

namespace Modules\Blog\Repositories\Eloquent;

use Modules\Blog\Repositories\CategoryRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentCategoryRepository extends EloquentBaseRepository implements CategoryRepository {

    public function serverPaginationFilteringFor(Request $request): LengthAwarePaginator {
        $pages = $this->allWithBuilder();

        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $pages->whereHas('translations', function ($query) use ($term) {
                        $query->where('title', 'LIKE', "%{$term}%");
                        $query->orWhere('slug', 'LIKE', "%{$term}%");
                    })
                    ->orWhere('id', $term);
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            if (str_contains($request->get('order_by'), '.')) {
                $fields = explode('.', $request->get('order_by'));

                $pages->with('translations')->join('page__page_translations as t', function ($join) {
                            $join->on('page__pages.id', '=', 't.page_id');
                        })
                        ->where('t.locale', locale())
                        ->groupBy('page__pages.id')->orderBy("t.{$fields[1]}", $order);
            } else {
                $pages->orderBy($request->get('order_by'), $order);
            }
        }
        //dd($pages->toSql());
//        $pages->with('translations')->join('page__page_translations as t', function ($join) {
//            $join->on('page__pages.id', '=', 't.page_id');
//        })->where('t.locale', locale())
//            ->groupBy('page__pages.id')->orderBy("t.title", 'desc');
        return $pages->paginate($request->get('per_page', 10));
    }

}
