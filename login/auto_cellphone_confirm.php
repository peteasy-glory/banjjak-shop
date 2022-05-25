<?
/*==========================================================
* 파일명 : ci_web.php
* 작성자 : 기술지원팀
* 작성일 : 2014.01
* 용  도 : 사용자인증 Weblink 방식 결제 연동 페이지
* 버	전 : 0003

* 가맹점의 소스 임의변경에 따른 책임은 모빌리언스에서 책임을 지지 않습니다.
* 요청 파라미터 및 결제 후  가맹점측  Okurl로 Return 되는 파라미터와 가맹점 서비스처리 방법은
* 연동 매뉴얼을 반드시 참조하세요.
* 실서버 전환시 꼭 모빌리언스 기술지원팀으로 연락바랍니다.
*==========================================================*/
?>

<?
/*****************************************************************************************
- 결제수단 캐시 구분
 *****************************************************************************************/
$CASH_GB 		= "CI"; 	//대표결제수단

/*****************************************************************************************
- 필수 입력 항목 
 *****************************************************************************************/
$PAY_MODE		= "00"; 	//연동시 테스트,실결제구분 ( 00 : 테스트결제, 10 : 실거래결제   )
$Siteurl		= "gobeauty.kr"; 		//가맹점도메인
$Tradeid		= strval(uniqid()); 		//가맹점거래번호

$CI_SVCID		= "190123066519"; 		//서비스아이디
$CI_Mode		= "61"; 	// 61:SMS발송, 67:SMS미발송


$Okurl 			= $_REQUEST['okurl'];//"http://gobeauty.kr/mainpage/regist_shop_auth.php";		//성공URL : 결제완료통보페이지 full Url (예:http://www.mcash.co.kr/okurl.jsp )	

$Cryptyn		= "Y";			//암호화 사용 여부 (default : Y)
$Keygb			= "0";			//암호화 Key 구분 (0 : CI_SVCID 8자리, 1·2 : 가맹점 관리자 등록 후 사용)

/*****************************************************************************************
- 선택 입력 항목 
 *****************************************************************************************/
$Callback		= "";			//SMS,LMS발신번호
$Smstext		= "";			//SMS문구
$Lmstitle		= "";			//LMS제목
$Lmstext		= "";			//LMS문구

$SUB_CPID		= ""; 		//SUB대행사 식별코드
$DI_CODE		= "X18042419260"; 		//DI웹사이트코드 (12byte)
$CI_FIXCOMMID	= "";		//이동통신사 고정 시 사용
$Closeurl		= "";			//취소버튼 클릭 시 이동할 페이지 (예:http://www.mcash.co.kr/failurl.jsp)

$MSTR				= ""; 		//가맹점콜백변수
//가맹점에서 추가적으로 파라미터가 필요한 경우 사용하며 &, % 는 사용불가 ( 예 : MSTR="a=1|b=2|c=3" )

/*****************************************************************************************
- 디자인 관련 선택항목 ( 향후  변경될 수 있습니다  )
 *****************************************************************************************/
$LOGO_YN		= "N"; 		//가맹점 로고 사용여부  ( 가맹점 로고 사용시 'Y'로 설정, 사전에 모빌리언스에 가맹점 로고 이미지가 있어야함  )
$CALL_TYPE	= "SELF"; 		//결제창 호출방식( SELF 페이지전환 , P 팝업 )


?>


<!--  가맹점의 결제요청 페이지 -->
<html>
<head>
    <?
    /*****************************************************************************************
    가맹점에서는 아래 js 파일을 반드시 include
    실 결제환경 구성시 모빌리언스 담당자와 상의 요망
     *****************************************************************************************/
    ?>
</head>
<body  >

