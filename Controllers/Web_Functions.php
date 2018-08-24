<?php
/**
 * Created by PhpStorm.
 * User: Moon
 * Date: 2018-04-23
 * Time: 오전 10:56
 */

namespace App\Http\Controllers;


use App\Models\Buylist;
use App\Models\Collection;
use App\Models\FairyTale;
use App\Models\Follow;
use App\Models\UserInfo;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Models\Recommend;

class Web_Functions extends Controller
{

    // 로그인, 인자값 : 유저 ID, 유저 PW
    public function Login(Request $request){

        if(session()->has('user_no') && session('user_id') == $request->input('user_id')){
            return 'false';
        }

        // DB에서 데이터 가져오기
        $this->return_value =
            UserInfo::loginCheck(
                'web',$request->input('user_id'),$request->input('user_pw')
            );

        // 유저가 입력한 ID,PW가 DB에 없을때 false 반환
        if($this->return_value == 'Invalid id' || $this->return_value == 'Invalid password'){

            $this->return_value = 'false';
        }

        return $this-> return_value;
    }


    // 구매
    // 인자값 : 유저번호, 도안 번호 or 동화 번호
    public function Buy(Request $request){

        // 도안을 구독할때
        if($request->has('design_no')){
            // DB에서 데이터 가져오기
            $this->return_value = Collection::subscribePento ($request->input('user_no'),
                                                                $request->input('design_no'));
        }
        // 동화를 구매할때
        else{

            // DB에서 회원의 돈 가져오기
            $result_query_userInfo = UserProfile::myPageUserInfo($request->input('user_no'));

            // DB에서 동화의 가격 가져오기
            $result_query_storyInfo = FairyTale::getStoryInfo($request->input('story_no'));

            // 동화의 가격과 회원이 가진 돈을 비교
            if($result_query_storyInfo[0]->tale_price > $result_query_userInfo[0]->user_point){

                // 회원의 돈이 부족하면 구매불가
                return 'nocash';
            }

            // DB에서 데이터 가져오기
            $this->return_value = Buylist::buyStory($request->input('user_no'),$request->input('story_no'));
        }

        if($this->return_value != 'true'){
            $this->return_value = 'false';
        }

        // View로 반환
        return $this->return_value;
    }


    // 장바구니 구매
    // 인자값 : 유저번호, 동화번호
    public function MyBasket(Request $request){

        $array_storys = $request->input('story_no');

        // 장바구니 중복값 제거
        $array_storys_result =  array_unique($array_storys);

        foreach ($array_storys_result as $key => $value){

            // 구매동화 DB 저장
            $result_query = Buylist::buyStory($request->input('user_no'),$array_storys_result[$key]);

            // 구매성공, 구매실패 분류
            if($result_query == 'true'){
                $this->return_value['complete'][$value] = $array_storys_result[$key];
            }
            else{
                $this->return_value['fail'][$value] = $array_storys_result[$key];
            }

        }

        // View로 데이터 반환
        return $this->return_value;
    }


    // 도안 추천
    // 인자값 : 회원번호, 도안 번호
    public function Recommend(Request $request){

        // 회원번호,도안번호 추천테이블에 저장
        $result_query = Recommend::recommend ($request->input('user_no'),$request->input('design_no'));

        // 추천정보 저장
        $this->return_value = $result_query[0]->recommendNum;

//        return 1;
        // View로 반환
        return $this->return_value;
    }

    // 검색
    // 인자값 : 도안 번호, 친구 ID
    public function SearchValue(Request $request){

        // DB에서 친구아이디 검색하기
        $this->return_value = Follow::findFollowerID ($request->input('friends_id'));

        // 검색결과 저장
        if($this->return_value == 'select fail'){
            $this->return_value = 'false';
        }

        // View로 반환
        return $this->return_value;
    }

    // 친구 추가
    // 인자값 : 친구 ID
    public function AddFriend(Request $request){

        // 친구정보 DB에 저장
        $this->return_value = Follow::addFollow($request->input('user_no'),$request->input('friends_no'));

        // 친구추가 결과 저장
        if($this->return_value != 'true'){
            $this->return_value = 'false';
        }

        // View로 반환
        return $this->return_value;

    }

    // 친구 삭제
    // 인자값 : 친구 번호
    public function DeleteFriend(Request $request){

        // DB에서 친구 삭제
        $this->return_value = Follow::deleteFollow($request->input('user_no'),$request->input('friends_no'));

        // 친구삭제 결과 저장
        if($this->return_value != 'true'){
            $this->return_value = 'false';
        }

        // View로 반환
        return $this->return_value;
    }

}