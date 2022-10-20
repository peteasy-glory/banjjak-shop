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

                        document.getElementById('hotel_product_none_data').style.display = 'flex';

                    }


                    if(body.dog.length === 0 ){


                        document.getElementById('hotel_dog_product_none_data').style.display = 'flex';



                    }else{
                        document.getElementById('hotel_dog_product_none_data').style.display = 'none';


                        let set = new Set();


                        body.dog.forEach(function(v){

                            document.getElementById('hotel_dog_colgroup').innerHTML += `<col style="width:auto;">`
                            document.getElementById('hotel_dog_room_list').innerHTML += `<th>${v.room_name}</th>`



                            v.fee_list.forEach(function(v2){

                                set.add(v2.kg);
                            })



                            // v.fee_list.forEach(function(v2){
                            //
                            //     document.getElementById('hotel_dog_product_tbody').innerHTML += `<tr><th>${v2.kg}</th></tr>`;
                            // })





                        })

                        let i = 0;
                        set.forEach(function(s){

                            i+=1;

                            document.getElementById('hotel_dog_product_tbody').innerHTML += `<tr ><th id="hotel_dog_product_row_${i}">${s}</th></tr>`


                        })
                    }
                    resolve(body);
                }
            }


        })

    })
}

function get_hotel_product_dog(data){


    let kg_list = new Set();



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



}
function wide_tab(){
    let tab_cell = document.getElementById('wide-tab-inner').children;

    Array.from(tab_cell).forEach(function (el) {

        el.addEventListener('click', function () {
            if (!this.classList.contains('actived')) {


                Array.from(tab_cell).forEach(function (el) {
                    el.classList.remove('actived');
                })

                this.classList.add('actived');
            } else {
                return;
            }
        })
    })
}


