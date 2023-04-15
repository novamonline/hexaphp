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
    "GET /help" => function(){
      
        return [
            "message" => "Hello World from Closure",
        ];
    }
];