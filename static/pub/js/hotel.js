function am_pm_check(hours){

    if(hours > 12){
        hours = `오후 ${(hours-12).toString().length <2 ? '0' : ''}${hours-12}`
    }else if(hours === 12){
        hours = `오후 ${hours}`
    }else{
        hours = `오전 ${hours.toString().length <2 ? '0' : ''}${hours}`
    }

    return hours;
}

function fill_zero(time){

    if(time.toString().length < 2){

        time = `0${time}`
    }else{
        time = time;
    }

    return time;
}



function get_hotel_product(id){


    return new Promise(function(resolve){

        $.ajax({
            url:'/data/api.php',
            type:'post',
            data:{
                mode:'get_hotel_product',
                artist_id:id
            },
            success:function(res) {
                let response = JSON.parse(res);
                let head = response.data.head;
                let body = response.data.body;
                if (head.code === 401) {
                    pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
                } else if (head.code === 200) {
                    console.log(body)

                    if(location.href.match('set_hotel')){
                        if(body.cat.length === 0 && body.dog.length === 0){

                            if(document.getElementById('hotel_product_none_data')){

                                document.getElementById('hotel_product_none_data').style.display = 'flex';
                            }

                        }


                        if(body.dog.length === 0 ){

                            if(document.getElementById('hotel_dog_product_none_data')){

                                document.getElementById('hotel_dog_product_none_data').style.display = 'flex';


                            }

                        }else{
                            if(document.getElementById('hotel_dog_product_none_data')){


                                document.getElementById('hotel_dog_product_none_data').style.display = 'none';
                            }

                            let set = new Set();

                            if(document.getElementById('hotel_dog_colgroup') && document.getElementById('hotel_dog_room_list')){


                                body.dog.forEach(function(v){

                                    document.getElementById('hotel_dog_colgroup').innerHTML += `<col style="width:auto;">`
                                    document.getElementById('hotel_dog_room_list').innerHTML += `<th>${v.room_name}</th>`

                                    v.fee_list.forEach(function(v2){

                                        set.add(v2.kg);

                                    })

                                })
                            }

                            let i = 0;

                            if(document.getElementById('hotel_dog_product_tbody')){


                                set.forEach(function(s){

                                    i+=1;

                                    document.getElementById('hotel_dog_product_tbody').innerHTML += `<tr ><th id="hotel_dog_product_row_${i}">${s}</th></tr>`


                                })
                            }
                        }



                        if(body.cat.length === 0 ){

                            if(document.getElementById('hotel_cat_product_none_data')){

                                // document.getElementById('hotel_cat_product_none_data').style.display = 'flex';
                            }

                        }else{
                            if(document.getElementById('hotel_cat_product_none_data')){

                                document.getElementById('hotel_cat_product_none_data').style.display = 'none';
                            }

                            let set = new Set();


                            if(document.getElementById('hotel_cat_colgroup') && document.getElementById('hotel_cat_room_list')){


                                body.cat.forEach(function(v){

                                    document.getElementById('hotel_cat_colgroup').innerHTML += `<col style="width:auto;">`
                                    document.getElementById('hotel_cat_room_list').innerHTML += `<th>${v.room_name}</th>`

                                    v.fee_list.forEach(function(v2){

                                        set.add(v2.kg);

                                    })

                                })
                            }

                            let i = 0;

                            if(document.getElementById('hotel_cat_product_tbody')){

                                set.forEach(function(s){

                                    i+=1;

                                    document.getElementById('hotel_cat_product_tbody').innerHTML += `<tr ><th id="hotel_dog_product_row_${i}">${s}</th></tr>`


                                })
                            }
                        }

                    }else{


                    }



                    resolve(body);
                }
            }


        })

    })
}


function get_hotel_product_dog(data){

    let kg_list = new Set();

    if(data.dog.length > 0){

        data.dog.forEach(function(d){

            d.fee_list.forEach(function(f){

                kg_list.add(f.kg);

            })
        })
        Array.from(document.getElementById('hotel_dog_product_tbody').children).forEach(function(c){

            data.dog.forEach(function(d){

                let d_kg_list = [];

                d.fee_list.forEach(function(f){
                    d_kg_list.push(f.kg);
                })

                let diff = Array.from(kg_list).filter(x => !d_kg_list.includes(x));

                d.fee_list.forEach(function(f){
                    if(c.children[0].innerText === f.kg){
                        c.innerHTML += `<td>${f.normal_price/1000}</td>`
                    }
                })

                if(diff.includes(c.children[0].innerText)){
                    c.innerHTML += `<td></td>`
                }

            })
        })
        document.getElementById('dog_tab').setAttribute('data-exist',1);

    }else{

        document.getElementById('hotel_product_wrap').style.display='none';
        // document.getElementById('hotel_dog_product_none_data').style.display ='flex';
        document.getElementById('dog_tab').setAttribute('data-exist',0);


    }

}
function get_hotel_product_cat(data){

    let kg_list = new Set();

    if(data.cat.length> 0){


        data.cat.forEach(function(c){

            c.fee_list.forEach(function(f){

                kg_list.add(f.kg);

            })
        })
        Array.from(document.getElementById('hotel_cat_product_tbody').children).forEach(function(ch){

            data.cat.forEach(function(c){

                let c_kg_list = [];

                c.fee_list.forEach(function(f){
                    c_kg_list.push(f.kg);
                })

                let diff = Array.from(kg_list).filter(x => !c_kg_list.includes(x));

                c.fee_list.forEach(function(f){
                    if(ch.children[0].innerText === f.kg){
                        ch.innerHTML += `<td>${f.normal_price/1000}</td>`
                    }
                })

                if(diff.includes(ch.children[0].innerText)){
                    ch.innerHTML += `<td></td>`
                }

            })
        })
        document.getElementById('cat_tab').setAttribute('data-exist',1);
    }else{

        document.getElementById('cat_tab').setAttribute('data-exist',0);
    }

}

function get_hotel_info(id){

    $.ajax({
        url:'/data/api.php',
        type:'post',
        data:{
            mode:'get_hotel_info',
            artist_id:id,
        },
        success:function(res) {
            let response = JSON.parse(res);
            let head = response.data.head;
            let body = response.data.body;
            if (head.code === 401) {
                pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
            } else if (head.code === 200) {
                console.log(body)

                localStorage.setItem('h_seq',body.h_seq);

                if(location.href.match('set_hotel')){

                    if(parseInt(body.is_neutral) === 1){

                        if(parseInt(body.is_neutral_pay) === 1){

                            document.getElementById('hotel_info_neutral').style.display='block';
                            document.getElementById('hotel_info_neutral_price').innerText = body.neutral_price.toLocaleString();


                        }

                    }else{
                        document.getElementById('hotel_info_neutral').style.display='none';
                    }


                    if(parseInt(body.is_pickup) === 1){
                        document.getElementById('hotel_info_pickup_1').style.display ='block';
                    }else{
                        document.getElementById('hotel_info_pickup_2').style.display = 'block';
                    }


                    document.getElementById('hotel_info_check').innerText = `${am_pm_check(body.check_in)}:00 ~ ${am_pm_check(body.check_out)}:00`


                    document.getElementById('hotel_common_notice').value = body.hotel_info;

                    document.getElementById('hotel_common_notice').addEventListener('input',function(){

                        document.getElementById('hotel_common_notice_length').innerText = document.getElementById('hotel_common_notice').value.length;
                    })
                    document.getElementById('hotel_common_notice_length').innerText = body.hotel_info.length;


                    document.getElementById('hotel_product_delete_all').addEventListener('click',function(){

                        pop.open('hotelProductDeleteAll');
                    })
                }else{


                }

            }
        }
    })

}

