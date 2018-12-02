<?php
/**
**  Project Name - ncloud-kakao-alim-api
**  Maker - 한빛가람( http://hanb.jp )
**  Date - 2018-11-17 (YYYY-mm-dd)
**  Project URL - https://github.com/HanbitGaram/ncloud-kakao-alim-api
**/
if(!defined('_GNUBOARD_')) exit; // 그누보드 개별 파일 실행 방지

// 변수 초기화
$ncGaram = array();

// 알림톡 설정 파일 로드
include_once(G5_PLUGIN_PATH.'/hanbitgaram/nc_alimtalk/user_config.php');

// 반드시 탬플릿 컨텐츠와 버튼 내용은 SENS 등록 내용과 동일하게 작성해주세요.
function ncTalk($templateCode = '', $phoneNum = '', $content = '', $buttons = array()){
	global $ncGaram;

	// 휴대폰 번호에서 숫자를 제외한 모든 문자를 제거함.
	$phoneNum = preg_replace( '/[^0-9]/', '', $phoneNum );

	// 필수 값 검사. 값이 없으면 false를 리턴함.
	// 필수 값 = 플러스친구아이디, 탬플릿코드, 휴대폰번호, 컨텐츠명
	// 선택 값 = 버튼( 타입과 이름은 필수 )
	// ##1 ( 필수 값 검사 루틴 시작)
	if(
		// 기본 값 검사
		(
			!trim( $ncGaram['plusFriendId'] ) // 플러스친구 아이디가 없을 경우
			|| !trim( $templateCode ) // 탬플릿 코드가 없을 경우
			|| !trim( $phoneNum ) // 휴대폰 번호가 없을 경우
			// 휴대폰 번호가 10글자나 11글자가 아닌 경우 (01x-xxx-xxxx or 01x-xxxx-xxxx)
			|| ( 
				strlen( $phoneNum ) !== 10 // ex) 01x-xxx-xxxx
				&& strlen( $phoneNum ) !== 11 // ex) 01x-xxxx-xxxx
			)
			|| !trim( $content ) // 컨텐츠가 없을 경우
		)
		// 버튼 값이 있을 경우 검사.
		|| (
			( count( $buttons ) !== 0 && !trim( $buttons['type'] ) ) // 버튼 타입 지정이 되지 않았을 경우
			|| ( count( $buttons ) !== 0 && !trim( $buttons['name'] ) ) // 버튼 이름 지정이 되지 않았을 경우
			|| !is_array( $buttons ) // 버튼이 배열이 아닐 경우 (버튼은 반드시 배열로 넘겨야함)
		)
		// 버튼 타입이 웹 링크(WL) 일 경우, 검사
		|| (
			( $buttons['type'] == 'WL' && !trim( $buttons['linkMobile'] ) ) // 모바일 링크가 지정되지 않은 경우
			|| ( $buttons['type'] == 'WL' && !trim( $buttons['linkPc'] ) ) // PC 링크가 지정되지 않은 경우
		)
		// 버튼 타입이 앱 링크(AL) 일 경우, 검사
		|| (
			( $buttons['type'] == 'AL' && !trim( $buttons['schemeIos'] ) ) // iOS 스키마가 지정되지 않은 경우
			|| ( $buttons['type'] == 'AL' && !trim( $buttons['schemeAndroid'] ) ) // Android 스키마가 지정되지 않은 경우
		)
	){ 
		ncTalkDebug('필수 값이 입력되지 않았거나 형식에 맞지 않음.'); 
		return false;
	}
	// ##1 ( 필수 값 검사 루틴 종료)

	// 입력한 값을 바탕으로하여 데이터 작성.
	$postData = array(
		'templateCode' => $templateCode,
		'plusFriendId' => $ncGaram['plusFriendId'],
		'messages' => array(
			0 => array(
				'to' => $phoneNum,
				'content' => $content,
				'buttons' => $buttons
			)
		)
	);
	
	// 버튼 json 인코딩.
	$postData = json_encode( $postData );
	
	// 타임 스탬프 지정 (Timeout : 5 minutes)
	$timestamp = round( microtime( true ) * 1000 );
	
	// 요청 URL 지정.
	$requestUrl = '/alimtalk/'.$ncGaram['version'].'/services/'.urlencode($ncGaram['serviceId']).'/messages';
	
	// HmacSHA256 으로 시그니쳐 생성.
	$signature = base64_encode(
		hash_hmac(
			'sha256', // Hmac 중 sha256으로 해시를 생성함
			"POST "   // POST로 데이터를 보냄. (아래 cURL 참조)
			.$requestUrl // /alimtalk/v2/~~~~~/messages
			.PHP_EOL
			.$timestamp  // 13453245632 (타임스탬프 값)
			.PHP_EOL
			.$ncGaram['subKeyId'],
			$ncGaram['subKeySecret'],
			true
		)
	);
	
	// 인증용 헤더 생성.
	// 200 => 시그니쳐 오류, 400 => 데이터 입력 오류(데이터 확인 필수)
	$authHeader = array( 
		'accept: application/json; charset=UTF-8',
		'Content-Type: application/json; charset=utf-8',
		'x-ncp-apigw-timestamp: '.$timestamp,
		'x-ncp-apigw-api-key: '.$ncGaram['primaryKey'], 
		'x-ncp-iam-access-key: '.$ncGaram['subKeyId'],
		'x-ncp-apigw-signature-v2: '.$signature
	);
	
	// ncloud 서버에 알림톡 전송
	$curl = curl_init();
	$curlUrl = $ncGaram['endpoint'].'/'.$ncGaram['version'].'/services/'.urlencode($ncGaram['serviceId']).'/messages';
	curl_setopt( $curl, CURLOPT_URL, $curlUrl ); // 접속할 URL을 입력함.
	curl_setopt( $curl, CURLOPT_HEADER, 0 ); // Response 헤더 값 나오지 않게 설정함.
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 데이터 반환을 curl_exec를 통해서만 되게 처리함.
	curl_setopt( $curl, CURLOPT_HTTPHEADER, $authHeader ); // Request 헤더
	curl_setopt( $curl, CURLOPT_POST, 1 );
	curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'POST' );
	curl_setopt( $curl, CURLOPT_POSTFIELDS, $postData ); //http_build_query
	$exec = curl_exec( $curl );
	curl_close( $curl );
	ncTalkDebug( $exec );
	
	// 서버에서 전달받은 JSON 값을 PHP 배열로 바꿈.
	$return = json_decode($exec, true);
	
	// 만약, 서버에서 전달받은 상태가 성공이라면
	if( $return['messages'][0]['requestStatusCode'] == 'A000' ){ 
		ncTalkDebug( '알림톡 전송 성공. 알림톡이 오지 않는 경우 탬플릿을 반드시 점검하기 바랍니다.' );
		return true;
	}else{
		ncTalkDebug( '알림톡 전송 실패.' );
	}
	
}

// 화면에 디버그 메시지 표시 (최고관리자가 아니라면 표시하지 않음)
function ncTalkDebug($msg){
	global $ncGaram, $is_admin;
	if( $ncGaram['debug'] !== true || $is_admin !== 'super' ){ return false; }

	echo '<div style="font-size:12px; font-family:\'Apple SD Gothic Neo\',\'NanumGothic\',\'Nanum Gothic\'\'Dotum\',\'Gulim\'; display:block; font-weight:normal;">[DEBUG] - ';
	echo $msg;
	echo '</div>';
}