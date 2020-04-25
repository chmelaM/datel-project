<?php

namespace App\Http\Controllers;

use App\Models\ExportEplan;
use Illuminate\Http\Request;

class porovnatController extends Controller
{
    public function porovnatGet()
    {
        return view('porovnani1');
    }

    public function porovnatPost(Request $request, ExportEplan $exportEplan)
    {

        $file = $request->adresy;
        $object = simplexml_load_file($file);
        $exportEplan->hydrate($object);

        \Debugbar::info($exportEplan);
        \Debugbar::info($object->Document->Page->Footer);

        return view('porovnani2', compact('exportEplan'));
    }
}
