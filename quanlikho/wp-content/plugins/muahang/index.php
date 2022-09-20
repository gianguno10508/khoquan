<?php 
/**
 * @package Mua hàng
 * @version 1.0.0
 */
/*
Plugin Name: Mua hàng
Plugin URI: http://wordpress.org/plugins/
Description: Mua hàng
Author: Dev
Version: 1.0.0
Author URI: http://ma.tt/
*/
// Install:
// composer require automattic/woocommerce

// Setup:
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

function plugin_scripts() {
    wp_enqueue_style('style_plugin_mua', plugins_url('muahang/styles.css'), array(), null);
    //wp_enqueue_script('js_plugin', plugins_url('quanli/index.js'), array(), null);
    wp_enqueue_style('style_plugin_mua', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css', array(), null);
    //wp_enqueue_script('jquery_plugin', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', array(), null);
}
add_action( 'admin_enqueue_scripts', 'plugin_scripts' );

function custom_order()
{
    add_menu_page(
        'Mua hàng', 
        'Mua hàng', 
        'manage_options', 
        'mua-hang', 
        'order', 
        '', 
        '55.6'
    );
}
function add_admin_submenu()
{
    add_submenu_page ('mua-hang', 'Sửa chữa', 'Sửa chữa', 'manage_options', 'sua_chua', 'sua' );
}
function sua(){
    ?>
    <h2 class="title-sua">Sửa chữa</h2>
    <form method="post" class="sua-form">
        <div class="thongtin">
            <label for="ho">
                <p>Họ</p>
                <input type="text" name="ho" id="ho" required>
            </label>
            <label for="ten">
                <p>Tên</p>
                <input type="text" name="ten" id="ten" required>
            </label>
        </div>
        <label for="diachi" class="diachisua">
            <p>Địa chỉ</p>
            <input type="text" name="diachi" id="diachi" required>
        </label>
        <label for="sodienthoai" class="sodienthoaisua">
            <p>Số điện thoại</p>
            <input type="number" name="sodienthoai" id="sodienthoai" required>
        </label>
        <label for="congviec" class="congviecsua">
            <p>Công việc</p>
            <input type="text" name="congviec" id="congviec">
        </label>
        <label for="gia" class="giasua">
            <p>Giá tiền</p>
            <input type="number" name="gia" id="gia">
        </label>
        <label for="mabaohanh" class="mabaohanhsua" >
            <p>Mã bảo hành</p>
            <input type="text" name="mabaohanh" id="mabaohanh">
        </label>
        <input type="submit" name="dathang" value="Đặt hàng" class="dat">
    </form>
    <?php
        if(isset($_POST['dathang'])){
            $args = array( 'post_type' => 'product',  'product_cat' => 'sua-chua');
            $loop = new WP_Query( $args );
            while ( $loop->have_posts() ) : $loop->the_post(); 
                global $product; 
                update_post_meta($product->get_id(), '_regular_price', (float)$_POST['gia']);
                update_post_meta($product->get_id(), '_price', (float)$_POST['gia']);
            endwhile; 
            wp_reset_query(); 
            $data = [
                'status' => 'completed',
                'billing' => [
                    'first_name' => $_POST['ho'],
                    'last_name' => $_POST['ten'],
                    'address_1' => $_POST['diachi'],
                    'phone' => $_POST['sodienthoai']
                ],
                'shipping' => [
                    'first_name' => $_POST['ho'],
                    'last_name' => $_POST['ten'],
                    'address_1' => $_POST['diachi'],
                    'phone' => $_POST['sodienthoai']
                ],
                'line_items' => [
                    [
                        'product_id' => 79,
                        'name'      => $_POST['congviec'],
                        'quantity' => 1
                    ]
                ],
                'meta_data' => [
                    [
                        'key' => '_billing_wooccm11',
                        'value' => $_POST['mabaohanh']
                    ]
                ]
            ];
            if((core_woo()->post('orders', $data))){
                ?>
                <h3>Thành công!!!!</h3>
                <?php
            };
        }
}
function dienthoai(){
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 10,
        'product_cat'   => 'dien-thoai'
    );

    $loop = new WP_Query( $args );
    ?>
    <h2 class="title-muahang">Mua hàng</h2>
    <table class="table_muahang_dienthoai">
        <thead>
            <th>STT</th>
            <th>Sản phẩm</th>
            <th>Mô tả</th>
            <th>Giá</th>
            <th>Mua</th>
        </thead>
        <tbody>
            <?php
            $i = 0;
            $tong = 0;
        while ( $loop->have_posts() ) : $loop->the_post();
            global $product;
                $i++;
                ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $product->get_title(); ?></td>
                <td><?php echo $product->get_description(); ?></td>
                <td><?php echo $product->get_price(); ?></td>
                <td><a href="?page=mua-hang&action=muadienthoai&id=<?php echo $product->get_id()?>">Mua hàng</a></td>
            </tr>
            <?php
        endwhile;
        ?>
        </tbody>
    </table>
    <?php
        wp_reset_query();
}
function order(){
    ?>
    <div class="row">
        <div class="col-md-7">
            <?php dienthoai(); ?>
        </div>
        <div class="col-md-4">
            <?php 
                if(isset($_GET['action'])){
                    $action = $_GET['action'];
                    switch ($action) {
                        case 'muadienthoai':
                            # code...
                            if(isset($_GET['id'])){
                                $id = $_GET['id'];
                                dienthoaimua($id);
                            }
                        break;
                    }
                }
            ?>
        </div>
    </div>
    <?php
}

