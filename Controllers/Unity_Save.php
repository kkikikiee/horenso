<?php
/**
 * Created by PhpStorm.
 * User: Moon
 * Date: 2018-04-19
 * Time: 오후 3:35
 */

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\ColorInfo;
use App\Models\PentoDesign;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class Unity_Save extends Controller
{

    //　変わった「location」を保存する変数
    public $result_value = '';

    // 「Image_Save」のオブジェクトを保存する変数
    public $obj_ImageSave;

    // 「Color」を保存する変数
    public $color_array = array();

    public function __construct()
    {
        // DB に「Color」を持ってくる。
        $result_query = ColorInfo::getColorRGB();

        // 「Color 配列」に保存する
        foreach ($result_query as $key => $value) {

            // 「Color 配列」中の「Red」系列
            $this->color_array[$key+1][0] = $value->R;
            // 「Color 配列」中の「Green」系列
            $this->color_array[$key+1][1] = $value->G;
            // 「Color 配列」中の「Blue」系列
            $this->color_array[$key+1][2] = $value->B;
        }

        $this->obj_ImageSave = new Image_Save();
    }

    // UnityのDataをイメージ化するためのLocationに帰る
    // Data : Location
    public function Change_Location_toSave($location_value){

        $result_array = array();

        //何番目のLocationとかカウントする変数
        $count = 0;

        for( $y=0; $y<15; $y++ ){

            for( $x=0; $x<15; $x++ ){

                // 桁に該当する座標値を配列に保存する
                $result_array[$y][$x] = (int)substr($location_value,$count,1);

                $count++;
            }
        }

        return $result_array;
    }

    // 図案情報をDBに保存する
    public function Save_DB($file_value){

        // Dataの中で clear timeがあるかを確認する
        // 'Free Mode'は clear time がない

        if( empty($file_value['clear_time'] )){

            // Free Mode
            // Data を DB に保存する
            $result_query = PentoDesign::savePentoDesign($file_value);

        }
        else{

            // 'Step Mode','Collection Mode'
            // Data を DB に保存する
            $result_query = Collection::saveImitatedDesign($file_value['user_no'],$file_value['design_no'],$file_value['put_num'],
                $file_value['image_name'],$file_value['clear_time']);
        }

        return $result_query;

    }


    // 'Free Mode' 保存する
    public function Save_FreeMode(Request $request){

        // ファイル内容を保存する配列
        $file_value = array();

        // Rear Time を保存する変数
        $time_now = date("Y.m.d H.i.s");

        ////////////////////////////////////////////////////////////////////////////////////////
        // ファイル内容

        // user_no を内容
        $file_value['user_no']              = $request->input('user_no');
        // design_title を内容
        $file_value['design_title']         = $request->input('design_title');
        // design_explain を内容
        $file_value['design_explain']       = $request->input('design_explain');
        // image_name を内容
        $file_value['image_name']         = $request->input('design_title').'_'.$time_now.'.jpg';
        // coordinate_value を内容
        $file_value['coordinate_value']     = $request->input('location_default');

        /////////////////////////////////////////////////////////////////////////////////////////
        // Location 変形
        // Unity Location => Canvas Location (default Location)
        $result_location_default = $this->Change_Location_toSave($request->input('location_default'));
        // Unity Location => Canvas Location (Color Location)
        $result_location_color = $this->Change_Location_toSave($request->input('location_color'));

        /////////////////////////////////////////////////////////////////////////////////////////

        // DB に Data を保存する
        $result_query = $this->Save_DB($file_value);

        if($result_query == 'true'){

            // Color Location で Canvas を作る
            $this->obj_ImageSave->CreateImage_color($result_location_color);
            // Canvas Image を .jpg ファイルで保存する
            $this->obj_ImageSave->Save_Img_color($file_value);

            // Default Location で Canvas を作る
            $this->obj_ImageSave->CreateImage_default($result_location_default);
            // Canvas Image を .jpg ファイルで保存する
            $this->obj_ImageSave->Save_Img_default($file_value);

            return 'true';
        }
        else{

            return $result_query;
        }

    }


    public function test(Request $request){

        $file_value = array();

        // その level の 「イメージデータ」と「イメージタイトル」を持ってくる
        if($request->input('level') != null){
            $result_query = PentoDesign::test($request->input('level'));
        }
        else{
            $result_query = PentoDesign::test2($request->input('design_no'));
        }

        // イマージを作る
        foreach ($result_query as $key => $value){
            
            $this->obj_ImageSave = new Image_Save();

            // Location を 文字列で配列に変更
            $result_location = $this->Change_Location_toSave($value->coordinate_value);

            // Canvas に block を入る
            $this->obj_ImageSave->CreateImage_default($result_location);

            // イメージの名前を作る
            $file_value['image_name'] = $value->design_title.'.jpg';

            // イマージを保存する
            $this->obj_ImageSave->Save_Img_default($file_value);
        }

        return 'true';

    }

    // 'Step Mode','Collection Mode' を保存する
    public function Save_NomalMode(Request $request){

        // real time を保存する変数
        $time_now = date("Y.m.d H.i.s");

        // image_name　を保存する
        $file_value['image_name'] = $request->input('user_no').'_'.$time_now.'.jpg';
        // user_no　を保存する
        $file_value['user_no'] = $request->input('user_no');
        // design_no　を保存する
        $file_value['design_no'] = $request->input('design_no');
        // put_num　を保存する
        $file_value['put_num'] = $request->input('put_num');
        // clear_time　を保存する
        $file_value['clear_time'] = $request->input('clear_time');

        // 図案情報のうち、補償価格がいるか確認する
        // ('Step Mode','Collection Mode' どれかを確認する)
        $reward_point = PentoDesign::getRewardPoint($file_value['design_no']);

        // 図案情報に補償価格があるとは
        // (Step Mode)
        if( $reward_point != 'false' ){

            // ユーザのポイントをアップデート
            UserProfile::updateUserPoint($file_value['user_no'],$reward_point);

            // 特定段階の Level を全部するとユーザーの Level を上げる
            UserProfile::updateUserGrade($file_value['user_no']);
        }

        // Unity Location => Canvas Location (Color Location)
        $result_location_color = $this->Change_Location_toSave($request->input('location_color'));

        // イマージを作る
        // Color Location で Canvas を作る
        $this->obj_ImageSave->CreateImage_color($result_location_color);

        // Canvas Image を .jpg ファイルで保存する
        $this->obj_ImageSave->Save_Img_color($file_value);

        /////////////////////////////////////////////////////////////////////////////////
        // Data を DB に保存する
        return $this->Save_DB($file_value);
    }

}

