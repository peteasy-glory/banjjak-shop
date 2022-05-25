<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");
include($_SERVER['DOCUMENT_ROOT']."/include/simple_html_dom.php");


$error = array();

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

$shop_sql = "SELECT * FROM tb_shop WHERE customer_id = '{$user_id}'";
$shop_result = mysqli_query($connection, $shop_sql);
if ($shop_datas = mysqli_fetch_object($shop_result)) {
    $shop_name = $shop_datas->name;

    $search = $_POST["search"];
    $page = $_POST["page"];

    if($search == null || $search == ""){
        $search = $shop_name;
    }
    if($page == null || $page == ""){
        $page = 1;
    }

    $client_id = $naver_client_id;
    $client_secret = $naver_client_secret_key;
    $encText = urlencode($search);
    $display_cnt = 10;
    $start = (($page - 1) * $display_cnt) + 1;

    $url = "https://openapi.naver.com/v1/search/blog.json?query=".$encText."&display=".$display_cnt."&start=".$start; // json 결과
    $is_post = false;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $is_post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $headers = array();
    $headers[] = "X-Naver-Client-Id: ".$client_id;
    $headers[] = "X-Naver-Client-Secret: ".$client_secret;

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec ($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close ($ch);

    $search_data = null;

    $build_date = date("Y-m-d H:i:s");
    $total = 0;
    $page_count = 0;
    $blog_list = array();

    if($status_code == 200) {
        $search_data = json_decode($response);

        $build_date = date("Y-m-d H:i:s", strtotime($search_data->lastBuildDate));
        $total = $search_data->total;
        $page_count = ceil($total / $display_cnt);
        $blog_list = $search_data->items;

        if($start <= $total){
            //이미 등록한 것인지 검사하기
            $check_query = "SELECT * FROM tb_blog WHERE customer_id = '{$user_id}'";
            $check_result = mysqli_query($connection, $check_query);
            $check_list = array();
            $check_seq_list = array();
            $check_delete_list = array();

            while($check_data = mysqli_fetch_object($check_result)){
                $check_list[] = $check_data->link;
                $check_seq_list[] = $check_data->blog_seq;
                $check_delete_list[] = $check_data->del_yn;
            }

            //썸네일 구하기
            $thumb_list = array();
            $link_key_list = array();
            for($i = 0 ; $i < 3 ; $i++){
                $crawl_start = ((($page + $i) - 1) * $display_cnt) + 1;
                $link = "https://search.naver.com/search.naver?query=".$encText."&where=post&start=".$crawl_start;
                $curl_content = "";
                if (function_exists('curl_init')) { 
                    $ch = curl_init(); 

                    curl_setopt($ch, CURLOPT_URL, $link); 
                    curl_setopt($ch, CURLOPT_HEADER, 0); 
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0'); 

                    $curl_content = curl_exec($ch); 
                    curl_close($ch); 
                } 
                
                $html = new simple_html_dom();
                $html->load($curl_content);

                foreach($html->find('li.sh_blog_top') as $element){
                    $thumb_data = array();

                    $thumb_data["title"] = trim(strip_tags($element->find("a.sh_blog_title", 0)->innertext));
                    $thumb_data["link"] = $element->find("a.sp_thmb", 0)->href;
                    $thumb_data["thumbnail"] = $element->find("img.sh_blog_thumbnail", 0)->src;
                    
                    $thumb_list[] = $thumb_data;
                    $title_key_list[] = $thumb_data["title"];
                }
            }

            //구해온 썸네일과 합치기
            foreach($blog_list as $key => $data){
                $new_data = array();

                $new_data["title"] = $data->title;
                $new_data["desc"] = $data->description;
                $new_data["link"] = $data->link;
                $new_data["thumb"] = "";
                $new_data["blogger_name"] = $data->bloggername;
                $new_data["post_date"] = date("Y-m-d", strtotime($data->postdate));

                $needle = trim(strip_tags($new_data["title"]));

                $thumb_key = array_search($needle, $title_key_list);
                if($thumb_key !== FALSE){
                    $thumb_data = $thumb_list[$thumb_key];
                    $new_data["thumb"] = $thumb_data["thumbnail"];
                }

                //이미 저장한 것인지 검사하기
                if(count($check_list) > 0){
                    $check_key = array_search($new_data["link"], $check_list);
                    if($check_key !== FALSE){
                        $new_data["blog_seq"] = $check_seq_list[$check_key];

                        $del_yn = $check_delete_list[$check_key];
                        $new_data["del_yn"] = $del_yn;
                        
                        if($del_yn == "Y"){
                            $new_data["mode"] = "delete";
                        }else{
                            $new_data["mode"] = "update";
                        }
                    }else{
                        $new_data["mode"] = "write";
                    }
                }else{
                    $new_data["mode"] = "write";
                }

                $new_data["search"] = $search;
                $new_data["page"] = $page;
                $new_data["start"] = $start;
                $new_data["total"] = $total;

                $blog_list[$key] = $new_data;
            }

            echo json_encode($blog_list, JSON_UNESCAPED_UNICODE);
        }else{
            echo json_encode($error, JSON_UNESCAPED_UNICODE);
        }
    }else{
        echo json_encode($error, JSON_UNESCAPED_UNICODE);
    }
}else{
    echo json_encode($error, JSON_UNESCAPED_UNICODE);
}
?>