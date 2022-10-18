function siblings(el,i){

    return [...el.parentElement.children][i];

}

function allimi_btn_event(){

    Array.from(document.getElementsByClassName('allimi-check-title')).forEach(function(el){

        el.addEventListener('click',function(){

            if(el.classList.contains('actived')){
                document.getElementById(`${el.getAttribute('id')}`).parentElement.children[1].style.display = 'none';
                el.classList.remove('actived');

            }else{

                el.classList.add('actived');
                siblings(el,1).style.display = 'inline-flex';
            }





        })
    })

    Array.from(document.getElementsByClassName('allimi-form-one')).forEach(function(el){

        el.addEventListener('click',function(){


            if(el.parentElement.getAttribute('id') === 'check_list_attitude'){

                if(el.getAttribute('id') === 'attitude_etc'){

                    document.getElementById('allimi_attitude_textarea').style.display = 'block';
                }else{
                    document.getElementById('allimi_attitude_textarea').style.display = 'none';
                }


            }

            if(el.parentElement.getAttribute('id')==='check_list_tangle'){

                if(el.getAttribute('id') === 'tangle_etc'){

                    if(document.getElementById('allimi_tangle_textarea').style.display === 'block'){

                        document.getElementById('allimi_tangle_textarea').style.display = 'none';
                    }else{

                        document.getElementById('allimi_tangle_textarea').style.display = 'block';
                    }

                }
            }

            if(el.parentElement.getAttribute('id')==='check_list_bath'){

                if(el.getAttribute('id') === 'bath_etc'){

                    document.getElementById('allimi_bath_textarea').style.display = 'block';
                }else{
                    document.getElementById('allimi_bath_textarea').style.display = 'none';
                }
            }

            if(el.parentElement.getAttribute('id')==='check_list_skin'){

                if(el.getAttribute('id') === 'skin_etc'){

                    if(document.getElementById('allimi_skin_textarea').style.display === 'block'){

                        document.getElementById('allimi_skin_textarea').style.display = 'none';
                    }else{

                        document.getElementById('allimi_skin_textarea').style.display = 'block';
                    }

                }
            }

            if(el.parentElement.getAttribute('id')==='check_list_condition'){

                if(el.getAttribute('id') === 'condition_etc'){

                    document.getElementById('allimi_condition_textarea').style.display = 'block';
                }else{
                    document.getElementById('allimi_condition_textarea').style.display = 'none';
                }
            }

            if(el.parentElement.getAttribute('id')==='check_list_dislike'){

                if(el.getAttribute('id') === 'dislike_etc'){

                    if(document.getElementById('allimi_dislike_textarea').style.display === 'block'){

                        document.getElementById('allimi_dislike_textarea').style.display = 'none';
                    }else{

                        document.getElementById('allimi_dislike_textarea').style.display = 'block';
                    }

                }
            }

            if(el.parentElement.getAttribute('id')==='check_list_self'){

                if(el.getAttribute('id') === 'self_etc'){

                    if(document.getElementById('allimi_self_textarea').style.display === 'block'){

                        document.getElementById('allimi_self_textarea').style.display = 'none';
                    }else{

                        document.getElementById('allimi_self_textarea').style.display = 'block';
                    }

                }
            }


        })


    })
}

function allimi_send_pop(target,id){

    event.preventDefault()
    event.stopPropagation();


    let week = ['일','월','화','수','목','금','토']

    let payment_idx = target.getAttribute('data-payment_idx');
    let pet_seq = target.getAttribute('data-pet_seq');
    let cellphone = target.getAttribute('data-cellphone');
    let pet_name = target.getAttribute('data-pet_name');
    let beauty_date = target.getAttribute('data-date').replace(' ','T');

    document.getElementById('allimi_history_btn').setAttribute('data-artist_id',id);
    document.getElementById('allimi_history_btn').setAttribute('data-cellphone',cellphone);
    document.getElementById('allimi_history_btn').setAttribute('data-pet_seq',pet_seq);

    document.getElementById('allimi_preview_btn').setAttribute('data-artist_id',id);
    let date_ = new Date(beauty_date);
    let date = `${date_.getFullYear()}년 ${date_.getMonth()+1}월 ${date_.getDate()}일(${week[date_.getDay()]})`


    document.getElementById('allimi_date').innerText = date;

    document.getElementById('allimi_pet_list').innerHTML ='';
    document.getElementById('allimi_pet_list').innerHTML += `<label class="allimi-form">
                                                                            <input type="radio" name="allimi-pet" onclick="allimi_get_gallery(this,'${id}')" data-artist_id="${id}" data-pet_name="${pet_name}" data-cellphone="${cellphone}" data-payment_idx="${payment_idx}" data-pet_seq="${pet_seq}">
                                                                            <em></em>
                                                                            <span class="allimi-radio-span">${pet_name}</span>
                                                                        </label>`

    document.getElementById('allimi_open_gallery').setAttribute('data-payment_idx',payment_idx);
    document.getElementById('allimi_open_gallery').setAttribute('data-pet_seq',pet_seq);
    document.getElementById('allimi_open_gallery').setAttribute('data-cellphone',cellphone);
    document.getElementById('allimi_open_gallery').setAttribute('data-pet_name',pet_name);

    document.getElementById('allimi_select_photo').setAttribute('data-payment_idx',payment_idx);
    document.getElementById('allimi_select_photo').setAttribute('data-pet_seq',pet_seq);
    document.getElementById('allimi_select_photo').setAttribute('data-cellphone',cellphone);
    document.getElementById('allimi_select_photo').setAttribute('data-pet_name',pet_name);


    pop.open('allimi_pop')
}

function allimi_send_pop_customer(target,id){


    let cellphone = target.getAttribute('data-cellphone');

    document.getElementById('allimi_date_select').innerHTML ='';

    document.getElementById('allimi_history_btn').setAttribute('data-artist_id',id);
    document.getElementById('allimi_history_btn').setAttribute('data-cellphone',cellphone);

    document.getElementById('allimi_history_btn_2').setAttribute('data-artist_id',id);
    document.getElementById('allimi_history_btn_2').setAttribute('data-cellphone',cellphone);


    document.getElementById('allimi_preview_btn').setAttribute('data-artist_id',id);

    $.ajax({

        url:'/data/api.php',
        type:'post',
        data:{
            mode:'get_diary_date',
            artist_id:id,
            cellphone:cellphone
        },
        success:function(res) {
            let response = JSON.parse(res);
            let head = response.data.head;
            let body = response.data.body;
            if (head.code === 401) {
                pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
            } else if (head.code === 200) {

                if(body.length === undefined){
                    body = [body];
                }
                console.log(body)
                if(body.length>0){

                    let week = ['일','월','화','수','목','금','토'];
                    body.forEach(function(el){

                        let beauty_date = new Date(`${el.beauty_date.substr(0,4)}`,`${el.beauty_date.substr(4,2)}`,`${el.beauty_date.substr(6,2)}`);



                        document.getElementById('allimi_date_select').innerHTML += `<option value="${el.beauty_date}">${beauty_date.getFullYear()}년 ${beauty_date.getMonth()}월 ${beauty_date.getDate()}일 (${week[beauty_date.getDay()]})</option>`
                    })
                }

                setTimeout(function(){

                    document.getElementById('allimi_date_select').dispatchEvent(new Event('change'));
                },200);

            }
        }
    })

    pop.open('allimi_pop');

}

