<?php
/**
 * Created by PhpStorm.
 * User: Moon
 * Date: 2018-04-23
 * Time: 오전 10:46
 */

namespace App\Http\Controllers;


use App\Models\Buylist;
use App\Models\Collection;
use App\Models\FairyTale;
use App\Models\Follow;
use App\Models\ImitatedPento;
use App\Models\PentoDesign;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class Web_DefaultValue extends Controller
{

    // 童話リストの最初のデータを持ってくる
    public function StoryList(){

        // DBのDataを持ってくる
        $this->return_value = FairyTale::getStoryListWeb();

        // View に Data Return
        return $this->return_value;
    }

    // 買い物リストのデータを持ってくる
    public function BuyList(Request $request){

        // DBのDataを持ってくる
        $this->return_value = Buylist::getBuyList($request->input('user_no'));

        // View に Data Return
        return $this->return_value;
    }



    // ユーザーコレクションの最初のデータを持ってくる
    public function MyCollection(Request $request){

        // DBのDataを持ってくる
        $this->return_value = Collection::getCollectionListWeb($request->input('user_no'));

        // View に Data Return
        return $this->return_value;
    }


    // みんなコレクションの最初のデータを持ってくる
    public function EveryCollection(){

        // DBのDataを持ってくる
        $this->return_value = PentoDesign::getPentoListWeb();

        // View に Data Return
        return $this->return_value;

    }

    // MyPageの最初のデータを持ってくる
    public function MyPage(Request $request){

        // DBのDataを持ってくる
        $this->return_value = UserProfile::myPageUserInfo($request->input('user_no'));

        // View に Data Return
        return $this->return_value;
    }


    // Friends Pageの最初のデータを持ってくる
    public function Friends(Request $request){

        // DBのDataを持ってくる
        $this->return_value = Follow::getFollowList($request->input('user_no'));

        // View に Data Return
        return $this->return_value;
    }

    // Rank Pageの最初のデータを持ってくる
    public function Rank(Request $request){

        // DBのDataを持ってくる
        $this->return_value = ImitatedPento::getImitatedPentoList ($request->input('user_no'));

        // View に Data Return
        return $this->return_value;
    }

}