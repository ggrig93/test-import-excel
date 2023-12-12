<?php

namespace App\Http\Controllers;

use App\Models\Row;

class RowController extends Controller
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function __invoke()
    {
        $rows = Row::query()->orderBy('date', 'desc')->get()->groupBy('date')->paginate(10);

        return view('rows.index', compact('rows'));
    }
}