function allimi_date_select(target,id){

    let date = target.value;

    let cellphone = target.getAttribute('data-cellphone');

    $.ajax({

        url:'/data/api.php',
        type:'post',
        data:{
            mode:'get_diary_date_pet',
            artist_id:id,
            cellphone:cellphone,
            date:date,
        },
        success:function(res) {
            let response = JSON.parse(res);
            let head = response.data.head;
            let body = response.data.body;
            if (head.code === 401) {
                pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
            } else if (head.code === 200) {
                console.log(body)
                if(body.length === undefined){
                    body = [body];
                }

                if(body.length>0){
                    document.getElementById('allimi_pet_list').innerHTML = '';

                    body.forEach(function(el){

                        document.getElementById('allimi_pet_list').innerHTML += `<label class="allimi-form">
                                                                                                <input type="checkbox" name="allimi-pet" data-artist_id="${id}" data-pet_name="${el.name}" data-cellphone="${cellphone}" data-payment_idx="${el.payment_log_seq}" data-pet_seq="${el.pet_seq}">
                                                                                                <em></em>
                                                                                                <span class="allimi-radio-span">${el.name}</span>
                                                                                            </label>`
                    })
                }
            }
        }

    })


}
function allimi_open_gallery(){


    // document.getElementById('allimi-right-title').innerText = '미용 갤러리';
    document.getElementById('allimi_gallery').style.display = 'flex';

    document.getElementById('allimi_defalut').style.opacity = '0';
    document.getElementById('allimi_preview').style.opacity = '0';
    document.getElementById('allimi_history').style.opacity = '0';




    setTimeout(function(){
        document.getElementById('allimi_gallery').style.opacity = '1';
        document.getElementById('allimi_defalut').style.display ='none';
        document.getElementById('allimi_preview').style.display ='none';
        document.getElementById('allimi_history').style.display ='none';


    },200)




}


function allimi_open_history(target){

    let artist_id = target.getAttribute('data-artist_id');
    let cellphone = target.getAttribute('data-cellphone');
    let pet_seq = target.getAttribute('data-pet_seq');



    $.ajax({

        url:'/data/api.php',
        type:'post',
        data:{
            mode:'get_allimi_history',
            artist_id:artist_id,
            cellphone:cellphone,
            pet_seq:pet_seq

        },
        success:function(res) {
            console.log(res)
            let response = JSON.parse(res);
            let head = response.data.head;
            let body = response.data.body;
            if (head.code === 401) {
                pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
            } else if (head.code === 200) {


                if(body.length === undefined){

                    body = [body];
                }
                console.log(body)
                if(body.length > 0){
                    document.getElementById('allimi_history_select').style.display = 'flex';
                    document.getElementById('allimi_history_select').innerHTML =`<option data-pet_seq="0" data-artist_id="${artist_id}" data-cellphone="${cellphone}">전체보기</option>`

                    document.getElementById('allimi_history_select').dispatchEvent(new Event('change'));
                    let pet_list = [];
                    body.forEach(function(el){


                        if(pet_list.includes(`${el.pet_seq}`)){

                            return;
                        }

                        pet_list.push(`${el.pet_seq}`);


                        document.getElementById('allimi_history_select').innerHTML += `<option data-cellphone="${cellphone}" data-artist_id="${artist_id}" data-pet_seq="${el.pet_seq}" >${el.name}</option>`
                    })
                }else{
                    document.getElementById('allimi_history_select').style.display = 'none';

                }
            }
        }


    })

    // document.getElementById('allimi-right-title').innerText = '알리미 발송이력';
    // document.getElementById('allimi_history').style.display = 'flex';
    //
    // document.getElementById('allimi_defalut').style.opacity = '0';
    // document.getElementById('allimi_preview').style.opacity = '0';
    // document.getElementById('allimi_gallery').style.opacity = '0';
    //
    //
    //
    //
    // setTimeout(function(){
    //     document.getElementById('allimi_history').style.opacity = '1';
    //     document.getElementById('allimi_defalut').style.display ='none';
    //     document.getElementById('allimi_preview').style.display ='none';
    //     document.getElementById('allimi_gallery').style.display ='none';
    //
    //
    // },200)




}

function allimi_history_change(target){


    let artist_id = target.options[target.selectedIndex].getAttribute('data-artist_id');
    let pet_seq = target.options[target.selectedIndex].getAttribute('data-pet_seq');
    let cellphone = target.options[target.selectedIndex].getAttribute('data-cellphone');

    $.ajax({

        url:'/data/api.php',
        type:'post',
        data:{
            mode:'get_allimi_history',
            artist_id:artist_id,
            cellphone:cellphone,
            pet_seq:pet_seq,
        },
        success:function(res) {
            let response = JSON.parse(res);
            let head = response.data.head;
            let body = response.data.body;
            if (head.code === 401) {
                pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
            } else if (head.code === 200) {
                console.log(body)
                if(body.length === undefined){
                    body = [body];
                }

                let week = ['일','월','화','수','목','금','토'];
                if(body.length> 0){

                    document.getElementById('allimi_history_list').innerHTML = ''
                    body.forEach(function(el,i){


                        let date = new Date(`${el.beauty_date.substr(0,4)}`,`${el.beauty_date.substr(4,2)}`,`${el.beauty_date.substr(6,2)}`);


                        document.getElementById('allimi_history_list').innerHTML += ` <div class="allimi-history-cell-wrap" style="cursor: pointer; ${i === body.length-1 ? 'margin-bottom:50px': ''}">
                                                                                                    <div class="allimi-history-img-wrap">
                                                                                                        <img src="${el.main_photo !== '' ? img_link_change(el.main_photo) : '/static/pub/images/icon/icon-pup-select-off.png'}">    
                                                                                                    </div>
                                                                                                    <div class="allimi-history-info-wrap" data-payment_log="${el.payment_log_seq}" onclick="allimi_get_history_one(${el.payment_log_seq},'${artist_id}')">
                                                                                                        <strong class="allimi-history-date">${date.getFullYear()}년 ${fill_zero(date.getMonth())}월 ${fill_zero(date.getDate())}일(${week[date.getDay()]})</strong>
                                                                                                        <span class="allimi-history-name">${el.name}</span>
                                                                                                    </div>
                                                                                                </div>`

                    })
                }
            }
        }

    })

}

function img_link_change(img){
    var img 	= img.replace("/pet/images", "/images");
    var img 	= img.replace("/pet/upload", "/upload");

    return "https://image.banjjakpet.com"+img;
}

