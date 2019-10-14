<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CouponActivateDatabase extends CI_Model {

    public function SaltData()
    {
        $salt = 'adastratechnologies';
        $hash = sha1($salt.'adastra');
        $this->session->set_userdata('userauth',$hash);
        if(isset($_GET['q']))
        {
            $mobno = $_GET['q'];
            $pw_hash = sha1($salt.$mobno);
            $matchData = $this->db->query("select * from api_table where MATCH(salt) AGAINST('$pw_hash' IN NATURAL LANGUAGE MODE)")->result_array(); 
            if($this->db->affected_rows()>0)
            {
                $this->session->set_userdata('userauth',$pw_hash);
                return 0;
            }
        }
        return 0;
        // return $pw_hash;
        // return $iduser;
    }

    public function RetrieveCart()
    {
        //assuming session variable 'userid' has 'user id'
        // $this->session->set_userdata('userid',1);
        // if($this->session->has_userdata('userid'))
        // {
        //     $userid = $this->session->userdata('userid');
            $userid = $_GET['q'];
            $RetrieveData = $this->db->query("select * from cart_table where customer_id = '$userid'")->result_array();
            return $RetrieveData;
        // }
        // else
        // {
        //     return "error";
        // }
    }

    public function CouponActivate()
    {
        $couponCode = $this->input->post('couponCode');
        // $productId = $this->input->post('productId');
        $userid = $this->input->post('userId');
        $cartId = $this->input->post('cartId');

        //coupon code validation ie, whether the coupon code exists in coupon table
        $couponInfo = $this->db->query("select id,MaxusePC from coupon where coupon_code = '$couponCode'")->row();

        // return $couponInfo;
        if($this->db->affected_rows()>0)
        {
            //category validation ie, whether given coupon code belongs to the given category
            // $productCategoryId = $this->db->query("select category_id from product where id = '$productId'")->row();
            // if($productCategoryId == $couponCategoryId)
            // {
                
                //check whether the cart already contains the coupon
                // $checkCoupon = $this->db->query("select coupon_code from cart where id ='$cartId'")->row();
                // if($checkCoupon->coupon_code != null)
                // {
                //     return "coupon already applied for this item";
                // }
                // else
                // {
                    //user-coupon validation one user can use twice a coupon 
                    $couponCount = $this->db->query("select UseCount from couponsubscription where customer_id = '$userid' AND coupon_id = '$couponInfo->id'")->row();
                    $useCount =  $couponCount->UseCount;
                    $maxCount = $couponInfo->MaxusePC;
                    if($this->db->affected_rows() > 0)
                    {
                        // //update coupon code in the particular cart id
                        // $coupon = array('coupon_id'=>$couponInfo->id);
                        // $this->db->where('id',$cartId);
                        //     $this->db->update('cart',$coupon);
                        // return "coupon successfully applied";
                        if($useCount < $maxCount)
                        {
                            $useCount = $useCount+1;
                            $this->db->trans_start();
                                $this->db->set('Usecount', $useCount);
                                $this->db->set('time_stamp', Date('Y-m-d h:i:s'));
                                $this->db->update('couponsubscription');
                            $this->db->trans_complete();
                            return "coupon subscribed";
                        }
                        else
                        {
                            return "max limit reached";
                        }
                        
                    }
                    else
                    {
                        $insert = array('customer_id'=>$userid,
                                        'coupon_id'=>$couponInfo->id,
                                    'UseCount'=>1,
                                'time_stamp'=>Date('Y-m-d h:i:s'));

                        $this->db->trans_start();
                            $this->db->insert('couponsubscription',$insert);
                        $this->db->trans_complete();
                        return "coupon subscribed for first time";
                    }
                // }
                
            // }
            // else
            // {
            //     return "Invalid Category";
            // }
        }
        else
        {
            return "Invalid Coupon";
        }
        // return $couponValidate;
    }

    
}

