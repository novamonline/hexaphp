<?php

use HexaPHP\Libs\HttpClient\Response;
use HexaPHP\Libs\HttpClient\Request;

class Application{

    public function process(Request $request){
        // var_dump($request);
        return new Response(
            'Hello World __NAME__',
            200,
            $request->headers->all(),
        );
    }
}

$app = new Application();

// Register the routes


return $app;