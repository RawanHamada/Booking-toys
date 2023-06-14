<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Game;
use Illuminate\Http\Request;

class GamesController extends Controller
{
    use GeneralTrait;

    public function index(){
        $games = Game::all();
        if($games->count() > 0) {

        return $this->returnSuccessMessage("كل الألعاب",'All Games',200,true,$games);

       }else {

        return $this->returnErrorMessage("لا يوجد ألعاب متاحة",'No Games Found',200,true,[]);

       }
    }


}
