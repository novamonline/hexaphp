<?php 

namespace HexaPHP\Libs\HttpClient;

use Symfony\Component\HttpFoundation\Response as BaseResponse;


class Response extends BaseResponse {

    public function run(){
        $this->send();
    }    
}