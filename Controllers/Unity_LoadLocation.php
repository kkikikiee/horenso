<?php
/**
 * Created by PhpStorm.
 * User: Moon
 * Date: 2018-04-19
 * Time: 오후 3:36
 */

namespace App\Http\Controllers;


use App\Models\PentoDesign;
use Illuminate\Http\Request;

class Unity_LoadLocation extends Controller
{

    // 「Location」を持ってくる、
    // 「request」は「design_no」
    public function Load_Location(Request $request){

        //「DB」で「query result」を持ってくる
        $result_query = PentoDesign::getPentoCoordinate($request->input('design_no'));

        //「DB query」の結果加工
        foreach ($result_query as $key => $value){

            $this->return_value = $value->coordinate_value;

        }

        // 「Unity」に伝える。
        return $this->return_value;

    }
}

