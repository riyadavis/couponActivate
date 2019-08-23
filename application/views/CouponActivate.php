<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Coupon Activate</title>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>    
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="StyleSheet" type="text/css" href="<?php echo base_url()."assets/css/bootstrap.min.css"; ?>">
    <link rel="StyleSheet" type="text/css" href="<?php echo base_url()."assets/css/main.css"; ?>">
</head>
<body>
    <div id="couponActivate"></div>
</body>
<script src="<?php echo base_url().'assets/js/bootstrap.min.js';?>"></script>
<script>
    $(document).ready(function(){

        RetrieveCart();
        async function RetrieveCart()
        {
            let url = "<?php echo site_url('CouponActivateApi/RetrieveCart'); ?>";
            let request = await fetch(url);
            let response = await request.json();
            // console.log(response);
            response.map(r=>{
                //the cart is not viewed, but the cart id is displayed
                let cartId = document.createElement('input');
                    cartId.value = r.id;
                    // let textnode = document.createTextNode(r.id);
                    // cartId.appendChild(textnode);
                    // cartId.appendChild(document.createTextNode('textnode'));
                    // cartId.value = r.id;
                let couponInfo = document.createElement('input');
                    couponInfo.name = "couponCode";
                    couponInfo.id = "couponCode";
                    couponInfo.placeholder = "Coupon Code";
                let productId = document.createElement('input');
                    productId.value = r.product_id;
                    productId.id = "productId";
                    productId.name = "productId";
                    productId.type = "hidden";
                let userId = document.createElement('input');
                    userId.id = "userId";
                    userId.type = "hidden";
                    userId.value = r.user_id;
                    userId.name = "userId";
                let button = document.createElement('input');
                    button.value = "Coupon Activate";
                    button.type = "button";
                   
                let couponDiv = document.getElementById('couponActivate');
                let para = document.createElement('p');
                couponDiv.appendChild(cartId);
                couponDiv.appendChild(couponInfo);
                couponDiv.appendChild(button);
                couponDiv.appendChild(para);

                button.onclick = async function (){
                    // console.log(couponInfo.value);
                        let url = "<?php echo site_url('CouponActivateApi/CouponActivate');?>";
                        var form = new FormData();
                        form.append('productId',productId.value);
                        form.append('couponCode',couponInfo.value);
                        form.append('userId',userId.value);
                        form.append('cartId',cartId.value);
                        let request = await fetch(url,{
                            method : "post",
                            body : form
                        });
                        let response = await request.json();
                        console.log(response);
                    }
            });
        }
    });
</script>
</html>