function hotel_product_delete_all(id,target){

    let hp_seq = [];

    let type = target.getAttribute('data-type');
    if(type === 'dog'){


        $.ajax({

            url:'/data/api.php',
            type:'post',
            async:false,
            data:{
                mode:'get_hotel_product',
                artist_id:id,


            },success:function(res) {
                let response = JSON.parse(res);
                let head = response.data.head;
                let body = response.data.body;
                if (head.code === 401) {
                    pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
                } else if (head.code === 200) {
                    console.log(body)

                    body.dog.forEach(function(dog){

                        hp_seq.push(dog.hp_seq);

                    })
                }
            }


        })
    }else{

        $.ajax({

            url:'/data/api.php',
            type:'post',
            async:false,
            data:{
                mode:'get_hotel_product',
                artist_id:id,


            },success:function(res) {
                let response = JSON.parse(res);
                let head = response.data.head;
                let body = response.data.body;
                if (head.code === 401) {
                    pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
                } else if (head.code === 200) {
                    console.log(body)

                    body.cat.forEach(function(cat){

                        hp_seq.push(cat.hp_seq);

                    })
                }
            }


        })
    }


    if(hp_seq.length>0){

        hp_seq.forEach(function(seq){

            $.ajax({
                url:'/data/api.php',
                type:'post',
                data:{
                    mode:'delete_all_hotel_product',
                    artist_id:id,
                    hp_seq:seq,
                },
                success:function(res) {
                    console.log(res)
                    let response = JSON.parse(res);
                    let head = response.data.head;
                    let body = response.data.body;
                    if (head.code === 401) {
                        pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
                    } else if (head.code === 200) {

                        location.reload();
                    }
                }

            })


        })
    }


}
function get_hotel_coupon(id){


    $.ajax({

        url:'/data/api.php',
        type:'post',
        data:{
            mode:'get_coupon',
            artist_id:id,
            service_type:'H',
        },
        success:function(res) {
            let response = JSON.parse(res);
            let head = response.data.head;
            let body = response.data.body;
            if (head.code === 401) {
                pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
            } else if (head.code === 200) {
                if(body.length ===undefined){
                    body = [body];
                }

                if(body.length>0){


                    body.forEach(function(coupon){

                        if(coupon.type === 'C'){

                            document.getElementById('hotel_coupon_C_product_tbody').innerHTML += `<tr>
                                                                                                            <td>${coupon.name}</td>
                                                                                                            <td>${coupon.given}</td>
                                                                                                            <td>${coupon.price.toLocaleString()}</td>
                                                                                                        </tr>`
                        }else {
                            document.getElementById('hotel_coupon_F_product_tbody').innerHTML += `<tr>
                                                                                                            <td>${coupon.name}</td>
                                                                                                            <td>${coupon.price.toLocaleString()}</td>
                                                                                                            <td>${coupon.given.toLocaleString()}</td>
                                                                                                        </tr>`

                        }

                    })
                }
                console.log(body)


            }
        }

    })

}
function wide_tab_set_hotel(){
    let tab_cell = document.getElementById('wide-tab-inner').children;

    Array.from(tab_cell).forEach(function (el) {

        el.addEventListener('click', function () {
            if (!this.classList.contains('actived')) {


                Array.from(tab_cell).forEach(function (el) {
                    el.classList.remove('actived');
                })

                this.classList.add('actived');

                console.log(el.getAttribute('id'))
                if(el.getAttribute('id') === 'dog_tab'){


                    document.getElementById('hotel_product_delete_btn').setAttribute('data-type','dog');
                    if(el.getAttribute('data-exist') === '1'){
                        document.getElementById('hotel_product_wrap').style.display='block';
                        document.getElementById('hotel_cat_wrap').style.display = 'none';
                        document.getElementById('hotel_dog_wrap').style.display ='block';
                        document.getElementById('btn_accordion_menu').setAttribute('data-type','dog');
                        document.getElementById('hotel_dog_product_none_data').style.display ='none';
                        document.getElementById('hotel_cat_product_none_data').style.display ='none';

                    }else{
                        document.getElementById('hotel_product_wrap').style.display='none';
                        document.getElementById('hotel_cat_wrap').style.display = 'none';
                        document.getElementById('hotel_dog_wrap').style.display ='none';
                        document.getElementById('hotel_dog_product_none_data').style.display ='flex';
                        document.getElementById('hotel_cat_product_none_data').style.display ='none';
                    }


                }else if(el.getAttribute('id') === 'cat_tab'){

                    document.getElementById('hotel_product_delete_btn').setAttribute('data-type','cat');
                    if(el.getAttribute('data-exist') === '1'){

                        document.getElementById('hotel_product_wrap').style.display='block';
                        document.getElementById('hotel_dog_wrap').style.display ='none';
                        document.getElementById('hotel_cat_wrap').style.display = 'block';
                        document.getElementById('hotel_cat_product_none_data').style.display ='none';
                        document.getElementById('hotel_dog_product_none_data').style.display ='none';
                        document.getElementById('btn_accordion_menu').setAttribute('data-type','cat');
                    }else{
                        document.getElementById('hotel_product_wrap').style.display='none';
                        document.getElementById('hotel_dog_wrap').style.display ='none';
                        document.getElementById('hotel_cat_wrap').style.display = 'none';
                        document.getElementById('hotel_dog_product_none_data').style.display ='none';
                        document.getElementById('hotel_cat_product_none_data').style.display ='flex';


                    }


                }

            } else {
                return;
            }




        })
    })
}

function wide_tab_set_hotel_add(){
    let tab_cell = document.getElementById('wide-tab-inner').children;

    Array.from(tab_cell).forEach(function (el) {

        el.addEventListener('click', function () {
            if (!this.classList.contains('actived')) {


                Array.from(tab_cell).forEach(function (el) {
                    el.classList.remove('actived');
                })

                this.classList.add('actived');

                if(document.getElementById('dog_tab')){

                    if(el.getAttribute('id') === 'dog_tab'){

                        document.getElementById('hotel_cat_wrap').style.display = 'none';
                        document.getElementById('hotel_dog_wrap').style.display ='block';

                    }else if(el.getAttribute('id') === 'cat_tab'){
                        document.getElementById('hotel_cat_wrap').style.display = 'block';
                        document.getElementById('hotel_dog_wrap').style.display ='none';


                    }
                }else if(document.getElementById('hotel_dog_tab')){
                    if(el.getAttribute('id') === 'hotel_dog_tab'){

                        document.getElementById('hotel_add_cat').style.display = 'none';
                        document.getElementById('hotel_add_dog').style.display ='block';

                    }else if(el.getAttribute('id') === 'hotel_cat_tab'){
                        document.getElementById('hotel_add_cat').style.display = 'block';
                        document.getElementById('hotel_add_dog').style.display ='none';


                    }

                }


            } else {
                return;
            }




        })
    })
}

