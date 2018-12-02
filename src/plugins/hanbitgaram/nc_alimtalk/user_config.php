<?php
	if(!defined('_GNUBOARD_')) exit; // 그누보드 개별 파일 실행 방지

	// API 서버 설정 (여긴 특별한 사유가 없는 이상 건드리지 마세요)
	$ncGaram['endpoint'] = 'https://sens.apigw.ntruss.com/alimtalk'; // SENS API Endpoint 주소
	$ncGaram['version'] = 'v2'; // SENS API Endpoint 버전 (기준 : 버전 2)

	// API 사용자 설정 (여기 값을 수정해서 사용하세요)
	$ncGaram['debug'] = true; // 디버그 모드 설정 = true, 디버그 모드 해제 - false
	$ncGaram['serviceId'] = ''; // ncp: 로 시작하는 API키
	$ncGaram['plusFriendId'] = ''; // @를 포함한 플러스친구 아이디
	
	$ncGaram['subKeyId'] = ''; // 서브계정에서 생성한 접큰키 아이디
	$ncGaram['subKeySecret'] = ''; // 서브계정에서 생성한 시크릿 키

	$ncGaram['primaryKey'] = ''; // API Gateway 에서 부여받은 키