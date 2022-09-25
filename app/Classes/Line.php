<?php
namespace App\Classes;

use Illuminate\Support\Facades\Log;

class Line
{

    public function __construct(){
        Log::info("[Line][constructor]");

    }
}