function add_get_hotel_info(id){



    $.ajax({

        url:'/data/api.php',
        type:'post',
        data:{

            mode:'get_hotel_info',
            artist_id:id,
        },
        success:function(res) {
            let response = JSON.parse(res);
            let head = response.data.head;
            let body = response.data.body;
            if (head.code === 401) {
                pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
            } else if (head.code === 200) {
                console.log(body)

                localStorage.setItem('h_seq',body.h_seq);

                switch(body.pet_type){

                    case 'dog' : document.getElementById('dog_switch_toggle').checked = true; break;
                    case 'cat' : document.getElementById('cat_switch_toggle').checked = true; break;
                    case 'both' : document.getElementById('dog_switch_toggle').checked = true; document.getElementById('cat_switch_toggle').checked = true; break;
                }





                if(body.is_image === '1'){
                    document.getElementById('hotel_room_photo_dog_y').click();

                }else{
                    document.getElementById('hotel_room_photo_dog_n').click();

                }


                if(body.is_coupon === '1'){

                    document.getElementById('hotel_coupon_y').click();
                }else{
                    document.getElementById('hotel_coupon_n').click();
                }

                if(body.is_flat ==='1'){
                    document.getElementById('hotel_flat_y').click();
                }else{
                    document.getElementById('hotel_flat_n').click();
                }

                if(body.is_neutral === '1'){
                    document.getElementById('hotel_neutral_y').click();
                }else{
                    document.getElementById('hotel_neutral_n').click();
                }

                if(body.is_neutral_pay === '1'){
                    document.getElementById('hotel_neutral_price_y').click();
                    document.getElementById('hotel_neutral_price_select').innerHTML += select_neutral_price();
                }else{
                    document.getElementById('hotel_neutral_price_n').click();
                    document.getElementById('hotel_neutral_price_select').innerHTML = select_neutral_price();
                }

                if(body.is_pickup === '1'){
                    document.getElementById('hotel_pick_up_y').click();
                }else{
                    document.getElementById('hotel_pick_up_n').click();

                }

                if(body.is_24hour === '1'){

                    document.getElementById('hotel_24hour_y').click();
                    document.getElementById('hotel_check_in_time').innerHTML = '';
                    document.getElementById('hotel_check_out_time').innerHTML = '';
                }else{
                    document.getElementById('hotel_24hour_n').click();
                    document.getElementById('hotel_check_in_time').innerHTML += select_24hour();
                    document.getElementById('hotel_check_out_time').innerHTML += select_24hour();
                }


                document.getElementById('hotel_common_notice').addEventListener('input',function(){

                    document.getElementById('hotel_common_notice_length').innerText = document.getElementById('hotel_common_notice').value.length;
                })
                document.getElementById('hotel_common_notice_length').innerText =0;

                setTimeout(function(){

                    if(document.getElementById('hotel_neutral_price_select').options.length >0 && body.is_neutral_pay === '1'){

                        for(let i=0; i<document.getElementById('hotel_neutral_price_select').options.length; i++){

                            if(parseInt(document.getElementById('hotel_neutral_price_select').options[i].value) === body.neutral_price){

                                document.getElementById('hotel_neutral_price_select').options[i].selected = true;
                            }

                        }
                    }


                    if(document.getElementById('hotel_check_in_time').options.length>0){

                        for(let i=0; i<document.getElementById('hotel_check_in_time').options.length; i++){




                            if(document.getElementById('hotel_check_in_time').options[i].value === body.check_in){
                                document.getElementById('hotel_check_in_time').options[i].selected = true;
                            }
                        }
                    }

                    if(document.getElementById('hotel_check_out_time').options.length>0){

                        for(let i=0; i<document.getElementById('hotel_check_out_time').options.length; i++){




                            if(document.getElementById('hotel_check_out_time').options[i].value === body.check_out){
                                document.getElementById('hotel_check_out_time').options[i].selected = true;
                            }
                        }
                    }

                },100)
            }
        }
    })
}

function change_hotel_grade_dog(id,target){





    // document.getElementById('hotel_grade_dog_tbody').innerHTML = '';
    //
    //
    // for(let i=0; i<target.value; i++){
    //
    //     document.getElementById('hotel_grade_dog_tbody').innerHTML +=
    //
    // }


    let tbody = document.getElementById('hotel_grade_dog_tbody');

    let weight_tr = document.getElementById('hotel_grade_dog_sep_weight');

    let peak_tr = document.getElementById('hotel_peak_dog_sep_weight');

    let photo_tr = document.getElementById('hotel_photo_dog_tr');
    let now_length = tbody.children.length;

    let select_length = parseInt(target.value);

    console.log(now_length);
    console.log(select_length);

    if(select_length > now_length){


        for(let i=0; i<select_length-now_length; i++){

            tbody.innerHTML += `<tr class="grade-dog-tr"><td class="no-padding">
                                                                <div class="form-table-select dog-room-name">
                                                                    <input type="text" oninput="typing_change(this)" placeholder="입력"/>
                                                                </div>
                                                            </td><td class="no-padding">
                                                                <div class="form-table-select">
                                                                    <input type="number" placeholder="입력"/>
                                                                </div>
                                                            </td></tr>`

            weight_tr.innerHTML += '<th class="grade-dog-sep-th"></th>'
            peak_tr.innerHTML += '<th class="peak-dog-sep-th"></th>'
            photo_tr.innerHTML += '<th class="photo-dog-sep-th"></th>'

            Array.from(document.getElementsByClassName('hotel-grade-dog-sep-tr')).forEach(function(el){


                el.innerHTML += `<td class="no-padding"><div class="form-table-select"><select class="price-check">${select_price()}</select></div></td>`

            })

            Array.from(document.getElementsByClassName('hotel-peak-dog-sep-tr')).forEach(function(el){

                el.innerHTML += `<td class="no-padding"><div class="form-table-select"><select class="price-check">${select_price()}</select></div></td>`
            })


            let count = 0;
            Array.from(document.getElementsByClassName('hotel-photo-dog-td')).forEach(function(el){

                count = el.getAttribute('id').split('_')[1];
            })
            document.getElementById('hotel_photo_dog_tbody_tr').innerHTML += `<td class="hotel-photo-dog-td" id="dog_${parseInt(count)+1}" data-photos=""><div class="upload-img-btn" data-rid="dog_${parseInt(count)+1}" onclick="HotelMemofocusNcursor(this)"><img src="/static/pub/images/icon/icon-picture-add.png" width="30%" style="max-width: 30px;"></div></td>`


        }




    }else if(now_length>select_length){

        for(let i=0; i<now_length-select_length;i++){

            $('.grade-dog-tr:last-child').remove();
            $('.grade-dog-sep-th:last-child').remove();
            $('.peak-dog-sep-th:last-child').remove();
            $('.photo-dog-sep-th:last-child').remove();


            Array.from(document.getElementsByClassName('hotel-grade-dog-sep-tr')).forEach(function(el){
                el.children[el.children.length-1].remove();
            })

            Array.from(document.getElementsByClassName('hotel-peak-dog-sep-tr')).forEach(function(el){
                el.children[el.children.length-1].remove();
            })
            document.getElementById('hotel_photo_dog_tbody_tr').children[document.getElementById('hotel_photo_dog_tbody_tr').children.length-1].remove();
        }


    }


    setTimeout(function(){



        Array.from(document.getElementsByClassName('weight-check')).forEach(function(el){

            if(el.getAttribute('data-weight') !== null || el.getAttribute('data-weight') !== '' || el.getAttribute('data-weight') !== undefined){

                for(let i=0; i<el.options.length; i++){

                    if(el.options[i].value === el.getAttribute('data-weight')){

                        el.options[i].selected = true;
                    }

                }
            }
        })

    },100)


}

