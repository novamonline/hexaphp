<?php 


return [
    "GET /" => [\App\Controller\HomeController::class],
    "GET /help/{user}" => function(){
      
        return [
            "message" => "Hello World from Closure",
        ];
    }
];