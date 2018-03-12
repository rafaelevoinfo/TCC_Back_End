<?php 
namespace Controllers;

class BaseController{
    protected function fprRetornarStatus($status)
    {
        return \json_encode(array(
            'status' => $status,
        ));
    }
}