function change_hotel_grade_cat(id,target){






    document.getElementById('hotel_grade_cat_tbody').innerHTML = '';


    for(let i=0; i<target.value; i++){

        document.getElementById('hotel_grade_cat_tbody').innerHTML += `<tr><td class="no-padding">
                                                                <div class="form-table-select">
                                                                    <input type="text" placeholder="입력"/>
                                                                </div>
                                                            </td><td class="no-padding">
                                                                <div class="form-table-select">
                                                                    <input type="text" placeholder="입력"/>
                                                                </div>
                                                            </td></tr>`

    }


}



function add_get_hotel_product(id){


    return new Promise(function(resolve){
        $.ajax({
            url:'/data/api.php',
            type:'post',
            data:{
                mode:'get_hotel_product',
                artist_id:id,
            },
            success:function(res) {
                let response = JSON.parse(res);
                let head = response.data.head;
                let body = response.data.body;
                if (head.code === 401) {
                    pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
                } else if (head.code === 200) {
                    console.log(body)

                    let dog_arr = [];
                    let cat_arr = [];


                    if(body.dog[0].is_peak === '1'){

                        document.getElementById('hotel_peak_dog_y').click();

                    }else{
                        document.getElementById('hotel_peak_dog_n').click();
                    }

                    if(body.dog[0].is_neutral === '1'){

                        document.getElementById('hotel_neutral_y').click();

                    }else{
                        document.getElementById('hotel_neutral_n').click();
                    }

                    if(body.dog[0].is_neutral_pay === '1'){

                        document.getElementById('hotel_neutral_price_y').click();

                    }else{
                        document.getElementById('hotel_neutral_price_n').click();
                    }



                    if(body.dog.length > 0){

                        hotel_extra_price_div = document.getElementById('hotel_extra_price');



                        hotel_extra_price_div.innerHTML += `${select_extra()}`


                        document.getElementById('hotel_common_notice_length').innerText =body.dog[0].comment.length;
                        document.getElementById('hotel_common_notice').value = body.dog[0].comment;
                        document.getElementById('hotel_common_notice').dispatchEvent(new Event('change'));


                        setTimeout(function(){

                            for(let i = 0; i<hotel_extra_price_div.options.length; i++){

                                if(parseInt(hotel_extra_price_div.options[i].value) === body.dog[0].extra_price){

                                    hotel_extra_price_div.options[i].selected = true;
                                }
                            }
                        },100)


                        body.dog.forEach(function(d,i){


                            d.fee_list.forEach(function(fee){

                                let item = {hp_seq:d.hp_seq,name:d.room_name,kg:fee.kg,normal_price:fee.normal_price,peak_price:fee.peak_price};
                                dog_arr.push(item);

                            })


                            document.getElementById('hotel_grade_dog_tbody').innerHTML += `<tr class="grade-dog-tr"><td class="no-padding">
                                                                <div class="form-table-select dog-room-name" data-hp_seq="${d.hp_seq}">
                                                                    <input type="text" placeholder="입력" oninput="typing_change(this)" value="${d.room_name}"/>
                                                                </div>
                                                            </td><td class="no-padding">
                                                                <div class="form-table-select">
                                                                    <input type="number" placeholder="입력" value="${d.room_cnt}"/>
                                                                </div>
                                                            </td></tr>`

                            document.getElementById('hotel_grade_dog_sep_weight').innerHTML += `<th class="grade-dog-sep-th">${d.room_name}</th>`
                            document.getElementById('hotel_peak_dog_sep_weight').innerHTML += `<th class="peak-dog-sep-th">${d.room_name}</th>`
                            document.getElementById('hotel_photo_dog_tr').innerHTML += `<th class="photo-dog-sep-th">${d.room_name}</th>`
                            document.getElementById('hotel_photo_dog_tbody_tr').innerHTML += `<td class="hotel-photo-dog-td" data-photos="" id="dog_${i}"><div class="upload-img-btn" data-rid="dog_${i}" onclick="HotelMemofocusNcursor(this)"><img src="/static/pub/images/icon/icon-picture-add.png" width="30%" style="max-width: 30px;" ></div></td>`

                        })


                        for(let i=0; i<document.getElementById('select_hotel_grade_dog').options.length; i++){

                            if(body.dog.length === parseInt(document.getElementById('select_hotel_grade_dog').options[i].value)){

                                document.getElementById('select_hotel_grade_dog').options[i].selected = true;
                                // document.getElementById('select_hotel_grade_dog').dispatchEvent(new Event('change'));
                            }


                        }

                    }



                    if(body.cat.length>0){


                        body.cat.forEach(function(c){

                            c.fee_list.forEach(function(fee){

                                let item = {hp_seq:c.hp_seq,name:c.room_name,kg:fee.kg,normal_price:fee.normal_price,peak_price:fee.peak_price};
                                cat_arr.push(item);

                            })

                            document.getElementById('hotel_grade_cat_tbody').innerHTML += `<tr><td class="no-padding">
                                                                <div class="form-table-select">
                                                                    <input type="text" placeholder="입력" value="${c.room_name}"/>
                                                                </div>
                                                            </td><td class="no-padding">
                                                                <div class="form-table-select">
                                                                    <input type="text" placeholder="입력" value="${c.room_cnt}"/>
                                                                </div>
                                                            </td></tr>`
                            document.getElementById('hotel_grade_cat_sep_weight').innerHTML += `<th>${c.room_name}</th>`

                        })

                        for(let i=0; i<document.getElementById('select_hotel_grade_cat').options.length; i++){

                            if(body.cat.length === parseInt(document.getElementById('select_hotel_grade_cat').options[i].value)){

                                document.getElementById('select_hotel_grade_cat').options[i].selected = true;
                                // document.getElementById('select_hotel_grade_cat').dispatchEvent(new Event('change'));
                            }

                        }

                    }








                    let dog_weight = new Set();
                    let cat_weight = new Set();
                    dog_arr.forEach(function(d){

                        dog_weight.add(d.kg);

                    })

                    cat_arr.forEach(function(c){

                        cat_weight.add(c.kg);
                    })


                    for(let i=0; i<Array.from(dog_weight).length; i++){

                        document.getElementById('hotel_grade_dog_sep_tbody').innerHTML += `<tr class="hotel-grade-dog-sep-tr" id="hotel_grade_dog_sep_tr_${i}"></tr>`
                        document.getElementById('hotel_peak_dog_sep_tbody').innerHTML += `<tr class="hotel-peak-dog-sep-tr" id="hotel_peak_dog_sep_tr_${i}"></tr>`

                    }


                    for(let i=0; i<Array.from(cat_weight).length; i++){

                        document.getElementById('hotel_grade_cat_sep_tbody').innerHTML += `<tr class="hotel-grade-cat-sep-tr" id="hotel_grade_cat_sep_tr_${i}"></tr>`
                    }

                    let room = [dog_arr,cat_arr,dog_weight,cat_weight,body];



                    if(body?.coupon){

                        body.coupon.forEach(function(coupon){

                            if(coupon.type === 'C'){

                                document.getElementById('hotel_coupon_list').innerHTML = `<div class="form-vertical-cell middle hotel-coupon-div">
                                                                                                    <div class="grid-layout basic">
                                                                                                        <div class="grid-layout-inner">
                                                                                                            <div class="grid-layout-cell grid-100">
                                                                                                                <div class="form-group-item card">
                                                                                                                    <div class="form-item-label">상품명</div>
                                                                                                                    <div class="form-item-data">
                                                                                                                        <input type="text" class="" value="${coupon.name}" placeholder="입력" >
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="grid-layout-cell grid-30">
                                                                                                                <div class="form-group-item card">
                                                                                                                    <div class="form-item-label">이용횟수</div>
                                                                                                                    <div class="form-item-data">
                                                                                                                        <select class="arrow" data-coupon_seq="${coupon.coupon_seq}" data-type="C-given">
                                                                                                                            ${select_C_coupon()}
                                                                                                                        </select>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="grid-layout-cell grid-40">
                                                                                                                <div class="form-group-item card">
                                                                                                                    <div class="form-item-label">요금</div>
                                                                                                                    <div class="form-item-data">
                                                                                                                        <select class="arrow" data-coupon_seq="${coupon.coupon_seq}" data-type="C-price">
                                                                                                                            ${select_F_coupon()}
                                                                                                                        </select>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="grid-layout-cell flex-auto"><button type="button" class="btn-data-trash large">휴지통</button></div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>`
                            }else if(coupon.type ==='F'){
                                document.getElementById('hotel_flat_list').innerHTML = `<div class="form-vertical-cell middle hotel-flat-div">
                                                                                                    <div class="grid-layout basic">
                                                                                                        <div class="grid-layout-inner">
                                                                                                            <div class="grid-layout-cell grid-100">
                                                                                                                <div class="form-group-item card">
                                                                                                                    <div class="form-item-label">상품명</div>
                                                                                                                    <div class="form-item-data">
                                                                                                                        <input type="text" class="" value="${coupon.name}" placeholder="입력" >
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="grid-layout-cell grid-30">
                                                                                                                <div class="form-group-item card">
                                                                                                                    <div class="form-item-label">가격</div>
                                                                                                                    <div class="form-item-data">
                                                                                                                        <select class="arrow" data-coupon_seq="${coupon.coupon_seq}" data-type="F-price">
                                                                                                                            ${select_F_coupon()}
                                                                                                                        </select>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="grid-layout-cell grid-40">
                                                                                                                <div class="form-group-item card">
                                                                                                                    <div class="form-item-label">실적립금</div>
                                                                                                                    <div class="form-item-data">
                                                                                                                        <select class="arrow" data-coupon_seq="${coupon.coupon_seq}" data-type="F-given">
                                                                                                                            ${select_F_coupon()}
                                                                                                                        </select>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="grid-layout-cell flex-auto"><button type="button" class="btn-data-trash large">휴지통</button></div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>`

                            }


                        })
                    }

                    console.log(room)

                    resolve(room);
                }
            }

        })




    })


}

