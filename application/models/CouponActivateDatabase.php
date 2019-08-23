<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CouponActivateDatabase extends CI_Model {

    public function RetrieveCart()
    {
        //assuming session variable 'userid' has 'user id'
        $this->session->set_userdata('userid',1);
        if($this->session->has_userdata('userid'))
        {
             $userid = $this->session->userdata('userid');
            $RetrieveData = $this->db->query("select * from cart where user_id = '$userid'")->result_array();
            return $RetrieveData;
        }
        else
        {
            return "error";
        }
    }

    public function CouponActivate()
    {
        $couponCode = $this->input->post('couponCode');
        $productId = $this->input->post('productId');
        $userid = $this->input->post('userId');
        $cartId = $this->input->post('cartId');

        //coupon code validation ie, whether the coupon code exists in coupon table
        $couponCategoryId = $this->db->query("select category_id from coupon where coupon_code = '$couponCode'")->row();
    
        if($this->db->affected_rows()>0)
        {
            //category validation ie, whether given coupon code belongs to the given category
            $productCategoryId = $this->db->query("select category_id from product where id = '$productId'")->row();
            if($productCategoryId == $couponCategoryId)
            {
                
                //check whether the cart already contains the coupon
                $checkCoupon = $this->db->query("select coupon_code from cart where id ='$cartId'")->row();
                if($checkCoupon->coupon_code != null)
                {
                    return "coupon already applied for this item";
                }
                else
                {
                    //user-coupon validation one user can use twice a coupon 
                    $couponCount = $this->db->query("select * from cart where user_id = '$userid' AND coupon_code = '$couponCode'")->result_array();
                    if($this->db->affected_rows() < 2)
                    {
                        //update coupon code in the particular cart id
                        $coupon = array('coupon_code'=>$couponCode);
                        $this->db->where('id',$cartId);
                            $this->db->update('cart',$coupon);
                        return "coupon successfully applied";
                    }
                    else
                    {
                        return "coupon limit exceeded";
                    }
                }
                
            }
            else
            {
                return "Invalid Category";
            }
        }
        else
        {
            return "Invalid Coupon";
        }
        // return $couponValidate;
    }
}

