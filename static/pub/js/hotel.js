function am_pm_check(hours){

    if(hours > 12){
        hours = `오후 ${(hours-12).toString().length <2 ? '0' : ''}${hours-12}`
    }else if(hours === 12){
        hours = `오후 ${hours}`
    }else{
        hours = `오전 ${hours}`
    }

    return hours;
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

                    if(body.cat.length === 0 && body.coupon.length ===0 && body.dog.length === 0){

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

                        if(document.getElementById('hotel_dog_product_none_data')){

                            document.getElementById('hotel_dog_product_none_data').style.display = 'flex';
                        }

                    }else{
                        if(document.getElementById('hotel_dog_product_none_data')){

                            document.getElementById('hotel_dog_product_none_data').style.display = 'none';
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
        document.getElementById('hotel_dog_product_none_data').style.display ='flex';
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

                    }else{
                        document.getElementById('hotel_product_wrap').style.display='none';
                        document.getElementById('hotel_cat_wrap').style.display = 'none';
                        document.getElementById('hotel_dog_wrap').style.display ='none';
                        document.getElementById('hotel_dog_product_none_data').style.display ='flex';
                    }


                }else if(el.getAttribute('id') === 'cat_tab'){

                    document.getElementById('hotel_product_delete_btn').setAttribute('data-type','cat');
                    if(el.getAttribute('data-exist') === '1'){

                        document.getElementById('hotel_product_wrap').style.display='block';
                        document.getElementById('hotel_dog_wrap').style.display ='none';
                        document.getElementById('hotel_cat_wrap').style.display = 'block';
                        document.getElementById('hotel_dog_product_none_data').style.display ='none';
                        document.getElementById('btn_accordion_menu').setAttribute('data-type','cat');
                    }else{
                        document.getElementById('hotel_product_wrap').style.display='none';
                        document.getElementById('hotel_dog_wrap').style.display ='none';
                        document.getElementById('hotel_cat_wrap').style.display = 'none';
                        document.getElementById('hotel_dog_product_none_data').style.display ='flex';


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


                switch(body.pet_type){

                    case 'dog' : document.getElementById('dog_switch_toggle').checked = true; break;
                    case 'cat' : document.getElementById('cat_switch_toggle').checked = true; break;
                    case 'both' : document.getElementById('dog_switch_toggle').checked = true; document.getElementById('cat_switch_toggle').checked = true; break;
                }
            }
        }
    })
}

function add_get_hotel_product(id){
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
            }
        }
    })

}

function change_hotel_grade_dog(target){


    document.getElementById('hotel_grade_dog_tbody').innerHTML = '';


    for(let i=0; i<target.value; i++){

        document.getElementById('hotel_grade_dog_tbody').innerHTML += `<tr><td class="no-padding">
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