function select_weight(){

   let options = '';

   for(let i=1; i<40; i++){

       for(let j=0; j<10; j++){

           options += `<option value="${i}.${j}">${i}.${j}kg</option>`
       }

   }

   return options;

}

function select_price(){

    let options = '';

    options += `<option value="">선택안함</option>`
    for(let i=5000; i<300000; i+=500){

        options += `<option value="${i}">${i}</option>`
    }

    return options;
}


function select_C_coupon(){

    let options = '';

    for(let i=1; i<=30; i++){

        options += `<option value="${i}">${i}회</option>`
    }

    return options;
}

function select_F_coupon(){

    let options ='';

    for(let i=1000; i<=1500000; i+=1000){

        options += `<option value="${i}">${i}원</option>`
    }

    return options ;
}


function select_neutral_price(){


    let options ='';
    for(let i=500; i<=500000; i+=500){

        options += `<option value="${i}">${i}원</option>`
    }
    return options;
}

function select_24hour(){

    let options = '';

    for(let i=0; i<24; i++){

        options += `<option value="${fill_zero(i)}">${am_pm_check(i)} 시</option>`
    }

    return options;
}

function select_extra(){


    let options ='';
    options += `<option value="">선택안함</option>`


    for(let i=500; i<=500000; i+=500){

        options += `<option value="${i}">${i}원</option>`

    }

    return options;
}


