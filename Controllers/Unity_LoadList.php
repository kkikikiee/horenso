<?php
/**
 * Created by PhpStorm.
 * User: Moon
 * Date: 2018-04-19
 * Time: 오후 3:36
 */

namespace App\Http\Controllers;


use App\Models\Collection;
use App\Models\FairyTale;
use App\Models\PentoDesign;
use Illuminate\Http\Request;

class Unity_LoadList extends Controller
{
    // 「Story List」を持ってくる
    public function Load_StoryList(Request $request){

        //「DB」で 「Story List」を持ってくる
        $result_value = FairyTale::getStoryListUnity();

        //「DB query」の結果加工
        foreach ($result_value as $key => $value){

            foreach ($value as $key2 => $value2){
                   if($key2 == 'fairy_tale_no' || $key2 == 'tale_title' || $key2 == 'tale_image'){

                       $this->return_value .= $value2.',';
                   }
                   else if ($key2 == 'tale_explain'){

                       $this->return_value .= $value2;
                   }
            }
            $this->return_value .= '*';
        }

        // 結果を「Unity」に送る
        return substr($this->return_value,0,-1);
    }


    //「Collection List」を持ってくる、
    //「request」は「user_no」
    public function Load_CollectionList(Request $request){

        //「DB」で「query result」を持ってくる
        $result_query = Collection::getCollectionListUnity($request->input('user_no'));
        
        //「DB query」の結果加工
        foreach ($result_query as $key => $value){

            foreach ($value as $key2 => $value2) {

                if($key2 == 'design_no' || $key2 == 'design_image' || $key2 == 'design_title'){
                    $this->return_value .= $value2.',';
                }
                else if ($key2 == 'user_nickname'){
                    $this->return_value .= $value2;
                }

            }
            $this->return_value .= '*';
        }

        // 「Unity」に伝える。
        return substr($this->return_value,0,-1);
    }

    //「Level List」を持ってくる、
    //「request」は「level」
    public function Load_LevelList(Request $request){

        //「DB」で「query result」を持ってくる
        $result_query = PentoDesign::getLevelDesignList($request->input('level'));

        //「DB query」の結果加工
        foreach ($result_query as $key => $value){

            $this->return_value .= $value->design_no.',';
            $this->return_value .= $value->design_title.',';
            $this->return_value .= $value->design_image.',';
            $this->return_value .= $value->design_explain.'*';
        }

        // 「Unity」に伝える。
        return substr($this->return_value,0,-1);
    }

}