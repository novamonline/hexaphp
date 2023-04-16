<?php 

namespace App\Http\Controllers;

use App\Domain\Entities\User;

class HomeController extends Controller
{
    public function __invoke()
    {
        User::all();
        return [
            "message" => "Hello World from Home Controller",
        ];
    }
}