function add_get_hotel_product_price_set(room){

    return new Promise(function(resolve){
        const dog_room = room[0];
        const cat_room = room[1];
        const dog_weight = Array.from(room[2]);
        const cat_weight = Array.from(room[3]);
        const data = room[4];


        console.log(room);

        dog_weight.forEach(function(d_w,i){

            document.getElementById(`hotel_grade_dog_sep_tr_${i}`).innerHTML += `<td class="no-padding " id="grade_dog_sep_tr_${i}" data-weight="${d_w}"><div class="form-table-select"><select class="weight-check" onchange="change_dog_price_select(this)" id="dog_weight_select_${i}" data-weight="${d_w}">${select_weight()}</select></div></td>`
            document.getElementById(`hotel_peak_dog_sep_tr_${i}`).innerHTML += `<td id="peak_dog_sep_tr_${i}" style="line-height: 40px;" data-weight="${d_w}">${d_w}kg</td>`



        })



        Array.from(document.getElementById('hotel_grade_dog_sep_tbody').children).forEach(function(c,k){



            data.dog.forEach(function(d,j){

                let d_kg_list =[];

                d.fee_list.forEach(function(f){

                    d_kg_list.push(f.kg);

                })

                let diff = dog_weight.filter(x => !d_kg_list.includes(x));

                d.fee_list.forEach(function(f,i){

                    if(c.children[0].getAttribute('data-weight')===f.kg){

                        c.innerHTML += `<td class="no-padding"><div class="form-table-select"><select class="price-check" id="dog_price_select_${d.hp_seq}_${k}" data-normal_price="${f.normal_price}">${select_price()}</select></div></td>`
                    }

                })

                if(diff.includes(c.children[0].getAttribute('data-weight'))){
                    c.innerHTML += `<td class="no-padding"><div class="form-table-select"><select class="price-check" id="dog_price_select_${d.hp_seq}_${k}">${select_price()}</select></div></td>`
                }
            })
        })


        Array.from(document.getElementById('hotel_peak_dog_sep_tbody').children).forEach(function(c,k){



            data.dog.forEach(function(d,j){

                let d_kg_list =[];

                d.fee_list.forEach(function(f){

                    d_kg_list.push(f.kg);

                })

                let diff = dog_weight.filter(x => !d_kg_list.includes(x));

                d.fee_list.forEach(function(f,i){

                    if(c.children[0].getAttribute('data-weight')===f.kg){

                        c.innerHTML += `<td class="no-padding"><div class="form-table-select"><select class="price-check" id="dog_price_select_${d.hp_seq}_${k}" data-normal_price="${f.peak_price}">${select_price()}</select></div></td>`
                    }

                })

                if(diff.includes(c.children[0].getAttribute('data-weight'))){
                    c.innerHTML += `<td class="no-padding"><div class="form-table-select"><select class="price-check" id="dog_price_select_${d.hp_seq}_${k}">${select_price()}</select></div></td>`
                }
            })
        })




        resolve(room);

        })



}

function add_get_hotel_product_price_set_2(room){

    const dog_room = room[0];
    const cat_room = room[1];
    const dog_weight = Array.from(room[2]);
    const cat_weight = Array.from(room[3]);
    const data = room[4];




    Array.from(document.getElementsByClassName('arrow')).forEach(function(el){


        if(el.getAttribute('data-coupon_seq') && el.getAttribute('data-type')){

            let type = '';
            switch (el.getAttribute('data-type')){

                case 'C-given' : case 'F-given' : type = 'given'; break;
                case 'C-price' : case  'F-price' : type = 'price'; break;

            }

            data.coupon.forEach(function(coupon){

                if(coupon.coupon_seq === parseInt(el.getAttribute('data-coupon_seq'))){

                    for(let i=0; i<el.options.length;i++){

                        if(parseInt(el.options[i].value) === coupon[type]){
                            el.options[i].selected = true;
                        }
                    }
                }
            })
        }

    })








    Array.from(document.getElementsByClassName('weight-check')).forEach(function(el){


        if(el.getAttribute('data-weight')){

            for(let i=0; i<el.children.length; i++){

                if(el.getAttribute('data-weight') === el.options[i].value){
                    el.options[i].selected = true;
                }
            }
        }
    })

    Array.from(document.getElementsByClassName('price-check')).forEach(function(el){


        if(el.getAttribute('data-normal_price')){

            for(let i=0; i<el.children.length; i++) {

                if (el.getAttribute('data-normal_price') === el.options[i].value) {

                    el.options[i].selected = true;
                }
            }

        }

    })
}


function peak_price_toggle(bool,type){


    if(bool){

        if(type === 'dog'){

            document.getElementById('hotel_peak_dog_wrap').style.display = 'block'
        }else{

        }

    }else{

        if(type === 'dog'){

            document.getElementById('hotel_peak_dog_wrap').style.display ='none';
        }else{

        }
    }
}

function room_picture_toggle(bool,type){


    if(bool){

        if(type === 'dog'){

            document.getElementById('hotel_room_photo_dog_wrap').style.display = 'block'
        }else{

        }

    }else{

        if(type === 'dog'){

            document.getElementById('hotel_room_photo_dog_wrap').style.display ='none';
        }else{

        }
    }
}

function HotelMemofocusNcursor(target) {
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

    $("#addimgfile").attr('data-rid',target.getAttribute('data-rid'));
    $("#addimgfile").trigger("click");

}
// data-url="/data/fileupload_ajax.php"

function hotel_photo_update(target,id) {

    let rid = target.getAttribute('data-rid');



    var upload_chk = "0";
    if (upload_chk == "0") {
        upload_chk = "1";
        var ext = $('#addimgfile').val().split('.').pop().toLowerCase();
        if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
            alert('gif,png,jpg,jpeg 파일만 업로드 할수 있습니다.');
            return;
        }

        var filename = $("input[name=imgupfile]")[0].files[0].name;
        var newfilename = GetPhotoFilename('hotelProduct', id, filename);
        var formData = new FormData();

        formData.append("myfile", $("input[name=imgupfile]")[0].files[0]);
        formData.append("filepath", newfilename);

        $.ajax({
            url: 'data/upload_hotel_product.php',
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            beforeSend: function () {
                $("#loading").addClass("actived");
            },
            success: function (data,res) {
                console.log(data);
                console.log(res);
                $("#loading").removeClass("actived");
                if (/(MSIE|Trident)/.test(navigator.userAgent)) {
                    // ie 일때 input[type=file] init.
                    $("#addimgfile").replaceWith($("#addimgfile").clone(true));
                } else {
                    // other browser 일때 input[type=file] init.
                    $("#addimgfile").val("");
                }

                //if ($("#con_img").val() == "" || $("#con_img").val() == undefined) {
                $("#con_img").attr('src', "https://image.banjjakpet.com/upload/" + newfilename);
                $(".file-preview-data").addClass('actived');
                //}

                document.getElementById(`${rid}`).setAttribute('data-photos',`${document.getElementById(`${rid}`).getAttribute('data-photos') === '' ? '' : document.getElementById(`${rid}`).getAttribute('data-photos')}${document.getElementById(`${rid}`).getAttribute('data-photos') === '' ? '':','}${data}`);



                $.ajax({

                    url:'/data/api.php',
                    type:'post',
                    data:{
                        mode:'get_file_img',
                        f_seq:data,
                    },
                    success:function(res) {
                        console.log(res);
                        let response = JSON.parse(res);
                        let head = response.data.head;
                        let file_path = response.data.file_path

                        console.log(file_path);

                        document.getElementById(`${rid}`).innerHTML+=`<img src="${file_path}" alt="">`

                    }

                })




            },
            error: function (xhr, status, error) {
                alert(error+"에러발생");
            }
        });

    } else {
        alert("사진파일 업로드 중입니다.");
    }

}




