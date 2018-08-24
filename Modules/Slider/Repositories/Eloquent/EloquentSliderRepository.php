<?php

namespace Modules\Slider\Repositories\Eloquent;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Modules\Slider\Entities\Slider;
use Modules\Slider\Repositories\SliderRepository;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentSliderRepository extends EloquentBaseRepository implements SliderRepository
{
    public function create($data)
    {
        $slider = $this->model->create($data);

        return $slider;
    }

    public function update($slider, $data)
    {
        $slider->update($data);

        return $slider;
    }

    /**
     * Count all records
     * @return int
     */
    public function countAll()
    {
        return $this->model->count();
    }

    /**
     * Get all available sliders
     * @return object
     */
    public function allOnline()
    {
        return $this->model->where('active', '=', true)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    /**
     * @param string $systemName
     * @return Slider
     */
    public function findBySystemName($systemName)
    {
        return $this->model->where('system_name', '=', $systemName)->active();
    }
    public function serverPaginationFilteringFor(Request $request): LengthAwarePaginator
    {
        $pages = $this->allWithBuilder();

        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $pages
                ->where('name', 'LIKE', "%{$term}%")
                ->orWhere('system_name', 'LIKE', "%{$term}%")
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
