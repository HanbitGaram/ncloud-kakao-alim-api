# ncloud-kakao-alim-api
네이버 NCloud용 카카오톡 알림톡 API<br>
네이버에서 제공하는 ncloud의 카카오톡 비즈메시지의 알림톡을 쉽게 사용할 수 있게 만든 그누보드 플러그인형 API입니다.

사실 그누보드 호환성만 고려한 것으로, 모듈로 떼어서 사용해도 무방합니다.

본 프로그램은 MIT License를 따르며, PHP 7.x 에서 개발되었으므로, 하위버전의 호환성은 고려하지 않았습니다.

## 그누보드 설치 방법
### 파일 플러그인 폴더 업로드
/plugin/hanbitgaram/nc_alimtalk 을 그누보드 /plugin/ 에 업로드 합니다.
/plugin/hanbitgaram/nc_alimtalk/user_config.php 파일 내에 api키와 설정 값을 집어넣습니다.

### 파일 extend 폴더 업로드
/extend/ 폴더에 hanbitgaram.ncalimtalk.extend.php 를 업로드합니다.

### 함수 사용방법
사용방법 - nctalk(**String** $templateCode, **String** $PhoneNum, **String** $content, **Array** $buttons);

#### $buttons 옵션
>**String** type
>> 배송조회 - DS<br>
>> 웹 링크 - WL<br>
>> 앱 링크 - AL<br>
>> 봇 키워드 - BK<br>
>> 메시지 전달 - MD<br>

> **String** name - 버튼 이름<br>
> **String** linkMobile - 모바일 웹 링크 <br>
> **String** linkPc - PC 웹 링크 <br>
> **String** schemeIos - 아이폰용 앱 스키마<br>
> **String** schemeAndroid - 안드로이드용 앱 스키마<br>
