<?php

namespace App\Modules\Repas\Http\Controllers;

use Illuminate\Http\Request;

class RepasController
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("Repas::welcome");
    }
}