function core_woo(){
    $woocommerce = new Client(
        'http://localhost/quanlikho', // Your store URL
        'ck_dd7a11ae37feafc194e86f85be720c33e97d24b1', // Your consumer key
        'cs_b8ec98bc5ef349a5d42ec7bf49a699953f98ada4', // Your consumer secret
        [
            'wp_api' => true, // Enable the WP REST API integration
            'version' => 'wc/v3' // WooCommerce WP REST API version
        ]
    );
    return $woocommerce;
}
function dienthoaimua($id){
    ?>
<h2 class="title-order">Đặt hàng</h2>
<form method="post" class="order-form">
    <div class="thongtin">
        <label for="ho">
            <p>Họ</p>
            <input type="text" name="ho" id="ho" required>
        </label>
        <label for="ten">
            <p>Tên</p>
            <input type="text" name="ten" id="ten" required>
        </label>
    </div>
    <label for="diachi">
        <p>Địa chỉ</p>
        <input type="text" name="diachi" id="diachi" required>
    </label>
    <label for="sodienthoai">
        <p>Số điện thoại</p>
        <input type="number" name="sodienthoai" id="sodienthoai" required>
    </label>
    <label for="mabaohanh" id="baohanh">
        <p>Mã bảo hành</p>
        <input type="text" name="mabaohanh" id="mabaohanh">
    </label>
    <div class="thongtin-don">
        <?php 
            $product_detail = wc_get_product( $id );
        ?>
        <label for="tensanpham">
            <p>Tên sản phẩm</p>
            <input type="text" name="tensanpham" id="tensanpham" value="<?php echo $product_detail->get_name(); ?>" disabled>
        </label>
        <label for="giasanpham">
            <p>Giá sản phẩm</p>
            <input type="text" name="giasanpham" id="giasanpham" value="<?php echo $product_detail->get_price(); ?>" disabled>
        </label>
        <label for="soluong">
            <p>Số lượng</p>
            <input type="number" name="soluong" id="soluong" required>
        </label>
    </div>
    <input type="hidden" name="id_product" value="<?php echo $id; ?>">
    <input type="submit" name="dathang" value="Đặt hàng">
</form>
<?php
if(isset($_POST['dathang'])){
    $data = [
        'status' => 'completed',
        'billing' => [
            'first_name' => $_POST['ho'],
            'last_name' => $_POST['ten'],
            'address_1' => $_POST['diachi'],
            'phone' => $_POST['sodienthoai']
        ],
        'shipping' => [
            'first_name' => $_POST['ho'],
            'last_name' => $_POST['ten'],
            'address_1' => $_POST['diachi'],
            'phone' => $_POST['sodienthoai']
        ],
        'line_items' => [
            [
                'product_id' => $_POST['id_product'],
                'quantity' => $_POST['soluong']
            ]
        ],
        'meta_data' => [
            [
                'key' => '_billing_wooccm11',
                'value' => $_POST['mabaohanh']
            ]
        ]
    ];
    if(core_woo()->post('orders', $data)){
        ?>
        <h3>Thành công!!!!</h3>
        <?php
    };
}
}


add_action('admin_menu', 'custom_order');
add_action('admin_menu', 'add_admin_submenu');