function allimi_open_preview(target,id,bool){

    let artist_id = '';
    if(target === ''){

        artist_id = id;

    }else{
        artist_id = target.getAttribute('data-artist_id');
    }



    $.ajax({
        url:'/data/api.php',
        type:'post',
        data:{
            mode:'get_shop_info_2',
            artist_id:artist_id,
        },
        success:function(res) {
            let response = JSON.parse(res);
            let head = response.data.head;
            let body = response.data.body;
            if (head.code === 401) {
                pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
            } else if (head.code === 200) {
                document.getElementById('allimi_preview_shop_title').innerText = body.shop_name;
                document.getElementById('allimi_preview_shop_phone').innerText = body.phone;
                document.getElementById('allimi_preview_shop_address').innerText = body.address.split('|')[1];
                //
                // var view_address = body.address.split('|')[1];
                // var lat = '';
                // var lng = '';
                // var mapContainer = document.getElementById('allimi_preview_shop_map'); // 지도를 표시할 div
                // var mapOption = {
                //     center: new daum.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
                //     level: 3 // 지도의 확대 레벨
                // };
                //
                //     // 지도를 생성합니다
                //     var map = new daum.maps.Map(mapContainer, mapOption);
                //
                //     // 주소-좌표 변환 객체를 생성합니다
                //     var geocoder = new daum.maps.services.Geocoder();
                //
                //     var coords = new daum.maps.LatLng(lat, lng);
                //
                //     // 결과값으로 받은 위치를 마커로 표시합니다
                //     var marker = new daum.maps.Marker({
                //         map: map,
                //         position: coords
                //     });
                //     // 인포윈도우로 장소에 대한 설명을 표시합니다
                //     var infowindow = new daum.maps.InfoWindow({
                //         content: '<div style="width: 300px;text-align:center;padding:6px 0;">'+view_address+'</div>'
                //     });
                //     infowindow.open(map, marker);
                //
                //     // 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
                //     map.setCenter(coords);



            }
        }
    })
    if(bool){


        if(document.querySelector('input[name="allimi-pet"]:checked') === null){

            document.getElementById('msg1_txt').innerText = '이용펫을 선택해주세요.';
            pop.open('reserveAcceptMsg1');
            return;
        }

    }
    pop.open('allimi_preview');

    let attitude = document.querySelector('input[name="attitude"]:checked') === null ? '' : document.querySelector('input[name="attitude"]:checked').value
    let tangle = document.querySelectorAll('input[name="tangle"]:checked') === null ? '' : document.querySelectorAll('input[name="tangle"]:checked');
    let bath = document.querySelector('input[name="bath"]:checked') === null ? '' :document.querySelector('input[name="bath"]:checked').value
    let skin = document.querySelectorAll('input[name="skin"]:checked') === null ? '' :document.querySelectorAll('input[name="skin"]:checked');
    let condition =document.querySelector('input[name="condition"]:checked') === null ? '' : document.querySelector('input[name="condition"]:checked').value
    let dislike = document.querySelectorAll('input[name="dislike"]:checked') === null ? '' :document.querySelectorAll('input[name="dislike"]:checked');
    let self = document.querySelectorAll('input[name="self"]:checked') === null ? '' : document.querySelectorAll('input[name="self"]:checked');


    console.log(tangle);
    console.log(skin);
    console.log(dislike);




    let attitude_text, tangle_text,bath_text,skin_text,condition_text,dislike_text,self_text;

    document.getElementById('allimi_preview_attitude_wrap').style.display ='flex';
    document.getElementById('allimi_preview_tangle_wrap').style.display ='flex';
    document.getElementById('allimi_preview_bath_wrap').style.display ='flex';
    document.getElementById('allimi_preview_skin_wrap').style.display ='flex';
    document.getElementById('allimi_preview_condition_wrap').style.display ='flex';
    document.getElementById('allimi_preview_dislike_wrap').style.display ='flex';
    document.getElementById('allimi_preview_self_wrap').style.display ='flex';

    document.getElementById('allimi_preview_gallery').style.display = 'block';
    tangle_text ='';
    skin_text = '';
    dislike_text = '';
    self_text = '';
    switch(attitude){
        case '1' : attitude_text = '아주 잘 했어요. 칭찬해 주세요.'; break;
        case '2' : attitude_text = '좋아요.'; break;
        case '3' : attitude_text = '힘들어해요.'; break;
        case '0' : attitude_text =  document.getElementById('allimi_attitude_textarea').value; break;
        default : attitude_text = ''; document.getElementById('allimi_preview_attitude_wrap').style.display = 'none'; break;
    }

    switch(bath){
        case '1' : bath_text = '잘해요.'; break;
        case '2' : bath_text = '조금 싫어해요.'; break;
        case '3' : bath_text = '거부감이 있어요.'; break;
        case '0' : bath_text =  document.getElementById('allimi_bath_textarea').value; break;
        default : bath_text = ''; document.getElementById('allimi_preview_bath_wrap').style.display = 'none';  break;
    }

    switch(condition){
        case '1' : condition_text = '좋아요.'; break;
        case '2' : condition_text = '긴장했어요.'; break;
        case '3' : condition_text = '피곤해 해요.'; break;
        case '0' : condition_text =  document.getElementById('allimi_condition_textarea').value; break;
        default : condition_text = ''; document.getElementById('allimi_preview_condition_wrap').style.display ='none'; break;
    }

    console.log(tangle);
    let tangle_values = [];
    let skin_values = [];
    let dislike_values = [];
    let self_values = [];
    Array.from(tangle).forEach(function(el){

        tangle_values.push(el.value);
    })
    Array.from(skin).forEach(function(el){

        skin_values.push(el.value);
    })
    Array.from(dislike).forEach(function(el){

        dislike_values.push(el.value);
    })
    Array.from(self).forEach(function(el){

        self_values.push(el.value);
    })


    tangle_values.forEach(function(el){
        switch (el){
            case '1': tangle_text += '없어요. '; break;
            case '2': tangle_text += '얼굴 '; break;
            case '3': tangle_text += '귀 '; break;
            case '4': tangle_text += '겨드랑이 '; break;
            case '5': tangle_text += '다리 '; break;
            case '6': tangle_text += '꼬리 '; break;
            case '0': tangle_text += document.getElementById('allimi_tangle_textarea').value; break;
        }
    })

    skin_values.forEach(function(el){
        switch (el){
            case '1': skin_text += '깨끗해요. '; break;
            case '2': skin_text += '피부염 '; break;
            case '3': skin_text += '각질 '; break;
            case '4': skin_text += '붉은기 '; break;
            case '5': skin_text += '습진 '; break;
            case '6': skin_text += '농피증 '; break;
            case '7': skin_text += '알로페시아 '; break;
            case '0': skin_text += document.getElementById('allimi_skin_textarea').value; break;
        }
    })

    dislike_values.forEach(function(el){
        switch (el){
            case '1': dislike_text += '얼굴 '; break;
            case '2': dislike_text += '귀 '; break;
            case '3': dislike_text += '앞발 '; break;
            case '4': dislike_text += '뒷발 '; break;
            case '5': dislike_text += '발톱 '; break;
            case '6': dislike_text += '꼬리 '; break;
            case '0': dislike_text += document.getElementById('allimi_dislike_textarea').value; break;

        }
    })

    self_values.forEach(function(el){
        switch (el){
            case '1': self_text += '피부 자극으로 긁거나 핥을 수 있으니 주의해주세요. \n'; break;
            case '2': self_text += '스트레스로 인하여 식욕 부진, 구토 및 설사 증상을 보일 수 있습니다. \n'; break;
            case '3': self_text += '항문을 끌고 다니거나 꼬리를 감추는 증상을 보일 수 있습니다. \n'; break;
            case '4': self_text += '이중모(포메,스피츠 등)의 경우 미용 후 알로페시아(클리퍼 증후군) 현상이 나타날 수 있습니다. \n'; break;
            case '0': self_text += document.getElementById('allimi_self_textarea').value; break;
        }
    })

    console.log(tangle_values);
    console.log(tangle_text);

    if(tangle_text === ''){

        document.getElementById('allimi_preview_tangle_wrap').style.display = 'none';

    }

    if(skin_text === ''){

        document.getElementById('allimi_preview_skin_wrap').style.display = 'none';

    }
    if(dislike_text === ''){

        document.getElementById('allimi_preview_dislike_wrap').style.display = 'none';

    }

    if(self_text === ''){

        document.getElementById('allimi_preview_self_wrap').style.display = 'none';

    }

    if(attitude_text === '' && tangle_text === '' && bath_text ==='' && skin_text === '' && condition_text === '' && dislike_text === '' && self_text === ''){

        document.getElementById('allimi_preview_none').style.display = 'flex';

    }else{

        document.getElementById('allimi_preview_none').style.display = 'none';
    }



    document.getElementById('allimi_preview_attitude').innerText = attitude_text;
    document.getElementById('allimi_preview_tangle').innerText = tangle_text;
    document.getElementById('allimi_preview_bath').innerText = bath_text;
    document.getElementById('allimi_preview_skin').innerText = skin_text;
    document.getElementById('allimi_preview_condition').innerText = condition_text;
    document.getElementById('allimi_preview_dislike').innerText = dislike_text;
    document.getElementById('allimi_preview_self').innerText = self_text;


    // document.getElementById('allimi-right-title').innerText = '알리미 미리보기';





    let preview_photos = [];

    Array.from(document.getElementsByClassName('allimi-gallery-cell')).forEach(function(el){

        if(!el.classList.contains('allimi-gallery-cell-icon')){
            preview_photos.push(el.children[1].getAttribute('src'));
        }
    })


    console.log(preview_photos);

    if(preview_photos.length === 0){
        document.getElementById('allimi_preview_gallery').style.display ='none';
    }

    document.getElementById('allimi-preview-swiper').innerHTML = '';

    if(document.getElementById('allimi_date_select')){

        document.getElementById('allimi_preview_date').innerText = document.getElementById('allimi_date_select').options[document.getElementById('allimi_date_select').selectedIndex].text;
    }else{

        document.getElementById('allimi_preview_date').innerText = document.getElementById('allimi_date').innerText;

    }

    if(bool){


        if(document.querySelectorAll('input[name="allimi-pet"]:checked').length>1){

            let pet_name_list = [];

            Array.from(document.querySelectorAll('input[name="allimi-pet"]:checked')).forEach(function(el){

                pet_name_list.push(el.getAttribute('data-pet_name'));
            })
            document.getElementById('allimi_preview_name').innerText = '';

            pet_name_list.forEach(function(el){

                document.getElementById('allimi_preview_name').innerText += `${el} `;
            })


        }else{

            document.getElementById('allimi_preview_name').innerText = document.querySelector('input[name="allimi-pet"]:checked').getAttribute('data-pet_name');
        }
    }else{


    }

    preview_photos.forEach(function(el){

        document.getElementById('allimi-preview-swiper').innerHTML += `<div class="swiper-slide allimi-slide">
                                                                                            <img src="${el}" alt="" />
                                                                                    </div>`
    })








}

