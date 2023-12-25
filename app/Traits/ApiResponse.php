<?php

namespace App\Traits;

trait ApiResponse
{

    function success_resposnes($result, $code = 200, $message = "Succssuful")
    {
        return response()->json(

            data: [
                "status" => true,
                "code" => $code,
                "message" => __($message),
                "data" => $result
            ],
            status: $code
        );
    }

    function fiald_resposnes($result=null,$code = 404, $message = "Failed")
    {
        return response()->json(

            data: [
                "status" => false,
                "code" => $code,
                "message" => __($message),
                "data" => $result
            ],
            status: $code
        );
    }
}
