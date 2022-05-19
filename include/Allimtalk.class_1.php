<?php
class Allimtalk{
    public $resultCode;
    public $resultMessge;
    public $cellphone;

    private function sendMessage($templateNo = null, $message = null, $btnName = null, $btnLink = null){
        if(($templateNo != null && $templateNo != "") && ($message != null && $message != "") ){
            $btnCheck = ((($btnName != null && $btnName != "") && ($btnLink != null && $btnLink != ""))?true:false);
            $insert_query = "INSERT INTO TSMS_ATALK_AGENT_MESSAGE (
                        MESSAGE_SEQNO
                        ,SERVICE_SEQNO
                        ,SEND_MESSAGE
                        ,BACKUP_PROCESS_CODE
                        ,MESSAGE_TYPE
                        ,CONTENTS_TYPE
                        ,RECEIVE_MOBILE_NO
                        ,JOB_TYPE
                        ,SEND_RESERVE_DATE
                        ,TEMPLATE_CODE
                        ,REGISTER_DATE
                        ,REGISTER_BY
                        ,IMG_ATTACH_FLAG
                        ".(($btnCheck)?",KKO_BTN_NAME":"")."
                        ".(($btnCheck)?",KKO_BTN_URL":"")."
                ) VALUES ( 
                        TSMS_GET_SEQNO()
                        ,'1910030046'   
                        ,'{$message}'
                        ,'003'                
                        ,'002'
                        ,'004'
                        ,'{$this->cellphone}'
                        ,'R00'
                        ,CURRENT_TIMESTAMP
                        ,'{$templateNo}'         
                        ,CURRENT_TIMESTAMP
                        ,'TESTER'
                        ,'N'
                        ".(($btnCheck)?",'{$btnName}'":"")."
                        ".(($btnCheck)?",'{$btnLink}'":"")."
                    )";
            $result = mysql_query($insert_query);
            $this->query = $insert_query;
            if($result){
                $this->resultMessge = "발송 요청 성공";
                return true;
            }else{
                $this->resultMessge = "데이터베이스 오류";
                return false;
            }
        }else{
            $this->resultMessge = "템플릿 번호와 메세지 내용이 필요합니다.";
            return false;
        }
    }

    public function sendScheduleEndNotice($customerName, $petName, $shopName, $time){
        $templateNo = "13414";
        $message = 
        "애견뷰티 플랫폼, Go펫에서 ".$customerName."님의 ".addslashes($petName)." 미용 종료 시간을 알려드립니다."
        ."\n\n".addslashes($shopName)."에서 미용을 마무리하고 있네요. 미용 종료 ".$time."입니다." 
        ."\n\n깔끔하고 더 귀여워진 ".addslashes($petName)."를 만나러 가세요^^";

        return $this->sendMessage($templateNo, $message, null, null);
    }

    public function sendReservationNotice($customerName, $petName, $shopName, $date, $btnLink){
        $templateNo = "13398";
        $btnName = "예약정보";
        $message = 
        "애견뷰티 플랫폼, Go펫에서 ".$customerName."님의 ".addslashes($petName)." "."미용예약내용을 알려드립니다."
        ."\n- 예약펫샵: ".addslashes($shopName)
        ."\n- 예약일시: ".$date
        ."\n예약내용 상세 확인과 예약은 Go펫에서도 확인 가능하오니 많은 이용바랍니다.";

        return $this->sendMessage($templateNo, $message, $btnName, $btnLink);
    }

    //----- 펫샵에서 예약 등록할 경우
    public function sendReservationNoticeTempUser($customerNumber, $petName, $shopName, $date, $btnLink){
        $templateNo = "13950";
        $btnName = "예약내용";
        $message = $customerNumber."님의 ".addslashes($petName)." 예약이 내일이네요^^"
        ."\n\n반려동물 뷰티플랫폼, Go펫에서 내일 예약내용을 알려드립니다."
        ."\n - 예약펫샵: ".addslashes($shopName)
        ."\n - 예약일시: ".$date
        ."\n 예약내용 상세확인과 변경은 Go펫에서도 가능하오니 많은 이용바랍니다."
        ."\n\n*No Show없는 예약문화! Go펫과 함께하세요~ ";

        return $this->sendMessage($templateNo, $message, $btnName, $btnLink);
    }

    public function sendUpdateNotice($customerName, $petName, $shopName, $date, $changeDate, $btnLink){
        $templateNo = "13399";
        $btnName = "예약변경";
        $message = 
        "애견뷰티 플랫폼, Go펫에서 ".$customerName."님의 ".addslashes($petName)." "."미용예약변경 내용을 알려드립니다."
        ."\n- 예약펫샵 : ".addslashes($shopName)
        ."\n- 기존예약 : ".$date
        ."\n- 변경일시 : ".$changeDate
        ."\n예약내용 상세 확인과 예약은 Go펫에서도 확인 가능하오니 많은 이용바랍니다.";

        return $this->sendMessage($templateNo, $message, $btnName, $btnLink);
    }
    
    


    /*----- 반짝으로 리뉴얼 후 새로운 변경된 템플릿 -----*/
    public function sendHotelAgree_new($customerNumber, $petName, $shopName, $date, $btnLink){
        $templateNo = "15845";
        $btnName = "호텔동의서작성보기";
        $message = "반려생활의 단짝, 반짝에서 ".$customerNumber."님이 작성해주신 동의서를 공유해 드립니다."
		."\n\n - 이용샵 : ".addslashes($shopName)
		."\n - 펫 이름 : ".addslashes($petName)
		."\n - 작성일시 : ".$date
		."\n\n자세히 보기를 클릭하시면 동의서 원본을 확인하실 수 있습니다.";

        return $this->sendMessage($templateNo, $message, $btnName, $btnLink);
    }

    public function sendOrderShipping_new($customerNumber, $orderNum, $productName, $shoppingInvoice, $btnLink){
        $templateNo = "14759";
        $btnName = "주문배송 확인하기";
        $message = "반려생활의 단짝, 반짝에서 ".$customerNumber."님께서 주문하신 상품의 배송이 시작되어 알려드립니다."
		."\n\n▶ 주문번호 : ".$orderNum
		."\n▶ 상품명 : ".addslashes($productName)
		."\n▶ 택배사(송장번호) : ".$shoppingInvoice
		."\n\n상세한 주문확인과 배송정보는 반짝에서 확인가능합니다.";

        return $this->sendMessage($templateNo, $message, $btnName, $btnLink);
    }

    public function sendOrderDeposit_new($customerNumber, $shippingName, $totalPrice, $orderNum, $payDt, $productName, $btnLink){
        $templateNo = "14758";
        $btnName = "주문내역 확인하기";
        $message = "반려생활의 단짝, 반짝에서 ".$customerNumber."님의 입금확인 완료와 주문내역을 알려드립니다. "
		."\n\n▶ 입금자명 : ".addslashes($shippingName)
		."\n▶ 입금액 : ".$totalPrice
		."\n================="
		."\n\n▶ 주문번호 : ".$orderNum
		."\n▶ 주문일시 : ".$payDt
		."\n▶ 주문상품 : ".addslashes($productName)
		."\n\n\n상세한 주문내역은 반짝에서 확인하실 수 있습니다."
		."\n\n주문하신 상품을 빨리 받아보실수 있게 최선을 다하겠습니다.";

        return $this->sendMessage($templateNo, $message, $btnName, $btnLink);
    }

	public function sendOrderAccount_new($customerNumber, $orderNum, $payDt, $productName, $shippingDt, $totalPrice, $shippingName, $btnLink){
        $templateNo = "14757";
        $btnName = "주문내역 확인하기";
        $message = "반려생활의 단짝, 반짝에서 ".$customerNumber."님의 주문내역(계좌이체)을 알려드립니다."
		."\n\n▶ 주문번호 : ".$orderNum
		."\n▶ 주문일시 : ".$payDt
		."\n▶ 주문상품 : ".addslashes($productName)
		."\n\n계좌이체 주문은 아래 계좌로 입금기한까지 입금을 하셔야 주문이 확정완료되며 입금기한이 지나면 주문이 자동취소되오니 유의하시기 바랍니다."
		."\n\n▶ 입금은행 : 기업은행"
		."\n▶ 입금계좌 : 054-143076-01-013 / 주식회사 펫이지"
		."\n▶ 입금기한 : ".$shippingDt
		."\n=================="
		."\n▶ 입금하실 금액 : ".$totalPrice
		."\n▶ 입금자명 : ".addslashes($shippingName)
		."\n\n\n상세한 주문내역은 반짝에서 확인하실 수 있습니다. ";

        return $this->sendMessage($templateNo, $message, $btnName, $btnLink);
    }

    public function sendOrderReceipt_new($customerNumber, $orderNum, $payDt, $productName, $btnLink){
        $templateNo = "14756";
        $btnName = "주문내역 확인하기";
        $message = "반려생활의 단짝, 반짝에서 ".$customerNumber."님의 주문내역을 알려드립니다."
		."\n\n▶ 주문번호 : ".$orderNum
		."\n▶ 주문일시 : ".$payDt
		."\n▶ 주문상품 : ".addslashes($productName)
		."\n\n상세한 주문내역은 반짝에서 확인하실 수 있습니다."
		."\n\n주문하신 상품을 빨리 받아보실수 있게 최선을 다하겠습니다.";

        return $this->sendMessage($templateNo, $message, $btnName, $btnLink);
    }

    public function sendBeautyAgree_new($customerNumber, $petName, $shopName, $date, $btnLink){
        $templateNo = "14516";
        $btnName = "미용동의서";
        $message = "반려생활의 단짝, 반짝에서 ".$customerNumber."님이 작성해주신 미용동의서를 공유해 드립니다."
		."\n\n - 이용샵 : ".addslashes($shopName)
		."\n - 펫 이름 : ".addslashes($petName)
		."\n - 작성일시 : ".$date
		."\n\n자세히 보기를 클릭하시면 미용동의서 원본을 확인하실 수 있습니다.";

        return $this->sendMessage($templateNo, $message, $btnName, $btnLink);
    }
	
	public function sendReservationNoticeTempUser_new($customerNumber, $petName, $shopName, $date, $btnLink){
        $templateNo = "14043";
        $btnName = "예약내용";
        $message = $customerNumber."님의 ".addslashes($petName)." 예약이 내일이네요^^"
        ."\n\n 반려생활의 단짝, 반짝에서 내일 예약 내용을 알려드립니다."
        ."\n\n - 예약펫샵: ".addslashes($shopName)
        ."\n - 예약일시: ".$date
        ."\n\n 예약내용 상세 확인과 변경은 반려생활의 단짝, 반짝에서도 가능합니다.";

        return $this->sendMessage($templateNo, $message, $btnName, $btnLink);
    }

    public function sendScheduleEndNotice_new($customerName, $petName, $shopName, $time){
        $templateNo = "14042";
        $message = 
        "반려생활의 단짝, 반짝에서 ".$customerName."님의 ".addslashes($petName)." 미용 종료시간을 알려드립니다."
        ."\n\n".addslashes($shopName)."에서 미용을 마무리하고 있네요. 미용 종료 ".$time."입니다." 
        ."\n\n깔끔하고 더 귀여워진 ".addslashes($petName)
        ."\n이제 만나러 가세요^^"
        ."\n\n반짝반짝 반려생활의 단짝, 반짝에서 알려드렸습니다.";

        return $this->sendMessage($templateNo, $message, null, null);
    }

    public function sendUpdateNotice_new($customerName, $petName, $shopName, $date, $changeDate, $btnLink){
        $templateNo = "14041";
        $btnName = "예약변경";
        $message = 
        "반려생활의 단짝, 반짝에서 ".$customerName."님의 ".addslashes($petName)." 미용 예약 변경 내용을 알려드립니다."
        ."\n\n - 예약펫샵 : ".addslashes($shopName)
        ."\n - 기존예약 : ".$date
        ."\n - 변경일시 : ".$changeDate
        ."\n\n예약내용 상세 확인과 예약은 반려생활의 단짝, 반짝에서도 가능합니다.";

        return $this->sendMessage($templateNo, $message, $btnName, $btnLink);
    }

    public function sendReservationNotice_new($customerName, $petName, $shopName, $date, $btnLink){
        $templateNo = "14040";
        $btnName = "예약내용";
        $message =
        "반려생활의 단짝, 반짝에서 ".$customerName."님의 ".addslashes($petName)."미용예약 내용을 알려드립니다."
        ."\n\n - 예약펫샵: ".addslashes($shopName)
        ."\n - 예약일시: ".$date
        ."\n\n 예약내용 상세확인과 예약은 반려생활의 단짝, 반짝에서도 가능합니다.";

        return $this->sendMessage($templateNo, $message, $btnName, $btnLink);
    }
    /*----- 반짝으로 리뉴얼 후 새로운 변경된 템플릿 ----- */


}