class Image_Save{

    // イメージの経路を保存する変数
    public $image;

    // Canvas image 作る
    public function __construct()
    {
        // Canvas (300 X 300) 作る
        $this->image = imagecreatetruecolor(300,300);
        $background = imagecolorallocate($this->image,228,226,203);
        imagefill($this->image,0,0,$background);

        // Canvas (横15つ 縦15つ) 白い線を作る
        for($j=10*2; $j<=300; $j+=20){
            $white = imagecolorallocate($this->image,255,255,255);
            imageline($this->image,$j,0,$j,300,$white);
            imageline($this->image,0,$j,300,$j,$white);
        }
    }

    // Default Location でイマージを作る
    // Data : Default Location
    public function CreateImage_default($location_default){

        $obj_UnitySave = new Unity_Save();

        // Canvas で Block を作る
        for( $y=0; $y<15; $y++ ){

            for( $x=0; $x<15; $x++ ){

                if( $location_default[$y][$x] == 1){

                    // Random color を決める
                    $random = rand(1,count($obj_UnitySave->color_array));

                    // Block の Color を保存する
                    $color_cube = imagecolorallocate(
                        // Red 系列 の color を「０番」で保存する
                        $this->image,$obj_UnitySave->color_array[$random][0],
                        // Green 系列 の color を「１番」で保存する
                        $obj_UnitySave->color_array[$random][1],
                        // Blue 系列 の color を「２番」で保存する
                        $obj_UnitySave->color_array[$random][2]);

                    // 「Block」を作る
                    imagefilledrectangle($this->image,
                        //「Block」の最初の X,Y Location setting
                        $x*20, $y*20,
                        //「Block」の最後の X,Y Location setting
                        ($x*20)+20, ($y*20)+20,
                        // 保存した色を使用
                        $color_cube
                    );

                }
            }
        }
    }

    // Color Location でイマージを作る
    // Data : Color Location
    public function CreateImage_color($location_color){

        $obj_UnitySave = new Unity_Save();

        // Canvas で Block を作る
        for( $y=0; $y<15; $y++ ){

            for( $x=0; $x<15; $x++ ){

                if( $location_color[$y][$x] != 0){

                    // Block の Color を保存する
                    $color = $location_color[$y][$x];

                    // Block color を決める
                    $color_cube = imagecolorallocate(
                        $this->image,
                        // Red 系列 の color を「０番」で保存する
                        $obj_UnitySave->color_array[$color][0],
                        // Green 系列 の color を「１番」で保存する
                        $obj_UnitySave->color_array[$color][1],
                        // Blue 系列 の color を「２番」で保存する
                        $obj_UnitySave->color_array[$color][2]);

                    // 「Block」を作る
                    imagefilledrectangle($this->image,
                        //「Block」の最初の X,Y Location setting
                        $x*20, $y*20,
                        //「Block」の最後の X,Y Location setting
                        ($x*20)+20, ($y*20)+20,
                        // 保存した色を使用
                        $color_cube
                    );

                }
            }
        }
    }

    // Default Location で作ったイマージをサーバーに保存する
    public function Save_Img_default($file_value){

        Header('Content-type: image/jpeg');

        // $image で保存した「Data」をイマージ化して「Jpeg」で保存する
        imagejpeg($this->image,$_SERVER['DOCUMENT_ROOT'] . "/images/everyPento/" . $file_value['image_name'] );

        return 'true';
    }

    // Color Location で作ったイマージをサーバーに保存する
    public function Save_Img_color($file_value){

        Header('Content-type: image/jpeg');

        // $image で保存した「Data」をイマージ化して「Jpeg」で保存する
        imagejpeg($this->image,$_SERVER['DOCUMENT_ROOT'] . "/images/imitatedPento/".$file_value['image_name']);

        return 'true';
    }
}

