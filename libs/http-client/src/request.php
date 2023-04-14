<?php 

namespace HexaPHP\Libs\HttpClient;

use Symfony\Component\HttpFoundation\Request as BaseRequest;


class Request extends BaseRequest {
    
    public static function fromGlobals() {
        
        return parent::createFromGlobals();
    }
}