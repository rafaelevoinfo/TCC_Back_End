<?php 
namespace Controllers;

class BaseController{
    protected function retornarStatus($status)
    {
        return \json_encode(array(
            'status' => $status,
        ));
    }
}