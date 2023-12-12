<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExcelImportRequest;
use App\Imports\ExcelImport;

use Maatwebsite\Excel\Facades\Excel;

class ExcelImportController extends Controller
{

    /**
     * @param ExcelImportRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(ExcelImportRequest $request)
    {
        Excel::import(new ExcelImport(), $request->file('file'));

        return redirect()->back()->with(['success' => 'Import started']);
    }

}
