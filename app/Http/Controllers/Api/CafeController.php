<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Cafe;
use Illuminate\Http\Request;

class CafeController extends Controller
{
    use GeneralTrait;

    public function index()
    {

        $cafes = Cafe::paginate(10);
        if($cafes->count() > 0) {

        return $this->returnSuccessMessage("كل الكافيهات",'All Cafes',200,true,$cafes);

       }else {

        return $this->returnErrorMessage("لا يوجد كافي",'No Cafes Found',200,true,[]);

       }

    }


    public function search($name){

        $result = Cafe::where('name', 'LIKE', '%'. $name. '%')->get();
        if(count($result)){
         return Response()->json($result);
        }
        else
        {
        return response()->json(['Result' => 'No Data not found'], 404);
      }
    }

}
