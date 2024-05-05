<?php
namespace App\Helpers\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class PaginationHelper
{
    public static function paginate($items, $perPage)
    {
        $page = Paginator::resolveCurrentPage() ?: 1;

        $items = $items instanceof Collection ? $items : $items->get();

        $urlPath = request()->fullUrlWithoutQuery('page');

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, ['path' => $urlPath]);
    }
}