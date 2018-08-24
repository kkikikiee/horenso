<?php
/**
 * Created by PhpStorm.
 * User: Moon
 * Date: 2018-04-19
 * Time: 오후 3:36
 */

namespace App\Http\Controllers;

use App\Models\ArduinoInfo;
use App\Models\Buylist;
use App\Models\PentoDesign;
use App\Models\TaleDesign;
use App\Models\UserInfo;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class Unity_LoadValue extends Controller
{

    //「User Level」を持ってくる、
    //「request」は「level」
    public function Load_UserLevel(Request $request){

        //「DB」で「query result」を持ってくる
        $this->return_value = UserProfile::getUserLevelNum($request->get('user_no'));

        // 「Unity」に伝える。
        return $this->return_value;
    }


    //「Buy List」を持ってくる、
    //「request」は「user_no」
    public function Load_BuyList(Request $request){

        //「DB」で「query result」を持ってくる
        $result_buylist = Buylist::getBuyStoryNum($request->input('user_no'));

        //「DB query」の結果加工
        foreach ($result_buylist as $key => $value){

            $this->return_value .= $value->fairy_tale_no.',';
        }

        // 「Unity」に伝える。
        return substr($this->return_value,0,-1);
    }

    //「User Number」を持ってくる、
    //「request」は「user_id」、「user_pw」、「serial_no」
    public function Load_UserNum(Request $request){

        // Serial Number Check
        if($request->has('serial_no')){

            return ArduinoInfo::getArduinoNum($request->get('serial_no'));
        }

        //「DB」で「query result」を持ってくる
        $result_user_no = UserInfo::loginCheck('unity',$request->get('user_id'),$request->get('user_pw'));

        //「DB query」の結果加工
        if($result_user_no == 'Invalid id' || $result_user_no == 'Invalid password'){
            $this->return_value = 'false';
        }
        else{
            $this->return_value = $result_user_no[0]->user_no;
        }

        // 「Unity」に伝える。
        return $this->return_value;
    }

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

    // 「Story Design Number」を持ってくる、
    // 「request」は「story_no」
    public function Load_DesignNum(Request $request){

        //「DB」で「query result」を持ってくる
        $result_query = TaleDesign::getStoryDesignNum($request->input('story_no'));

        //「DB query」の結果加工
        foreach ($result_query as $key => $value){

            if($key != 0){
                $this->return_value .= ',';
            }
            $this->return_value .= $value;
        }

        // 「Unity」に伝える。
        return $this->return_value;
    }

}