<?php

namespace App\Http\Traits;


trait GeneralTrait {
    public function returnSuccessMessage($message_ar,$message_en,$code,$status,$data)
    {

        return [
             'message_ar'=>$message_ar,
             'message_en' => $message_en,
             'code'=>$code,
             'status'=>$status,
             'data'=>$data,
        ];
    }



    public function returnErrorMessage($message_ar ,$message_en,$code,$status,$data)
    {
        return [
            'message_ar'=>$message_ar,
            'message_en'=>$message_en,
            'code'=>$code,
            'status'=>$status,
            'data'=>$data,
       ];
    }
}
