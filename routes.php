<?php

// 시발시발
Route::any('/test_create','Unity_Save@test');

// 시발시발
Route::any('/test_create2','Unity_Save@test');

// 로그인
Route::any('/Login','Web_Functions@Login');

// 동화리스트 초기값
Route::any('/StoryList','Web_DefaultValue@StoryList');

// 동화리스트 구매리스트 초기값
Route::any('/BuyList','Web_DefaultValue@BuyList');

// 동화 상세설명
Route::any('/StoryValue','Web_DetailValue@StoryValue');

// 나만의 컬렉션 초기값
Route::any('/MyCollection','Web_DefaultValue@MyCollection');

// 모두의 컬렉션 초기값
Route::any('/EveryCollection','Web_DefaultValue@EveryCollection');

// 컬렉션 구독하기
Route::any('/Buy','Web_Functions@Buy');

// 장바구니
// 매개변수
// 'user_no' : 유저 번호, 'story_no' : 동화 번호(배열)
Route::any('/MyBasket','Web_Functions@MyBasket');

// 컬렉션 추천하기
Route::any('/Recommend','Web_Functions@Recommend');

// 도안 상세정보 불러오기
Route::any('/CollectionValue','Web_DetailValue@CollectionValue');

// 마이페이지 초기값
Route::any('/MyPage','Web_DefaultValue@MyPage');

// 친구페이지 초기값
Route::any('/Friends','Web_DefaultValue@Friends');

// 친구 검색
Route::any('/SearchValue','Web_Functions@SearchValue');

// 친구 추가
Route::any('/AddFriend','Web_Functions@AddFriend');

// 친구 삭제
Route::any('/DeleteFriend','Web_Functions@DeleteFriend');

// 랭크페이지 초기값
Route::any('/Rank','Web_DefaultValue@Rank');

// 랭크페이지 검색값
Route::any('/RankSearchValue','Web_DetailValue@RankSearchValue');

// 랭크페이지 기록반환
Route::any('/RankRecordValue','Web_DetailValue@RankRecordValue');






Route::get('/unity',function (){
    return view('test');
});

/* 유니티부분 */

// 유저번호 가져오기
// 매개변수
// 'user_id' : 유저 아이디, 'user_pw' : 유저 패스워드
Route::any('/Load_Unity/UserNum','Unity_LoadValue@Load_UserNum');

// 로그인
// 매개변수
// 'serial_no' : 시리얼넘버
Route::any('/Load_Unity/CheckSerial','Unity_LoadValue@Load_UserNum');

// 좌표 가져오기
// 매개변수
// 'design_no' : 도안번호
Route::any('/Load_Unity/GetCoordinate','Unity_LoadValue@Load_Location');

// 동화리스트 가져오기
Route::any('/Load_Unity/GetStoryInfo','Unity_LoadList@Load_StoryList');

// 구매동화 리스트
// 매개변수
// 'user_no' : 유저번호
Route::any('/Load_Unity/GetMyStoryNum','Unity_LoadValue@Load_BuyList');

// 동화 도안번호 가져오기
// 매개변수
// 'story_no' : 동화번호
Route::any('/Load_Unity/GetStoryDesignNum','Unity_LoadValue@Load_DesignNum');

// 컬렉션리스트 가져오기
// 매개변수
// 'user_no' : 유저번호
Route::any('/Load_Unity/GetCollectionInfo','Unity_LoadList@Load_CollectionList');

// 단계별 리스트 가져오기
// 매개변수
// 'level' : 선택 레벨
Route::any('/Load_Unity/GetLevelInfo','Unity_LoadList@Load_LevelList');

// 유저 레벨 가져오기
// 매개변수
// 'user_no' : 유저번호
Route::any('/Load_Unity/GetUserLevel','Unity_LoadValue@Load_UserLevel');

// 색상좌표 저장하기 (단계별모드, 컬렉션모드)
// 매개변수
// 'location_color' : 색상좌표, 'user_no' : 유저 번호, 'design_no' : 작품 번호, 'put_num' : 놓은 횟수
// 'clear_time' : 클리어 시간
Route::any('/Load_Unity/SaveLocationColor','Unity_Save@Save_NomalMode');

// 일반좌표 저장하기 (자유모드)
// 매개변수
// 'location_default' : 일반좌표, 'design_title' : 제목, 'design_explain' : 설명, 'user_no' : 유저 번호
// 'location_color' : 컬러좌표
Route::any('/Load_Unity/SaveLocationDefault','Unity_Save@Save_FreeMode');

?>

