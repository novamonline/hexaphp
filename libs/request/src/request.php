<?php 

namespace HexaPHP\Libs;

class Request {
    
    public static function fromGlobals() {
        
        return $_SERVER;
    }
}