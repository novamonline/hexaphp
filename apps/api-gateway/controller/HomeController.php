<?php 

namespace App\Bootctl;

use App\Domain\Entities\User;

class HomeController extends BsseController
{
    public function __invoke()
    {
        User::all();
        return [
            "message" => "Hello World from Home Controller",
        ];
    }
}