function add_hotel_coupon(){

    let coupon_div_count = $('.hotel-coupon-div').length;
    if(coupon_div_count <5){
        let div = `<div class="form-vertical-cell middle hotel-coupon-div">
                    <div class="grid-layout basic">
                        <div class="grid-layout-inner">
                            <div class="grid-layout-cell grid-100">
                                <div class="form-group-item card">
                                    <div class="form-item-label">상품명</div>
                                    <div class="form-item-data">
                                        <input type="text" class="" value="" placeholder="입력" >
                                    </div>
                                </div>
                            </div>
                            <div class="grid-layout-cell grid-30">
                                <div class="form-group-item card">
                                    <div class="form-item-label">이용횟수</div>
                                    <div class="form-item-data">
                                        <select class="arrow">
                                            ${select_C_coupon()}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="grid-layout-cell grid-40">
                                <div class="form-group-item card">
                                    <div class="form-item-label">요금</div>
                                    <div class="form-item-data">
                                        <select class="arrow">
                                            ${select_F_coupon()}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="grid-layout-cell flex-auto"><button type="button" class="btn-data-trash large">휴지통</button></div>
                        </div>
                    </div>
                </div>`
        $('#hotel_coupon_list').append(div);
    }
}

function add_hotel_flat(){
    let flat_div_count = $('.hotel-flat-div').length;
    if(flat_div_count <5){
        let div = `<div class="form-vertical-cell middle hotel-flat-div">
                    <div class="grid-layout basic">
                        <div class="grid-layout-inner">
                            <div class="grid-layout-cell grid-100">
                                <div class="form-group-item card">
                                    <div class="form-item-label">상품명</div>
                                    <div class="form-item-data">
                                        <input type="text" class="" value="" placeholder="입력" >
                                    </div>
                                </div>
                            </div>
                            <div class="grid-layout-cell grid-30">
                                <div class="form-group-item card">
                                    <div class="form-item-label">가격</div>
                                    <div class="form-item-data">
                                        <select class="arrow">
                                            ${select_F_coupon()}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="grid-layout-cell grid-40">
                                <div class="form-group-item card">
                                    <div class="form-item-label">실적립금</div>
                                    <div class="form-item-data">
                                        <select class="arrow">
                                            ${select_F_coupon()}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="grid-layout-cell flex-auto"><button type="button" class="btn-data-trash large">휴지통</button></div>
                        </div>
                    </div>
                </div>`
        $('#hotel_flat_list').append(div);
    }

}
function add_hotel_dog_table(){

    let grade_dt_count = $('.hotel-grade-dog-sep-tr').length;
    if(grade_dt_count <5){
        let grade_dt_div = $('.hotel-grade-dog-sep-tr:last-child').clone();
        $('#hotel_grade_dog_sep_tbody').append(grade_dt_div);

        let peak_dt_div = $('.hotel-peak-dog-sep-tr:last-child').clone();
        $('#hotel_peak_dog_sep_tbody').append(peak_dt_div);


    }




}

function delete_hotel_dog_table(){

    let grade_total_cnt = $('.hotel-grade-dog-sep-tr').length;
    if(grade_total_cnt > 1) {
        $('.hotel-grade-dog-sep-tr:last-child').remove();
    }

    let peak_total_cnt = $('.hotel-peak-dog-sep-tr').length;

    if(peak_total_cnt >1){
        $('.hotel-peak-dog-sep-tr:last-child').remove();
    }
}

function hotel_delete_photo(target){

    target.parentElement.remove();
}
function hotel_coupon_toggle(bool){

    if(bool){

        document.getElementById('hotel_coupon_wrap').style.display ='block';

    }else{
        document.getElementById('hotel_coupon_wrap').style.display ='none';

    }
}

function hotel_flat_toggle(bool){

    if(bool){

        document.getElementById('hotel_flat_wrap').style.display = 'block';
    }else{
        document.getElementById('hotel_flat_wrap').style.display ='none';
    }
}

function test_send(){


    $('#fileupload').fileupload({
        formData: {
            mode: "upload_img",
            target: "tb_hotel_product.image",
            folder: "hotel"
        },
        dataType: 'json',
        done: function (e, data) {
            console.log(data);
        }

    });

}



function hotel_neutral_toggle(bool){


    if(bool){

        document.getElementById('hotel_neutral_use').style.display = 'block';
    }else{
        document.getElementById('hotel_neutral_use').style.display = 'none';
    }
}

function hotel_neutral_price_toggle(bool){

    if(bool){

        document.getElementById('hotel_neutral_price').style.display ='block'
    }else{
        document.getElementById('hotel_neutral_price').style.display ='none'

    }
}


function hotel_24hour_toggle(bool){

    if(bool){
        document.getElementById('hotel_24hour_wrap').style.display ='block';
    }else{
        document.getElementById('hotel_24hour_wrap').style.display ='none';

    }
}

function typing_change(target){


    let value = target.value;

    let index = $(target).parent().parent().parent().index();

    Array.from(document.getElementsByClassName('grade-dog-sep-th'))[index].innerText = value;
    Array.from(document.getElementsByClassName('peak-dog-sep-th'))[index].innerText = value;
    Array.from(document.getElementsByClassName('photo-dog-sep-th'))[index].innerText = value;


}

function change_dog_price_select(target){

    let value = target.value;



    target.setAttribute('data-weight',value);

    let index = $(target).parent().parent().parent().index();


    console.log(index);


    document.getElementById('hotel_peak_dog_sep_tbody').children[index].children[0].setAttribute('data-weight',value);
    document.getElementById('hotel_peak_dog_sep_tbody').children[index].children[0].innerText = `${value}kg`;


    // document.getElementById(`peak_dog_sep_tr_${index}`).setAttribute('data-weight',value);
    // document.getElementById(`peak_dog_sep_tr_${index}`).innerText = `${value}kg`


}

