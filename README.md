# ncloud-kakao-alim-api
네이버 NCloud용 카카오톡 알림톡 API<br>
네이버에서 제공하는 ncloud의 카카오톡 비즈메시지의 알림톡을 쉽게 사용할 수 있게 만든 그누보드 플러그인형 API입니다.

사실 그누보드 호환성만 고려한 것으로, 모듈로 떼어서 사용해도 무방합니다.

본 프로그램은 MIT License를 따르며, PHP 7.x 에서 개발되었으므로, 하위버전의 호환성은 고려하지 않았습니다.

## 그누보드 설치 방법
### 파일 플러그인 폴더 업로드
/plugin/hanbitgaram/nc_alimtalk 을 그누보드 /plugin/ 에 업로드 합니다.<br>
/plugin/hanbitgaram/nc_alimtalk/user_config.php 파일 내에 api키와 설정 값을 집어넣습니다.

### 파일 extend 폴더 업로드
/extend/ 폴더에 hanbitgaram.ncalimtalk.extend.php 를 업로드합니다.

### 함수 사용방법
사용방법<br>
<code> nctalk(**String** $templateCode, **String** $PhoneNum, **String** $content, **Array** $buttons); </code>

#### 함수 사용 예시
 \<?php<br>
    $templateCode = 'delivery'; // 탬플릿 코드<br>
    $phoneNum = '01012345678'; // 받는 이 휴대폰 번호<br>
    $content = "안녕하세요. **한빛가람**님.<br>
     고객님의 주문번호(**201810100310234**)이 오늘 발송 예정입니다.<br>
     <br>
     물품명 : **Applus HackBook 외 3건**<br>
     주문번호 : **201810100310234**<br>
     주소 : **사랑광역시 행복구 소망으로가는길로 56길 13, 77호 (화평동)**<br>
     <br>
     주문 후 하자품은 이상시 3일 내로 고객센터를 통해 무상 교환해야합니다.<br>
     "; // 탬플릿 내용<br>
    nctalk($templateCode, '01012345678', $content); // 알림톡 발송 <br>
  ?\>

#### $buttons 옵션
- **String** type
  - 배송조회 - DS<br>
  - 웹 링크 - WL<br>
  - 앱 링크 - AL<br>
  - 봇 키워드 - BK<br>
  - 메시지 전달 - MD<br>
-  **String** name - 버튼 이름<br>
-  **String** linkMobile - 모바일 웹 링크 <br>
-  **String** linkPc - PC 웹 링크 <br>
-  **String** schemeIos - 아이폰용 앱 스키마<br>
-  **String** schemeAndroid - 안드로이드용 앱 스키마<br>

## 외부 라이브러리 용도 사용방법
### 상수치환
- *G5_PLUGIN_PATH* 은(는) 그누보드의 플러그인 폴더의 절대경로를 의미합니다.
- *G5_PLUGIN_URL* 은(는) 그누보드 플러그인 폴더의 웹상 절대주소를 의미합니다.
