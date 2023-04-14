<?php 

class HomeController
{
    public function __invoke()
    {
        return [
            "message" => "Hello World from Home Controller",
        ];
    }
}

return [
    "GET /" => [HomeController::class],
];