function save_hotel_set(id){


    return new Promise(function(resolve){



        if(document.getElementById('hotel_coupon_y').checked === true){


            let coupon = Array.from(document.getElementsByClassName('hotel-coupon-div'));


            if(coupon.length >0 ){


                coupon.forEach(function(el){




                })
            }

        }

        let room_arr = [];

        $.ajax({
            url: '/data/api.php',
            type: 'post',
            async:false,
            data: {
                mode: 'get_hotel_product',
                artist_id: id
            },
            success: function (res) {
                let response = JSON.parse(res);
                let head = response.data.head;
                let body = response.data.body;
                if (head.code === 401) {
                    pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
                } else if (head.code === 200) {
                    console.log(body)

                    body.dog.forEach(function(d){

                        room_arr.push(d.hp_seq);
                    })


                }
            }
        })


        const room_pet_type = document.getElementById('hotel_dog_tab').classList.contains('actived') ? 'dog' : 'cat';

        let weight = '';

        let weight_bool = false;

        const weight_check = document.getElementsByClassName('weight-check');
        Array.from(weight_check).forEach(function(el,i){

            let weights = weight.split(',');
            if(weights.includes(el.value)){
                weight_bool = true;
                pop.open('weightCheck');
                return;
            };
            if(weight_check.length-1 === i){
                weight += `${el.value}`
            }else{
                weight += `${el.value},`
            }
        })
        if(weight_bool){

            return;
        }


        const dog_sep_tr = document.getElementsByClassName('hotel-grade-dog-sep-tr');

        const dog_peak_sep_tr = document.getElementsByClassName('hotel-peak-dog-sep-tr');


        const is_neutral = document.querySelector('input[name="radio5"]:checked').getAttribute('id') === 'hotel_neutral_y' ? 1 : 2;

        const is_neutral_pay = document.querySelector('input[name="radio6"]:checked').getAttribute('id') === 'hotel_neutral_price_y' ? 1 : 2;

        const neutral_price = document.getElementById('hotel_neutral_price_select').value;

        const extra_price = document.getElementById('hotel_extra_price').value;

        const is_peak = document.querySelector('input[name="radio1"]:checked').getAttribute('id') === 'hotel_peak_dog_y' ? 1 : 2;
        const is_image = document.querySelector('input[name="radio2"]:checked').getAttribute('id') === 'hotel_room_photo_dog_y' ? 1 : 2;

        const comment = document.getElementById('hotel_common_notice').value;


        let filtered;

        for(let i=0; i<document.getElementById('select_hotel_grade_dog').value; i++){

            let dog_room_name = document.getElementsByClassName('dog-room-name');
            let room_name = dog_room_name[i].children[0].value;
            let room_cnt = dog_room_name[i].parentElement.parentElement.children[1].children[0].children[0].value;
            let hp_seq = dog_room_name[i].getAttribute('data-hp_seq');


            let normal_price ='';
            for(let j=0; j<dog_sep_tr.length; j++){
                if(j === dog_sep_tr.length -1){
                    if(dog_sep_tr[j].children[i+1].children[0].children[0].value === ''){
                        normal_price += `0`
                    }else{
                        normal_price += `${dog_sep_tr[j].children[i+1].children[0].children[0].value}`;
                    }
                }else{
                    if(dog_sep_tr[j].children[i+1].children[0].children[0].value === ''){
                        normal_price += `0,`
                    }else{
                        normal_price += `${dog_sep_tr[j].children[i+1].children[0].children[0].value},`;
                    }
                }
            }


            let peak_price = '';

            for(let j=0; j<dog_peak_sep_tr.length; j++){
                if(j === dog_peak_sep_tr.length -1){
                    if(dog_peak_sep_tr[j].children[i+1].children[0].children[0].value === ''){
                        peak_price += `0`
                    }else{
                        peak_price += `${dog_peak_sep_tr[j].children[i+1].children[0].children[0].value}`;
                    }
                }else{
                    if(dog_peak_sep_tr[j].children[i+1].children[0].children[0].value === ''){
                        peak_price += `0,`
                    }else{
                        peak_price += `${dog_peak_sep_tr[j].children[i+1].children[0].children[0].value},`;
                    }
                }

            }

            let image = document.getElementsByClassName('hotel-photo-dog-td')[i].getAttribute('data-photos');



            if(hp_seq === null){

                $.ajax({

                    url:'/data/api.php',
                    type:'post',
                    data:{
                        mode:'post_set_hotel_product',
                        h_seq:localStorage.getItem('h_seq'),
                        partner_id:id,
                        room_pet_type:room_pet_type,
                        room_name :room_name,
                        room_cnt : room_cnt,
                        weight:weight,
                        normal_price:normal_price,
                        peak_price:peak_price,
                        is_neutral:is_neutral,
                        is_neutral_pay:is_neutral_pay,
                        neutral_price:neutral_price,
                        extra_price:extra_price,
                        is_peak:is_peak,
                        is_image:is_image,
                        comment:comment,
                        image:image,

                    },
                    success:function(res) {
                        let response = JSON.parse(res);
                        let head = response.data.head;
                        let body = response.data.body;
                        if (head.code === 401) {
                            pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
                        } else if (head.code === 200) {
                            // location.reload();
                        }
                    }
                })


            }else{

                for(let i=0; i<room_arr.length; i++){

                    if(room_arr[i] === parseInt(hp_seq)){
                        room_arr.splice(i,1);
                        i--
                    }
                }

                filtered = room_arr;

                console.log(filtered)
                console.log(hp_seq);








                $.ajax({

                    url:'/data/api.php',
                    type:'post',
                    data:{
                        mode:'put_set_hotel_product',
                        hp_seq:hp_seq,
                        room_name :room_name,
                        room_cnt : room_cnt,
                        weight:weight,
                        normal_price:normal_price,
                        sort:i+1,
                        peak_price:peak_price,
                        is_neutral:is_neutral,
                        is_neutral_pay:is_neutral_pay,
                        neutral_price:neutral_price,
                        extra_price:extra_price,
                        is_peak:is_peak,
                        is_image:is_image,
                        comment:comment,
                        image:image,

                    },
                    success:function(res) {
                        console.log(res)
                        let response = JSON.parse(res);
                        let head = response.data.head;
                        let body = response.data.body;
                        if (head.code === 401) {
                            pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
                        } else if (head.code === 200) {
                            console.log(body)
                            // location.reload();
                        }
                    }
                })








            }





        }

        resolve(filtered);
    })

}

function save_hotel_set_delete(filtered){

    console.log(filtered);
    filtered.forEach(function(hp_seq){
        $.ajax({

            url:'/data/api.php',
            type:'post',
            data:{
                mode:'delete_set_hotel_product',
                hp_seq:hp_seq
            },
            success:function(res) {
                console.log(res);
                let response = JSON.parse(res);
                let head = response.data.head;
                let body = response.data.body;
                if (head.code === 401) {
                    pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
                } else if (head.code === 200) {
                    console.log(body)
                }
            }

        })


    })
}



function hotel_product_init(id){

    let room_pet_type = document.getElementById('dog_tab').classList.contains('actived') ? 'dog':'cat';


    $.ajax({
        url: '/data/api.php',
        type: 'post',
        data: {
            mode: 'get_hotel_product',
            artist_id: id
        },
        success: function (res) {
            let response = JSON.parse(res);
            let head = response.data.head;
            let body = response.data.body;
            if (head.code === 401) {
                pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
            } else if (head.code === 200) {
                console.log(body)

                if(body.dog.length === 0){
                    $.ajax({

                        url:'/data/api.php',
                        type:'post',
                        data:{
                            mode:'post_set_hotel_product',
                            h_seq:localStorage.getItem('h_seq'),
                            partner_id:id,
                            room_pet_type:room_pet_type,
                            room_name:' ',
                            room_cnt:1,
                            weight:'1.0',
                            normal_price:'5000',
                            peak_price:'5000',
                            is_neutral: '2',
                            is_neutral_pay: '2',
                            neutral_price:0,
                            extra_price:0,
                            is_peak:'2',
                            is_image:'2',
                            comment:'',
                            image:'',

                        },
                        success:function(res) {
                            let response = JSON.parse(res);
                            let head = response.data.head;
                            let body = response.data.body;
                            if (head.code === 401) {
                                pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
                            } else if (head.code === 200) {
                                console.log(body)

                                location.href='/set_hotel_shop_add'

                            }
                        }
                    })

                }else{
                    location.href='/set_hotel_shop_add'

                }
            }
        }
    })







}