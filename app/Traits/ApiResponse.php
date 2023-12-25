<?php

namespace App\Traits;

trait ApiResponse
{

    function success_resposnes($result, $cood = 200, $message = "Succssuful")
    {
        return response()->json(

            data: [
                "status" => true,
                "code" => $cood,
                "message" => __($message),
                "data" => $result
            ],
            status: $cood
        );
    }

    function fiald_resposnes($result=null,$cood = 404, $message = "Failed")
    {
        return response()->json(

            data: [
                "status" => false,
                "code" => $cood,
                "message" => __($message),
                "data" => $result
            ],
            status: $cood
        );
    }
}
