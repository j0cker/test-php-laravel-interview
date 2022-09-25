<?php
namespace App\Classes;

use Illuminate\Support\Facades\Log;
use App\Classes\County;

class Policy
{
    public $county;
    public $line;

    public function __construct(){
        Log::info("[Policy][constructor]");
    }
}