function allimi_get_gallerys(id){


    document.getElementById('allimi_gallery_list').innerHTML = '';

    let pets = document.querySelectorAll('input[name="allimi-pet"]:checked') === null ? '' : document.querySelectorAll('input[name="allimi-pet"]:checked');

    if(pets === ''){

        document.getElementById('msg1_txt').innerText = '이용펫을 선택해주세요.'
        pop.open('reserveAccpetMsg1');
        return;

    }else{

        let pet_list = [];

        Array.from(pets).forEach(function(el){

            pet_list.push(el.getAttribute('data-pet_seq'));
        });

        pet_list.forEach(function(el){

            $.ajax({

                url:'/data/api.php',
                type:'post',
                data:{
                    mode:'beauty_gal_get',
                    idx:el,
                    artist_id:id,
                },
                success:function(res) {
                    let response = JSON.parse(res);
                    let head = response.data.head;
                    let body = response.data.body;
                    if (head.code === 401) {
                        pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
                    } else if (head.code === 200) {
                        console.log(body);

                        if(body.length === undefined){
                            body = [body];
                        }

                        if(body.length>0){
                            body.forEach(function(el){

                                document.getElementById('allimi_gallery_list').innerHTML += `<div class="allimi-gallery-list-cell list-cell">
                                                                                                <label>
                                                                                                    <div class="allimi-picture-thumb-view">
                                                                                                        <div class="allimi-picture-obj" onclick=""><img src="${img_link_change(el.file_path)}" alt=""></div>
                                                                                                        <div class="allimi-picture-date">${el.upload_dt.substr(0,4)}.${el.upload_dt.substr(4,2)}.${el.upload_dt.substr(6,2)}</div>
                                                                                                        <div class="allimi-picture-ui">
                                                                                                           <input type="checkbox" name="allimi-gallery-select" class="allimi-picture-select" data-file_path="${el.file_path}">
                                                                                                           <em></em>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </label>
                                                                                            </div>`
                            })

                        }

                    }
                }
            })
        })


    }

}
function allimi_get_gallery(target,id){

    let payment_idx = target.getAttribute('data-payment_idx');
    let pet_seq = target.getAttribute('data-pet_seq');
    let cellphone = target.getAttribute('data-cellphone');
    let pet_name = target.getAttribute('data-pet_name');

    document.getElementById('allimi_gallery_list').innerHTML = `<div class="allimi-gallery-list-cell"><a href="#" class="btn-gate-picture-register" onclick="file_view()"><span><em>이미지 추가</em></span></a></div>`

    document.getElementById('allimi_imgupfile_wrap').innerHTML = '';
    document.getElementById('allimi_imgupfile_wrap').innerHTML = `<input type="file" accept="image/*" name="allimi_imgupfile" id="allimi_addimgfile">`
    document.getElementById('allimi_open_gallery').setAttribute('data-payment_idx',payment_idx);
    document.getElementById('allimi_open_gallery').setAttribute('data-pet_seq',pet_seq);
    document.getElementById('allimi_open_gallery').setAttribute('data-cellphone',cellphone);
    document.getElementById('allimi_open_gallery').setAttribute('data-pet_name',pet_name);



    console.log(id)

    $.ajax({

        url:'/data/api.php',
        type:'post',
        data:{
            mode:'beauty_gal_get',
            idx:pet_seq,
            artist_id:id,
        },
        success:function(res) {
            let response = JSON.parse(res);
            let head = response.data.head;
            let body = response.data.body;
            if (head.code === 401) {
                pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
            } else if (head.code === 200) {
                console.log(body);

                if(body.length === undefined){
                    body = [body];
                }

                if(body.length>0){
                    body.forEach(function(el){

                        document.getElementById('allimi_gallery_list').innerHTML += `<div class="allimi-gallery-list-cell list-cell">
                                                                                                <label>
                                                                                                    <div class="allimi-picture-thumb-view">
                                                                                                        <div class="allimi-picture-obj" onclick=""><img src="${img_link_change(el.file_path)}" alt=""></div>
                                                                                                        <div class="allimi-picture-date">${el.upload_dt.substr(0,4)}.${el.upload_dt.substr(4,2)}.${el.upload_dt.substr(6,2)}</div>
                                                                                                        <div class="allimi-picture-ui">
                                                                                                           <input type="checkbox" name="allimi-gallery-select" class="allimi-picture-select" data-file_path="${el.file_path}">
                                                                                                           <em></em>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </label>
                                                                                            </div>`
                    })

                }

            }
        }
    })

    allimi_beauty_gallery_add(id,pet_seq,payment_idx);

}

