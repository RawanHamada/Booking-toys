<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Cafe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CafeController extends Controller
{
    use GeneralTrait;

    public function index(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'location' => 'required|string',
        ]);

        if ($validator->fails())

        return response()->json(['error'=>$validator->errors()], 400);

        $user = User::find($id);

        $cafes = Cafe::where('address', $user->location)->paginate(10);
        // $cafes = Cafe::paginate(10);
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