<form name="payForm" accept-charset='euc-kr'>

    <table border="1" width="100%" style="display:none;">
        <tr>
            <td align="center" colspan="6"><font size="15pt"><b>휴대폰본인확인 SAMPLE PAGE</b></td>
        </tr>

        <tr>
            <td align="center">대표결제수단</td>
            <td align="center">CASH_GB</td>
            <td><input type="text" name="CASH_GB" id="CASH_GB" value="<?echo $CASH_GB ?>"></td>
            <td align="center">서비스아이디</td>
            <td align="center">CI_SVCID</td>
            <td><input type="text" name="CI_SVCID" id="CI_SVCID"  value="<?echo $CI_SVCID ?>"></td>
        </tr>

        <tr>
            <td align="center">암호화사용여부</td>
            <td align="center">Cryptyn</td>
            <td><input type="text" name="Cryptyn" id="Cryptyn" value="<?echo $Cryptyn ?>"></td>
            <td align="center">암호화Key구분</td>
            <td align="center">Keygb</td>
            <td><input type="text" name="Keygb" id="Keygb"  value="<?echo $Keygb ?>"></td>
        </tr>

        <tr>
            <td align="center">결제창 호출방식</td>
            <td align="center">CALL_TYPE</td>
            <td><input type="text" name="CALL_TYPE" id="CALL_TYPE" value="<?echo $CALL_TYPE ?>"></td>
            <td align="center">가맹점 로고 사용여부</td>
            <td align="center">LOGO_YN</td>
            <td><input type="text" name="LOGO_YN" id="LOGO_YN" value="<?echo $LOGO_YN ?>"></td>
        </tr>

        <tr>
            <td align="center">결제단계구분</td>
            <td align="center">CI_Mode</td>
            <td><input type="text" name="CI_Mode" id="CI_Mode" value="<?echo $CI_Mode ?>"></td>
            <td align="center">DI웹사이트코드</td>
            <td align="center">DI_CODE</td>
            <td><input type="text" name="DI_CODE" id="DI_CODE" value="<?echo $DI_CODE ?>"></td>
        </tr>
        <tr>
            <td align="center">가맹점도메인</td>
            <td align="center">Siteurl</td>
            <td><input type="text" name="Siteurl" id="Siteurl" value="<?echo $Siteurl ?>"></td>
            <td align="center">SUB대행사식별</td>
            <td align="center">SUB_CPID</td>
            <td><input type="text" name="SUB_CPID" id="SUB_CPID" value="<?echo $SUB_CPID ?>"></td>
        </tr>
        <tr>
            <td align="center">발신번호</td>
            <td align="center">Callback</td>
            <td><input type="text" name="Callback" id="Callback" size="30" value="<?echo $Callback ?>"></td>
            <td align="center">SMS문구</td>
            <td align="center">Smstext</td>
            <td><input type="text" name="Smstext" id="Smstext" size="30" value="<?echo $Smstext ?>"></td>
        </tr>
        <tr>
            <td align="center">LMS제목</td>
            <td align="center">Lmstitle</td>
            <td><input type="text" name="Lmstitle" id="Lmstitle" size="40" value="<?echo $Lmstitle ?>"></td>
            <td align="center">LMS본문</td>
            <td align="center">Lmstext</td>
            <td><textarea name="Lmstext" id="Lmstext" rows="6" value="<?echo $Lmstext ?>"></textarea></td>
        </tr>
        <tr>
            <td align="center">가맹점거래번호</td>
            <td align="center">Tradeid</td>
            <td><input type="text" name="Tradeid" id="Tradeid" size="30" value="<?echo $Tradeid ?>"></td>
            <td align="center">거래종류</td>
            <td align="center">PAY_MODE</td>
            <td>
                <select name="PAY_MODE">
                    <option value="10">실서버(10)</option>
                    <option value="00">테스트(00)</option>
                </select>
            </td>
        </tr>
        <tr>
            <td align="center">성공URL</td>
            <td align="center">*Okurl</td>
            <td><input type="text" name="Okurl" id="Okurl" size="30" value="<?echo $Okurl ?>"></td>
            <td align="center">Okurl암호화</td>
            <td align="center">Cryptokurl</td>
            <td>
                <select name="Cryptokurl" id="Cryptokurl">
                    <option value="Y">사용</option>
                </select>
            </td>
        </tr>
        <tr>
            <td align="center">이통사고정</td>
            <td align="center">CI_FIXCOMMID</td>
            <td>
                <select name="CI_FIXCOMMID" id="CI_FIXCOMMID">
                    <option value="">사용안함</option>
                    <option value="SKT">SKT</option>
                    <option value="KTF">KTF</option>
                    <option value="LGT">LGT</option>
                    <option value="SKR">SKT알뜰폰</option>
                </select>
            </td>
            <td align="center">Closeurl</td>
            <td align="center">Closeurl</td>
            <td><input type="text" name="Closeurl" id="Closeurl" size="30" value="<?echo $Closeurl ?>"></td>
        </tr>

        <tr>
            <td align="center" colspan="6"> </td>
        </tr>
        <tr>
            <td align="center" colspan="6"><input type="button" value="옜옜옜" onclick="payRequest()"></td>
        </tr>
    </table>

</form>
</body>
</html>

<script src="https://auth.mobilians.co.kr/js/ext/ext_inc_comm.js"></script>
<script language="javascript">
    MCASH_PAYMENT(document.payForm);
</script>