function allimi_select_photo(target,bool){


    let payment_idx = target.getAttribute('data-payment_idx');
    let cellphone = target.getAttribute('data-cellphone');
    let pet_name = target.getAttribute('data-pet_name');
    let pet_seq = target.getAttribute('data-pet_seq');


    let elements = document.querySelectorAll('input[name="allimi-gallery-select"]:checked');

    let photos = [];

    Array.from(elements).forEach(function(el){

        photos.push(el.getAttribute('data-file_path'));
    })

    console.log(photos);


    if(bool){

        document.getElementById('allimi_gallery_wrap').innerHTML = `<div class="allimi-gallery-cell allimi-gallery-cell-icon" id="allimi_open_gallery" style="cursor:pointer;" onclick="pop.open('allimi_gallery')">
                                                                        <img src="/static/pub/images/icon/photo_icon.png" alt="">
                                                                        <span class="allimi-gallery-span">사진첨부</span>
                                                                    </div>`
    }else{
        // document.getElementById('allimi_gallery_wrap').innerHTML = `<div class="allimi-gallery-cell allimi-gallery-cell-icon" id="allimi_open_gallery" style="cursor:pointer;" onclick="allimi_select_pet_pop()">
        //                                                                 <img src="/static/pub/images/icon/photo_icon.png" alt="">
        //                                                                 <span class="allimi-gallery-span">사진첨부</span>
        //                                                             </div>`

    }



    if(photos.length >0){

        photos.forEach(function(el){

            document.getElementById('allimi_gallery_wrap').innerHTML += `<div class="allimi-gallery-cell" data-file_path="${el}" >
                                                                                        <div class="allimi-gallery-cell-delete" onclick="allimi_delete_photo(this)">
                                                                                            <img src="/static/pub/images/icon/10-ic-24-close-white@2x.png" alt=""> 
                                                                                        </div>
                                                                                        <img src="${img_link_change(el)}" alt="">
                                                                                    </div>`


        })
    }


    pop.close2('allimi_gallery');





}

function allimi_delete_photo(target){

    target.parentElement.remove();
}

