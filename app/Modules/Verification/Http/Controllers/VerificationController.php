<?php

namespace App\Modules\Verification\Http\Controllers;

use Illuminate\Http\Request;

class VerificationController
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("Verification::welcome");
    }
}
