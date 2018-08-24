<?php
/**
 * Created by PhpStorm.
 * User: Moon
 * Date: 2018-04-14
 * Time: 오후 11:23
 */

namespace App\Http\Controllers;


class Design_Unity
{
    public $image = array();
    // 이미지 저장할 배열
    
    public function Change_to_arrayUnity($array_db){
        // 유니티 배열로 변환

        $array_result = array();

        $array_count = count($array_db);

        $pre_index = 0;

        for($i=0,$index=0; $i<$array_count; $i++,$index++){

            foreach ($array_db[$i] as $key => $value){

                if($array_db[$i]->design_no != $pre_index){
                    $index = 0;
                }

                if($key != 'coordinate_no' && $key != 'registered_date'){

                    switch ($key){

                        case 'board_X':
                            $array_result[$array_db[$i]->design_no][$key][$index] = $value;
                            break;

                        case 'board_Y':
                            $array_result[$array_db[$i]->design_no][$key][$index] = $value;
                            break;
                    }

                }
            }

            $pre_index = $array_db[$i]->design_no;    // 이전값을 저장
        }

        return $array_result;
    }


    public function Change_to_image_UnityDefault($array_result){
        // 도안생성 메서드

        foreach ($array_result as $key => $value){

            $this->image[$key] = imagecreatetruecolor(300,300);
            $background = imagecolorallocate($this->image[$key],254,251,239);
            imagefill($this->image[$key],0,0,$background);

            for($j=10*2; $j<=300; $j+=20){
                $white = imagecolorallocate($this->image[$key],255,255,255);
                imageline($this->image[$key],$j,0,$j,300,$white);
                imageline($this->image[$key],0,$j,300,$j,$white);
            }
        }

        // 랜덤으로 부여할 블록 색상 지정
        $color_ran = array(
            0 => [254,115,114],
            1 => [254,200,85],
            2 => [71,183,223],
            3 => [115,214,24],
            4 => [253,199,200]  );


        foreach ($array_result as $key => $value){

            for( $x=0,$count=0; $x<15; $x++ ){

                for( $y=0; $y<15; $y++ ){

                    if( $y == $array_result[$key]['board_Y'][$count] && $x == $array_result[$key]['board_X'][$count]){

                        $random = rand(0,count($color_ran)-1);

                        $color_cube = imagecolorallocate(
                            $this->image[$key],$color_ran[$random][0],$color_ran[$random][1],$color_ran[$random][2]);

                        imagefilledrectangle($this->image[$key],
                            $x*20, $y*20,
                            ($x*20)+20, ($y*20)+20,
                            $color_cube
                        );

                        if($count < count($array_result[$key]['board_Y'])-1){
                            $count++;
                        }
                    }
                }
            }

        }
////////////////////////////////////////////////////////////

        Header('Content-type: image/png');

//        return var_dump($this->image);

        $array_image = array();     // 파일이름 저장할 변수

        foreach ($this->image as $key => $value ){

            imagepng($this->image[$key],$_SERVER['DOCUMENT_ROOT'] . "/images/collection/pentoimg".$key.".png");
            // 이미지를 파일로 저장

            $array_image[$key]['file_name'] = "http://ec2-13-125-219-201.ap-northeast-2.compute.amazonaws.com" . "/images/collection/pentoimg".$key.".png";
            // 파일이름을 배열에 저장
        }

        return $array_image;
    }

    public function Change_to_image_UnityLevel($value_Coordinate){

        foreach ($value_Coordinate as $key => $value){

            $this->image[$key] = imagecreatetruecolor(300,300);
            $background = imagecolorallocate($this->image[$key],254,251,239);
            imagefill($this->image[$key],0,0,$background);

            for($j=10*2; $j<=300; $j+=20){
                $white = imagecolorallocate($this->image[$key],255,255,255);
                imageline($this->image[$key],$j,0,$j,300,$white);
                imageline($this->image[$key],0,$j,300,$j,$white);
            }
        }

        // 랜덤으로 부여할 블록 색상 지정
        $color_ran = array(
            0 => [254,115,114],
            1 => [254,200,85],
            2 => [71,183,223],
            3 => [115,214,24],
            4 => [253,199,200]  );

        foreach ($value_Coordinate as $key => $value){

            foreach ($value as $key2 => $value2) {

                for ($x = 0, $count = 0; $x < 15; $x++) {

                    for ($y = 0; $y < 15; $y++) {

                        if ($key2 == 'board_X' || $key2 == 'board_Y') {
                            if ($y == $value_Coordinate[$key]['board_Y'][$count] && $x == $value_Coordinate[$key]['board_X'][$count]) {

                                $random = rand(0, count($color_ran) - 1);

                                $color_cube = imagecolorallocate(
                                    $this->image[$key], $color_ran[$random][0], $color_ran[$random][1], $color_ran[$random][2]);

                                imagefilledrectangle($this->image[$key],
                                    $x * 20, $y * 20,
                                    ($x * 20) + 20, ($y * 20) + 20,
                                    $color_cube
                                );

                                if ($count < count($value_Coordinate[$key]['board_Y']) - 1) {
                                    $count++;
                                }
                            }
                        }

                    }
                }
            }
        }

        Header('Content-type: image/png');

        $array_image = array();     // 파일이름 저장할 변수

        foreach ($this->image as $key => $value){

            imagepng($this->image[$key],$_SERVER['DOCUMENT_ROOT'] . "/images/collection/pentoimg".$key.".png");
            // 이미지를 파일로 저장

            $array_image[$key]['file_name'] = "http://ec2-13-125-219-201.ap-northeast-2.compute.amazonaws.com" . "/images/collection/pentoimg".$key.".png";

            // 파일이름을 배열에 저장
        }

        return $array_image;

    }

}