function allimi_beauty_gallery_add(id,pet_seq,payment_idx){


    console.log(id);
    console.log(pet_seq);
    console.log(payment_idx);
    if(payment_idx === null){

        payment_idx = 0;
    }

    if(document.getElementById('allimi_addimgfile').classList.contains('event')){

        return;
    }

    document.getElementById('allimi_addimgfile').classList.add('event');

    document.getElementById('allimi_addimgfile').addEventListener('change',function(e){

        let ext = document.getElementById('allimi_addimgfile').value.split('.').pop().toLowerCase()

        if(!ext.match(/png|jpg|jpeg/i)){

            alert('gif,png,jpg,jpeg 파일만 업로드 할 수 있습니다.')
            return;
        }

        let filename = document.querySelector('input[name="allimi_imgupfile"]').files[0]


        let type = filename.type.split('/')[1];

        let formData = new FormData();
        formData.append('mode','beauty_gal_add');
        formData.append('login_id',id);
        formData.append('payment_log_seq',payment_idx);
        formData.append('pet_seq',pet_seq);
        formData.append('prnt_title',filename.name.split('.')[0])
        formData.append('mime',type);
        formData.append('image',filename);




        $.ajax({

            url:'/data/api.php',
            type:'post',
            enctype:'multipart/form-data',
            data:formData,
            processData:false,
            contentType:false,
            success:function(res) {
                console.log(res)
                let response = JSON.parse(res);
                let head = response.data.head;
                let body = response.data.body;
                if (head.code === 401) {
                    pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
                } else if (head.code === 200) {
                    console.log(body);
                    if(body.err == 0){
                        allimi_get_gallery2(id);

                    }
                }
            }






        })



    })

}
function allimi_MemofocusNcursor() {
    html = "<div id='upimgarea'></div>";
    //document.getElementById('dmemo').focus();
    var sel, range;
    if (window.getSelection) {
        // IE9 and non-IE
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();

            // Range.createContextualFragment() would be useful here but is
            // non-standard and not supported in all browsers (IE9, for one)
            var el = document.createElement("div");
            el.innerHTML = html;
            var frag = document.createDocumentFragment(),
                node, lastNode;
            while ((node = el.firstChild)) {
                lastNode = frag.appendChild(node);
            }
            range.insertNode(frag);

            // Preserve the selection
            if (lastNode) {
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    } else if (document.selection && document.selection.type != "Control") {
        // IE < 9
        document.selection.createRange().pasteHTML(html);
    }

    $("#allimi_addimgfile").trigger("click");

}

function allimi_get_gallery2(id){

    let target = document.getElementById('allimi_open_gallery');

    let payment_idx = target.getAttribute('data-payment_idx');
    let pet_seq = target.getAttribute('data-pet_seq');
    let cellphone = target.getAttribute('data-cellphone');
    let pet_name = target.getAttribute('data-pet_name');

    document.getElementById('allimi_gallery_list').innerHTML = `<div class="allimi-gallery-list-cell"><a href="#" class="btn-gate-picture-register" onclick="file_view()"><span><em>이미지 추가</em></span></a></div>`



    $.ajax({

        url:'/data/api.php',
        type:'post',
        data:{
            mode:'beauty_gal_get',
            idx:pet_seq,
            artist_id:id,
        },
        success:function(res) {
            let response = JSON.parse(res);
            let head = response.data.head;
            let body = response.data.body;
            if (head.code === 401) {
                pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
            } else if (head.code === 200) {
                console.log(body);

                if(body.length === undefined){
                    body = [body];
                }
                if(body.length >0 ){
                    body.forEach(function(el){

                        document.getElementById('allimi_gallery_list').innerHTML += `<div class="allimi-gallery-list-cell list-cell">
                                                                                                <label>
                                                                                                    <div class="allimi-picture-thumb-view">
                                                                                                        <div class="allimi-picture-obj" onclick=""><img src="${img_link_change(el.file_path)}" alt=""></div>
                                                                                                        <div class="allimi-picture-date">${el.upload_dt.substr(0,4)}.${el.upload_dt.substr(4,2)}.${el.upload_dt.substr(6,2)}</div>
                                                                                                        <div class="allimi-picture-ui">
                                                                                                           <input type="checkbox" name="allimi-gallery-select" class="allimi-picture-select" data-file_path="${el.file_path}">
                                                                                                           <em></em>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </label>
                                                                                            </div>`
                    })

                }

            }
        }
    })
}


function allimi_send(shop_name){

    if(document.querySelector('input[name="allimi-pet"]:checked')===null){

        document.getElementById('msg1_txt').innerText = '이용펫을 선택해주세요.'
        pop.open('reserveAcceptMsg1');
        return;
    }


    let selected = document.querySelectorAll('input[name="allimi-pet"]:checked');


    Array.from(selected).forEach(function(el,i){




        let payment_log_seq = el.getAttribute('data-payment_idx');
        let artist_id = el.getAttribute('data-artist_id');
        let pet_seq = el.getAttribute('data-pet_seq');
        let cellphone = el.getAttribute('data-cellphone');
        let pet_name = el.getAttribute('data-pet_name');

        let pet_name_owner = '';
        $.ajax({
            url:'/data/api.php',
            type:'post',
            async:false,
            data:{
                mode:'pet_info',
                pet_seq:pet_seq,
            },
            success:function(res) {
                let response = JSON.parse(res);
                let head = response.data.head;
                let body = response.data.body;
                if (head.code === 401) {
                    pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
                } else if (head.code === 200) {
                    console.log(body)
                    if(body.length === undefined){
                        body = [body];
                    }

                    if(body.length>0){

                        pet_name_owner = body[0].name_for_owner;
                    }
                }
            }
        })

        let etiquette = document.querySelector('input[name="attitude"]:checked') === null ? '' : document.querySelector('input[name="attitude"]:checked');
        let etiquette_1 = etiquette.value == '1' ? '1':'0';
        let etiquette_2 = etiquette.value == '2' ? '1':'0';
        let etiquette_3 = etiquette.value == '3' ? '1':'0';
        let etiquette_etc = etiquette.value == '0' ? '1':'0';
        let etiquette_etc_memo = etiquette.value == '0' ? document.getElementById('allimi_attitude_textarea').value : '';

        let condition = document.querySelector('input[name="condition"]:checked') === null ? '' :document.querySelector('input[name="condition"]:checked');
        let condition_1 = condition.value == '1' ? '1':'0';
        let condition_2 = condition.value == '2' ? '1':'0';
        let condition_3 = condition.value == '3' ? '1':'0';
        let condition_etc = condition.value == '0' ? '1':'0';
        let condition_etc_memo = condition.value == '0' ? document.getElementById('allimi_condition_textarea').value : '';

        let tangles = document.querySelectorAll('input[name="tangle"]:checked').length === 0 ? '' : document.querySelectorAll('input[name="tangle"]:checked') ;


        let tangles_arr = [];
        if(tangles !== ''){

            tangles.forEach(function(el){

                tangles_arr.push(el.value);

            })
        }

        let tangles_1 = tangles_arr.includes('1') ? '1' : '0';
        let tangles_2 = tangles_arr.includes('2') ? '1' : '0';
        let tangles_3 = tangles_arr.includes('3') ? '1' : '0';
        let tangles_4= tangles_arr.includes('4') ? '1' : '0';
        let tangles_5 = tangles_arr.includes('5') ? '1' : '0';
        let tangles_6 = tangles_arr.includes('6') ? '1' : '0';
        let tangles_7 = tangles_arr.includes('7') ? '1' : '0';
        let tangles_etc = tangles_arr.includes('0') ? '1' : '0';
        let tangles_etc_memo = tangles_arr.includes('0') ? document.getElementById('allimi_tangle_textarea').value : '';


        let part = document.querySelectorAll('input[name="dislike"]:checked').length === 0 ? '' : document.querySelectorAll('input[name="dislike"]:checked') ;

        let part_arr = [];

        if(part !== ''){

            part.forEach(function(el){

                part_arr.push(el.value);
            })
        }

        let part_1 = part_arr.includes('1') ? '1':'0';
        let part_2 = part_arr.includes('2') ? '1':'0';
        let part_3 = part_arr.includes('3') ? '1':'0';
        let part_4 = part_arr.includes('4') ? '1':'0';
        let part_5 = part_arr.includes('5') ? '1':'0';
        let part_6 = part_arr.includes('6') ? '1':'0';
        let part_etc = part_arr.includes('0') ? '1':'0';
        let part_etc_memo = part_arr.includes('0') ? document.getElementById('allimi_dislike_textarea').value :'';


        let skin = document.querySelectorAll('input[name="skin"]:checked').length === 0 ? '' : document.querySelectorAll('input[name="skin"]:checked') ;

        let skin_arr = [];
        if(skin !== ''){

            skin.forEach(function(el){
                skin_arr.push(el.value);
            })
        }

        let skin_1 = skin_arr.includes('1') ? '1' : '0';
        let skin_2 = skin_arr.includes('2') ? '1' : '0';
        let skin_3 = skin_arr.includes('3') ? '1' : '0';
        let skin_4 = skin_arr.includes('4') ? '1' : '0';
        let skin_5 = skin_arr.includes('5') ? '1' : '0';
        let skin_6 = skin_arr.includes('6') ? '1' : '0';
        let skin_7 = skin_arr.includes('7') ? '1' : '0';
        let skin_etc = skin_arr.includes('0') ? '1' : '0';
        let skin_etc_memo = skin_arr.includes('0') ? document.getElementById('allimi_skin_textarea').value : '';

        console.log(skin_etc_memo);

        let bath = document.querySelector('input[name="bath"]:checked') === null ? '' : document.querySelector('input[name="bath"]:checked');
        let bath_1 = bath.value == '1' ? '1':'0';
        let bath_2 = bath.value == '2' ? '1':'0';
        let bath_3 = bath.value == '3' ? '1':'0';
        let bath_etc = bath.value == '0' ? '1':'0';
        let bath_etc_memo = bath.value == '0' ? document.getElementById('allimi_bath_textarea').value :'';


        let notice = document.querySelectorAll('input[name="self"]:checked').length === 0 ? '' : document.querySelectorAll('input[name="self"]:checked') ;


        let notice_arr = [];
        if(notice !== ''){

            notice.forEach(function(el){
                notice_arr.push(el.value);
            })
        }

        let notice_1 = notice_arr.includes('1') ? '1':'0';
        let notice_2 = notice_arr.includes('2') ? '1':'0';
        let notice_3 = notice_arr.includes('3') ? '1':'0';
        let notice_4 = notice_arr.includes('4') ? '1':'0';
        let notice_etc = notice_arr.includes('0') ? '1':'0';
        let notice_etc_memo = notice_arr.includes('0') ? document.getElementById('allimi_self_textarea').value :'';

        let file_path = '';

        Array.from(document.getElementsByClassName('allimi-gallery-cell')).forEach(function(el,i){

            if(!el.classList.contains('allimi-gallery-cell-icon')){

                if(i === document.getElementsByClassName('allimi-gallery-cell').length-1){

                    file_path += `${el.getAttribute('data-file_path')}`
                }else{

                    file_path += `${el.getAttribute('data-file_path')}|`
                }

            }
        })

        console.log(file_path)

        // console.log(etiquette)
        // console.log(etiquette_1)
        // console.log(etiquette_2)
        // console.log(etiquette_3)
        // console.log(etiquette_etc)
        // console.log(etiquette_etc_memo)

        $.ajax({

            url:'/data/api.php',
            type:'post',
            data: {
                mode: 'post_allimi',
                payment_log_seq: payment_log_seq,
                artist_id: artist_id,
                pet_seq: pet_seq,
                cellphone:cellphone,
                etiquette_1: etiquette_1,
                etiquette_2: etiquette_2,
                etiquette_3: etiquette_3,
                etiquette_etc: etiquette_etc,
                etiquette_etc_memo: etiquette_etc_memo,
                condition_1: condition_1,
                condition_2: condition_2,
                condition_3: condition_3,
                condition_etc: condition_etc,
                condition_etc_memo: condition_etc_memo,
                tangles_1: tangles_1,
                tangles_2: tangles_2,
                tangles_3: tangles_3,
                tangles_4: tangles_4,
                tangles_5: tangles_5,
                tangles_6: tangles_6,
                tangles_7: tangles_7,
                tangles_etc: tangles_etc,
                tangles_etc_memo: tangles_etc_memo,
                part_1: part_1,
                part_2: part_2,
                part_3: part_3,
                part_4: part_4,
                part_5: part_5,
                part_6: part_6,
                part_etc: part_etc,
                part_etc_memo: part_etc_memo,
                skin_1: skin_1,
                skin_2: skin_2,
                skin_3: skin_3,
                skin_4: skin_4,
                skin_5: skin_5,
                skin_6: skin_6,
                skin_7: skin_7,
                skin_etc: skin_etc,
                skin_etc_memo: skin_etc_memo,
                bath_1: bath_1,
                bath_2: bath_2,
                bath_3: bath_3,
                bath_etc: bath_etc,
                bath_etc_memo: bath_etc_memo,
                notice_1: notice_1,
                notice_2: notice_2,
                notice_3: notice_3,
                notice_4: notice_4,
                notice_etc: notice_etc,
                notice_etc_memo: notice_etc_memo,
                file_path: file_path,
            },
            success:function(res) {
                console.log(res)
                let response = JSON.parse(res);
                let head = response.data.head;
                let body = response.data.body;
                if (head.code === 401) {
                    pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
                } else if (head.code === 200) {
                    console.log(body);
                    if(i === 0){

                        let message= '';
                        if(pet_name_owner === '' || pet_name_owner === undefined || pet_name_owner === null){
                            message = `${pet_name} 보호자님 안녕하세요.\n` +
                                `${shop_name}에서 ${pet_name}의 컨디션과 활동에 대한 알리미가 도착했어요.\n` +
                                '\n' +
                                '아래 알리미보기 버튼을 눌러 확인해보세요.'
                        }else{
                            message = `${pet_name_owner} 보호자님 안녕하세요.\n` +
                                `${shop_name}에서 ${pet_name_owner}의 컨디션과 활동에 대한 알리미가 도착했어요.\n` +
                                '\n' +
                                '아래 알리미보기 버튼을 눌러 확인해보세요.'
                        }


                        $.ajax({

                            url:'/data/api.php',
                            type:'post',
                            data:{

                                mode:'allimi_talk',
                                cellphone:cellphone,
                                message:message,
                                payment_log_seq:payment_log_seq,


                            },
                            success:function(res) {
                                console.log(res);
                                let response = JSON.parse(res);
                                let head = response.data.head;
                                let body = response.data.body;
                                if (head.code === 401) {
                                    pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
                                } else if (head.code === 200) {
                                    console.log(body);
                                }
                            }

                        })
                    }

                    document.getElementById('close_msg').innerText = '알리미가 전송되었습니다.';
                    pop.open('allimiClosePop');
                }
            }



        })


    })


}

function allimi_recent(id,cellphone){

    $.ajax({

        url:'/data/api.php',
        type:'post',
        data:{

            mode:'allimi_recent',
            artist_id:id,
            cellphone:cellphone,
        },
        success:function(res) {
            let response = JSON.parse(res);
            let head = response.data.head;
            let body = response.data.body;
            if (head.code === 401) {
                pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
            } else if (head.code === 200) {
                if(body.length === undefined){

                    body = [body];
                }
                console.log(body);
                if(body.length>0){


                    let year = body[0].diary_recent_time.substr(0,4);
                    let month = body[0].diary_recent_time.substr(4,2);
                    let date = body[0].diary_recent_time.substr(6,2);
                    let hour = body[0].diary_recent_time.substr(8,2);
                    let min = body[0].diary_recent_time.substr(10,2);

                    let recent_date = new Date(year,parseInt(month)-1,date,hour,min);
                    console.log(recent_date);
                    document.getElementById('diary_recent').innerText = `( 최근발송 : ${recent_date.getFullYear()}. ${fill_zero(recent_date.getMonth()+1)}. ${fill_zero(recent_date.getDate())} ${am_pm_check(recent_date.getHours())}시 ${fill_zero(recent_date.getMinutes())}분 )`
                }
            }
        }
    })

}

function allimi_select_pet_pop(){

    document.getElementById('allimi_select_pet_list').innerHTML ='';
    Array.from(document.querySelectorAll('input[name="allimi-pet"]')).forEach(function(el){


        document.getElementById('allimi_select_pet_list').innerHTML +=`<label class="form-toggle-box small">
                                                                                    <input type="radio" name="select_pet_btn" data-pet_name="${el.getAttribute('data-pet_name')}" data-artist_id="${el.getAttribute('data-artist_id')}" data-cellphone="${el.getAttribute('data-cellphone')}" data-pet_seq="${el.getAttribute('data-pet_seq')}">
                                                                                    <em class="font-size-12">${el.getAttribute('data-pet_name')}</em>
                                                                                </label>`;
    })



    pop.open('allimi_select_pet')
}

function allimi_select_pet_pop_confirm(id){


    let target = document.querySelector('input[name="select_pet_btn"]:checked') === null ? '' : document.querySelector('input[name="select_pet_btn"]:checked');

    if(target === ''){

        document.getElementById('msg1_txt').innerText = '펫을 선택해주세요.';
        pop.open('reserveAcceptMsg1');
        return;

    }


    console.log(target);
    allimi_get_gallery(target,id);
    pop.open('allimi_gallery');
    pop.close2('allimi_select_pet');

}

function allimi_get_history_one(payment_idx,id){


    $.ajax({
        url:'/data/api.php',
        type:'post',
        data:{
            mode:'get_allimi',
            payment_log_seq:payment_idx
        },
        success:function(res) {
            let response = JSON.parse(res);
            let head = response.data.head;
            let body = response.data.body;
            if (head.code === 401) {
                pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
            } else if (head.code === 200) {
                console.log(body);

                // document.getElementById('allimi-right-title').innerText = '알리미 미리보기';

                pop.open('allimi_preview');
                // document.getElementById('allimi_preview').style.display = 'flex';
                // document.getElementById('allimi_defalut').style.opacity = '0';
                // document.getElementById('allimi_history').style.opacity = '0';
                //
                //
                // document.getElementById('allimi_gallery').style.opacity = '0';
                //
                // setTimeout(function(){
                //     document.getElementById('allimi_preview').style.opacity = '1';
                //     document.getElementById('allimi_defalut').style.display ='none';
                //     document.getElementById('allimi_gallery').style.display ='none';
                //     document.getElementById('allimi_history').style.display ='none';
                //
                //
                // },200)

                document.getElementById('allimi_preview_name').innerText = body.pet_name;

                let beauty_date = new Date(body.beauty_date.substr(0,4),body.beauty_date.substr(4,2),body.beauty_date.substr(6,2));

                beauty_date.setMonth(beauty_date.getMonth() -1);
                let week = ['일','월','화','수','목','금','토'];

                document.getElementById('allimi_preview_date').innerText = `${beauty_date.getFullYear()}년 ${fill_zero(beauty_date.getMonth()+1)}월 ${fill_zero(beauty_date.getDate())}일(${week[beauty_date.getDay()]})`


                if(body.etiquette_1 === 0 && body.etiquette_2 === 0 && body.etiquette_3 === 0 && body.etiquette_etc === 0){

                    document.getElementById('allimi_preview_attitude_wrap').style.display = 'none';


                }

                if(body.tangles_1 === 0 && body.tangles_2 === 0 && body.tangles_3 === 0 && body.tangles_4 === 0 && body.tangles_5 === 0 &&body.tangles_6 === 0 && body.tangles_7 === 0  && body.tangles_etc === 0  ){

                    document.getElementById('allimi_preview_tangle_wrap').style.display = 'none';


                }

                if(body.bath_1 === 0 && body.bath_2 === 0 && body.bath_3 === 0 && body.bath_etc === 0){

                    document.getElementById('allimi_preview_bath_wrap').style.display = 'none';


                }


                if(body.skin_1 === 0 && body.skin_2 === 0 && body.skin_3 === 0 && body.skin_4 === 0 && body.skin_5 === 0 && body.skin_6 === 0 && body.skin_7 === 0 && body.skin_etc === 0){

                    document.getElementById('allimi_preview_skin_wrap').style.display = 'none';


                }

                if(body.condition_1 === 0 && body.condition_2 === 0 && body.condition_3 === 0 && body.condition_etc === 0){

                    document.getElementById('allimi_preview_condition_wrap').style.display = 'none';


                }



                if(body.part_1 === 0 && body.part_2 === 0 && body.part_3 === 0 && body.part_4 === 0 && body.part_5 === 0 && body.part_6 === 0 && body.part_etc === 0){

                    document.getElementById('allimi_preview_dislike_wrap').style.display = 'none';


                }

                if(body.notice_1 === 0 && body.notice_2 === 0 && body.notice_3 === 0 && body.notice_etc === 0){

                    document.getElementById('allimi_preview_self_wrap').style.display = 'none';


                }

                let none_count = 0;
                Array.from(document.getElementsByClassName('list-style-basic')).forEach(function(el){

                    if(el.style.display === 'none'){

                        none_count++;

                    }
                })



                if(none_count === 8){

                    document.getElementById('allimi_preview_none').style.display = 'flex';}


                document.getElementById('allimi_preview_shop_title').innerText = body.shop_name;
                document.getElementById('allimi_preview_shop_phone').innerText =body.shop_phone;
                document.getElementById('allimi_preview_shop_address').innerText =body.shop_address.split('|')[1];



                if(body.etiquette_1 === 1){
                    document.getElementById('allimi_preview_attitude').innerText= '아주 잘 했어요. 칭찬해 주세요.'
                }

                if(body.etiquette_2 === 1){
                    document.getElementById('allimi_preview_attitude').innerText= '잘해요.'
                }

                if(body.etiquette_3 === 1){
                    document.getElementById('allimi_preview_attitude').innerText= '힘들어해요.'
                }
                if(body.etiquette_etc === 1){
                    document.getElementById('allimi_preview_attitude').innerText= body.etiquette_etc_memo;
                }

                if(body.bath_1 === 1){
                    document.getElementById('allimi_preview_bath').innerText = '잘해요.';
                }

                if(body.bath_2 === 1){
                    document.getElementById('allimi_preview_bath').innerText = '조금 싫어해요.';
                }
                if(body.bath_3 === 1){
                    document.getElementById('allimi_preview_bath').innerText = '거부감이 있어요.';
                }
                if(body.bath_etc === 1){
                    document.getElementById('allimi_preview_bath').innerText = body.bath_etc_memo;
                }

                if(body.condition_1 ===1){
                    document.getElementById('allimi_preview_condition').innerText = '잘해요.';

                }
                if(body.condition_2 ===1){
                    document.getElementById('allimi_preview_condition').innerText = '조금 싫어해요.';

                }
                if(body.condition_3 ===1){
                    document.getElementById('allimi_preview_condition').innerText = '거부감이 있어요.';

                }
                if(body.condition_etc ===1){
                    document.getElementById('allimi_preview_condition').innerText = body.condition_etc_memo;

                }

                let tangles_text ='';

                if(body.tangles_1 === 1){

                    tangles_text += '없어요. '
                }

                if(body.tangles_2 === 1){

                    tangles_text += '얼굴 '
                }
                if(body.tangles_3 === 1){

                    tangles_text += '귀 '
                }
                if(body.tangles_4 === 1){

                    tangles_text += '겨드랑이 '
                }
                if(body.tangles_5 === 1){

                    tangles_text += '다리 '
                }
                if(body.tangles_6 === 1){

                    tangles_text += '꼬리 '
                }
                if(body.tangles_etc === 1){

                    tangles_text += body.tangles_etc_memo;
                }

                let skin_text = '';

                if(body.skin_1 === 1){

                    skin_text += '깨끗해요. ';

                }
                if(body.skin_2 === 1){

                    skin_text += '피부염 ';

                }
                if(body.skin_3 === 1){

                    skin_text += '각질 ';

                }
                if(body.skin_4 === 1){

                    skin_text += '붉은기 ';

                }
                if(body.skin_5 === 1){

                    skin_text += '습진 ';

                }
                if(body.skin_6 === 1){

                    skin_text += '농피증 ';

                }
                if(body.skin_7 === 1){

                    skin_text += '알로페시아 ';

                }
                if(body.skin_etc === 1){

                    skin_text += body.skin_etc_memo;

                }

                let part_text = '';

                if(body.part_1 === 1){

                    part_text += '얼굴 ';
                }

                if(body.part_2 === 1){

                    part_text += '귀 ';
                }
                if(body.part_3 === 1){

                    part_text += '앞발 ';
                }
                if(body.part_4 === 1){

                    part_text += '뒷발 ';
                }
                if(body.part_5 === 1){

                    part_text += '발톱 ';
                }
                if(body.part_6 === 1){

                    part_text += '꼬리 ';
                }
                if(body.part_etc === 1){

                    part_text += body.part_etc_memo;
                }

                let notice_text = '';

                if(body.notice_1 === 1){

                    notice_text += '피부 자극으로 긁거나 핥을 수 있으니 주의해주세요. \n';
                }

                if(body.notice_2 === 1){

                    notice_text += '스트레스로 인하여 식욕 부진, 구토 및 설사 증상을 보일 수 있습니다. \n';
                }
                if(body.notice_3 === 1){

                    notice_text += '항문을 끌고 다니거나 꼬리를 감추는 증상을 보일 수 있습니다. \n';
                }
                if(body.notice_4 === 1){

                    notice_text += '이중모(포메,스피츠 등)의 경우 미용 후 알로페시아(클리퍼 증후군) 현상이 나타날 수 있습니다. \n';
                }
                if(body.notice_etc === 1){

                    notice_text += body.notice_etc_memo;
                }

                document.getElementById('allimi_preview_tangle').innerText = tangles_text;
                document.getElementById('allimi_preview_skin').innerText = skin_text;
                document.getElementById('allimi_preview_dislike').innerText = part_text;
                document.getElementById('allimi_preview_self').innerText = notice_text;



                document.getElementById('allimi-preview-swiper').innerHTML = '';

                let file_path;
                if(body.file_path === ''){
                    document.getElementById('allimi_preview_gallery').style.display ='none';

                }else{
                    document.getElementById('allimi_preview_gallery').style.display = 'block';

                    file_path = body.file_path.split('|');
                    file_path.forEach(function(el){
                        document.getElementById('allimi-preview-swiper').innerHTML += `<div class="swiper-slide allimi-slide">
                                                                                            <img src="${img_link_change(el)}" alt="" />
                                                                                    </div>`
                    })
                }






            }
        }
    })
}