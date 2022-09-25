<?php
namespace App\Classes;

use Illuminate\Support\Facades\Log;

class Tiv
{

    public $tiv_2012;

    public function __construct(){
        Log::info("[Tiv][constructor]");
    }
}