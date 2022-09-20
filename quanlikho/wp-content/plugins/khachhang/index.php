<?php 
/**
 * @package Khách hàng
 * @version 1.0.0
 */
/*
Plugin Name: Khách hàng
Plugin URI: http://wordpress.org/plugins/
Description: Quản lí khách hàng
Author: Dev
Version: 1.0.0
Author URI: http://ma.tt/
*/
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;


function custom_customer()
{
    add_menu_page(
        'Khách hàng', 
        'Khách hàng', 
        'manage_options', 
        'khach-hang', 
        'show_customer', 
        '', 
        '56'
    );
}
function show_customer(){
    ?>
    <h2 class="title_baocao">Khách hàng</h2>
    <table class="table_baocao">
        <thead>
            <th>STT</th>
            <th>Tên khách</th>
            <th>Địa chỉ khách</th>
            <th>Số điện thoại khách</th>
            <th>Sản phẩm</th>
            <th>Mã bảo hành</th>
            <th>Số lượng</th>
            <th>Ngày mua</th>
            <th>Tổng tiền hóa đơn</th>
        </thead>
        <tbody>
        <?php
        $detals_order = get_customer();
        $i = 0;
        $tong = 0;
        foreach ($detals_order as $key => $value) {
            # code...
            $i++;
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $value['billing']['first_name']." ".$value['billing']['last_name']; ?></td>
                <td><?php echo $value['billing']['address_1']; ?></td>
                <td><?php echo $value['billing']['phone']; ?></td>
                <td><?php echo $value['name']; ?></td>
                <td><?php echo $value['baohanh']; ?></td>
                <td><?php echo $value['quantity']; ?></td>
                <td><?php echo date("d/m/Y", strtotime($value['ngaymua'])); ?></td>
                <td><?php echo number_format($value['total'], 0, '', ','); ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php
}
function get_customer(){
    $loop = new WP_Query( array(
        'post_type'         => 'shop_order',
        'post_status'       =>  array_keys( wc_get_order_statuses() ),
        'posts_per_page'    => -1,
    ) );
    $date = date("Y-m-d");
    // The Wordpress post loop
    $Order_Array = [];
    if ( $loop->have_posts() ): 
    while ( $loop->have_posts() ) : $loop->the_post();
    
    // The order ID
    $order_id = $loop->post->ID;
    // Get an instance of the WC_Order Object
    $order = wc_get_order($loop->post->ID);
    $complete = $order->get_date_completed();
    // echo '<pre>';
    // print_r($order->get_meta('_billing_wooccm11'));
    if(isset($complete)){
        $date_int = strtotime($order->get_date_completed());
        $date_now = date("Y-m-d" , $date_int);
            foreach ( $order->get_items() as  $item_key => $item_values ) {
                $item_data = $item_values->get_data();
                //echo '<pre>';
                //print_r($item_values->meta_data());
                $Order_Array[] = [
                    "id" => $order->get_id(),
                    "billing"=>[
                        "first_name" => $order->get_billing_first_name(),
                        "last_name" => $order->get_billing_last_name(),
                        "address_1" => $order->get_billing_address_1(),
                        "phone"	=> $order->get_billing_phone()
                    ],
                    "value" => $order->get_total(),
                    'name' => $item_data['name'],
                    'quantity'=> $item_data['quantity'],
                    'total'	=> $item_data['total'],
                    'ngaymua'=> $complete,
                    'baohanh' => $order->get_meta('_billing_wooccm11'),
                ];
            }
    }
    
    endwhile;
    wp_reset_postdata();
    return ($Order_Array);
    endif;
}
add_action('admin_menu', 'custom_customer');