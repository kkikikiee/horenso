<?php
/**
 * Created by PhpStorm.
 * User: Moon
 * Date: 2018-04-23
 * Time: 오전 10:51
 */

namespace App\Http\Controllers;

use App\Models\FairyTale;
use App\Models\ImitatedPento;
use App\Models\PentoDesign;
use Illuminate\Http\Request;

class Web_DetailValue extends Controller
{
    // 童話の「Default Value」を持ってくる
    public function StoryValue(Request $request){

        // DBのDataを持ってくる
        $result_query = FairyTale::getStoryInfo($request->input('story_no'));

        // Data 加工
        foreach ($result_query as $key => $value){

            // 童話の番後を保存する
            $this->return_value['fairy_tale_no'] = $value->fairy_tale_no;
            // 童話のタイトルを保存する
            $this->return_value['title'] = $value->tale_title;
            // 童話のイマージの名前を保存する
            $this->return_value['tale_image'.$key] = $value->tale_image;
            // 童話の説明を保存する
            $this->return_value['tale_explain'] = $value->tale_explain;
            // 童話の価格を保存する
            $this->return_value['tale_price'] = $value->tale_price;
        }

        // View に Data Return
        return $this->return_value;
    }

    // 童話の「Default Value」を持ってくる
    // 도안 상세정보 불러오기, 인자값 : 도안 번호
    public function CollectionValue(Request $request){

        // DB에서 도안의 상세정보 가져오기
        $this->return_value = PentoDesign::getPentoInfo($request->input('design_no'));

        // Side image 데이터 가공
        $result_query_SideImage= ImitatedPento::getReImitatedPentoList($request->input('design_no'));

        $this->return_value['side_image'] = $result_query_SideImage;

        // View로 반환
        return $this->return_value;
    }

    // 랭크(유저검색), 인자값 : 유저 ID
    public function RankSearchValue(Request $request){

        // DB에서 유저의 플레이목록 가져오기
        $this->return_value = ImitatedPento::findImitatedPentoList($request->input('user_no'),
                                                                $request->input('pento_title'));

        // View로 반환
        return $this->return_value;
    }

    // 랭크페이지 해당회원 기록을 반환
    public function RankRecordValue(Request $request){

        // DB에서 유저의 플레이기록 가져오기
        $this->return_value = ImitatedPento::getRecordList($request->input('user_no'),
                                                            $request->input('design_no'));

        // View로 반환
        return $this->return_value;
    }

}


