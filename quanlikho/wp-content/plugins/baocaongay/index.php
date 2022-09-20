<?php 
/**
 * @package Báo cáo ngày
 * @version 1.0.0
 */
/*
Plugin Name: Báo cáo ngày
Plugin URI: http://wordpress.org/plugins/
Description: Bao cao ngay
Author: Dev
Version: 1.0.0
Author URI: http://ma.tt/
*/
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;


function plugin_admin_scripts() {
    wp_enqueue_style('style_plugin', plugins_url('baocaongay/styles.css'), array(), null);
    //wp_enqueue_script('js_plugin', plugins_url('quanli/index.js'), array(), null);
    wp_enqueue_style('boostrap_plugin', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css', array(), null);
    //wp_enqueue_script('jquery_plugin', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', array(), null);
}
add_action( 'admin_enqueue_scripts', 'plugin_admin_scripts' );

function add_admin_menu_ok()
{
    add_menu_page(
        'Báo cáo ngày', 
        'Báo cáo ngày', 
        'manage_options', 
        'bao-cao-ngay', 
        'get_daily_purchases_total', 
        '', 
        '55.5'
    );
}
function get_daily_purchases_total(){
    ?>
    <h2 class="title_baocao">Báo cáo ngày (<?php echo date('d/m/Y'); ?>)</h2>
    <table class="table_baocao">
        <thead>
            <th>STT</th>
            <th>Tên khách</th>
            <th>Địa chỉ khách</th>
            <th>Số điện thoại khách</th>
            <th>Sản phẩm</th>
            <th>Mã bảo hành</th>
            <th>Số lượng</th>
            <th>Tổng</th>
        </thead>
        <tbody>
        <?php
        $detals_order = get_details_order();
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
                <td><?php echo number_format($value['total'], 0, '', ','); ?></td>
            </tr>
            <?php
            $tong = $tong + $value['total'];
            
        }
        ?>
        </tbody>
    </table>
    <div class="tongket">
        <h4>Tổng số đơn trong ngày: <?php echo $i; ?></h4>
        <h4>Trung bình mỗi đơn: <?php if($i > 0){echo number_format($tong/$i, 0, '', ',');} ?></h4>
        <h4>Tổng tiền: <?php echo number_format($tong, 0, '', ','); ?></h4>
    </div>
    <?php
}
function get_details_order(){
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
    if(isset($complete)){
        $date_int = strtotime($order->get_date_completed());
        $date_now = date("Y-m-d" , $date_int);
        if($date_now == $date){
            foreach ( $order->get_items() as  $item_key => $item_values ) {
                $item_data = $item_values->get_data();
                $Order_Array[] = [
                    "id" => $order->get_id(),
                    "billing"=>[
                        "first_name" => $order->get_billing_first_name(),
                        "last_name" => $order->get_billing_last_name(),
                        "address_1" => $order->get_billing_address_1(),
                        "phone"	=> $order->get_billing_phone()
                    ],
                    'name' => $item_data['name'],
                    'quantity'=> $item_data['quantity'],
                    'total'	=> $item_data['total'],
                    'baohanh' => $order->get_meta('_billing_wooccm11')
                ];
            }
        }
    }
    
    endwhile;
    return $Order_Array;
    wp_reset_postdata();
    endif;
}
// function get_daily_orders_count( $date = 'now' ){
//     if( $date == 'now' ){
//         $date = date("Y-m-d");
//         $date_string = "> '$date'";
//     } else {
//         $date = date("Y-m-d", strtotime( $date ));
//         $date2 = date("Y-m-d", strtotime( $date ) + 86400 );
//         $date_string = "BETWEEN '$date' AND '$date2'";
//     }
//     global $wpdb;
//     $result = $wpdb->get_var( "
//         SELECT DISTINCT count(p.ID) FROM {$wpdb->prefix}posts as p
//         WHERE p.post_type = 'shop_order' AND p.post_date $date_string
//         AND p.post_status IN ('wc-on-hold','wc-processing','wc-completed')
//     " );
//     return $result;
// }
add_action('admin_menu', 'add_admin_menu_ok');
