<?php

namespace App\Http\Controllers\Excel;

use App\Exports\IndiceExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class IndiceGenDestController extends Controller
{
    public function export() 
    {
        return Excel::download(new IndiceExport, 'indice.xlsx');
    }
}
