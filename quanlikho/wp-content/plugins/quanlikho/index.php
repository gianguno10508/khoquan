<?php 
/**
 * @package Quản lí kho
 * @version 1.0.0
 */
/*
Plugin Name: Quản lí kho
Plugin URI: http://wordpress.org/plugins/
Description: Quản lí kho
Author: Dev
Version: 1.0.0
Author URI: http://ma.tt/
*/

require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;
function core_woo_ql(){
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

function plugin_scripts_quanlikho() {
    wp_enqueue_style('style_plugin_quanlikho', plugins_url('quanlikho/styles.css'), array(), null);
    //wp_enqueue_script('js_plugin', plugins_url('quanli/index.js'), array(), null);
    wp_enqueue_style('style_plugin_quanlikho', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css', array(), null);
    //wp_enqueue_script('jquery_plugin', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', array(), null);
}
add_action( 'admin_enqueue_scripts', 'plugin_scripts_quanlikho' );

function add_admin_menu_quanlikho()
{
    add_menu_page(
        'Quản lí kho', 
        'Quản lí kho', 
        'manage_options', 
        'quan-li-kho', 
        'quanlikhomain', 
        '', 
        '57'
    );
}
add_action('admin_menu', 'add_admin_menu_quanlikho');
add_action('admin_menu', 'add_admin_submenu_quanlikho');
function add_admin_submenu_quanlikho()
{
    add_submenu_page ('quan-li-kho', 'Thêm mới', 'Thêm mới', 'manage_options', 'them-moi', 'them' );
}
function quanlikhomain(){
    $array_product = core_woo_ql()->get('products');
    $saphet = 0;
    $het = 0;
    foreach ($array_product as $key => $value) {
        if($value->stock_quantity < 10 && $value->stock_quantity > 0 ){
            $saphet++;
        }elseif($value->stock_quantity == 0){
            $het++;
        }
    }
    ?>
        <h2 class="title_quanlikho">Quản lí kho</h2>
        <div class="thongbao">
            <?php
                if($saphet > 0){
                ?>
                    <h5 class="saphet">Bạn có <?php echo $saphet ?> sản phẩm <span style="color: #8e5a12">sắp hết hàng</span>!!!</h5>
                <?php
                }
                if($saphet > 0){
                ?>
                    <h5 class="het">Bạn có <?php echo $saphet ?> sản phẩm <span style="color: red">hết hàng</span>!!!</h5>
                <?php
                }
            ?>
        </div>
    <?php
        main($array_product);
            if(isset($_GET['action'])){
                $action = $_GET['action'];
                switch ($action) {
                    case 'suasanpham':
                        # code...
                        if(isset($_GET['id_product'])){
                            $id = $_GET['id_product'];
                            $list_product_id = core_woo_ql()->get('products/'.$id);
                            ?>
    <h2 class="title_suasanpham">Sửa sản phẩm</h2>
    <form method="POST" class="themsanpham">
        <label for="tensanphamthem" id="tensanphamthem">
            <p>Tên sản phẩm</p>
            <input type="text" name="tensanpham" id="tensanpham" value="<?php echo $list_product_id['name']; ?>">
        </label>
        <label for="danhmucthem" id="danhmucthem">
            <p>Danh mục</p>
               <?php 
                    foreach($arr_product as $value):
                        if($value->categories[0]->slug == "dien-thoai"){
                            ?>
                            <input type="text" name="danhmuc" id="danhmuc" value="<?php echo $value->categories[0]->name ?>" disabled>
                            <input type="hidden" name="danhmuc_id" value="<?php echo $value->categories[0]->id ?>">
                            <?php
                            break;
                        }
                    endforeach; 
                ?>
            </label>
            <label for="giathem" id="giathem">
                <p>Giá</p>
                <input type="number" name="gia" id="giathem">
            </label>
            <label for="soluongcon" id="soluongcon">
                <p>Số lượng</p>
                <input type="number" name="soluongcon" id="soluongcon">
            </label>
            <label for="motathem" id="motathem">
                <p>Mô tả</p>
                <input type="text" name="mota" id="mota">
            </label>
            <input type="submit" value="Thêm" class="them" name="them">
        </form>
        <?php
        if(isset($_POST['them'])){
            $data = [
                'name' => $_POST['tensanpham'],
                'regular_price' => $_POST['gia'],
                'description' => $_POST['mota'],
                'manage_stock'=> true,
                'stock_quantity' => $_POST['soluongcon'],
                'categories' => [
                    [
                        'id' => $_POST['danhmuc_id']
                    ]
                ],
            ];
            if(core_woo_ql()->post('products', $data)){
                ?>
                <h5 class="thongbao">Thành công!!!</h5>
                <?php
            }
            // echo '<pre>';
            // print_r($_POST);
        }
                            ?>
                            </div>
                            <?php
                        }
                        break;
                    case 'xoasanpham':
                        # code...
                        if(isset($_GET['id_product'])){
                            $id = $_GET['id_product'];
                            if(core_woo_ql()->delete('products/'.$id, ['force' => true])){
                                header("Location: http://localhost/quanlikho/wp-admin/admin.php?page=quan-li-kho");
                            }
                        }
                        break;
                }
            }
}
function main($array_product){
    ?>
            <form action="" class="quanlikho">
            <table class="table_quanlikho">
                <thead>
                    <th>STT</th>
                    <th>Sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Mô tả</th>
                    <th>Giá</th>
                    <th>Số lượng còn lại trong kho</th>
                    <th>Tình trạng</th>
                    <th>Hành động</th>
                </thead>
                <tbody>
                <?php
                // echo '<pre>';
                // print_r($array_product);
                // die;
                $i = 0;
                $tong = 0;
                foreach ($array_product as $key => $value) {
                    # code...
                    $i++;
                    ?>
                    <tr>
                        <?php 
                            if($value->categories[0]->slug == 'sua-chua'){
                                ?>
                                <td class="sua-chua"><?php echo $i; ?></td>
                                    <td><?php echo ($value->name); ?></td>
                                    <td><?php echo $value->categories[0]->name ?></td>
                                    <td><?php echo strip_tags($value->description); ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                <?php
                            }else{
                                ?>
                                <td><?php echo $i; ?></td>
                                <td><input type="text" name="tensanpham" id="tensanpham" value="<?php echo strip_tags($value->name); ?>"></td>
                                <td><?php echo $value->categories[0]->name ?></td>
                                <td><input type="text" name="motasanpham" id="motasanpham" value="<?php echo strip_tags($value->description); ?>"></td>
                                <td><input type="number" name="giasanpham" id="giasanpham" value="<?php echo $value->price; ?>"></td>
                                <td><input type="number" name="soluong" id="soluong" value="<?php echo $value->stock_quantity; ?>"></td>
                                <?php
                                    if($value->stock_quantity >= 10){
                                    ?>
                                        <td style="background-color: green">Còn hàng</td>
                                    <?php
                                }elseif($value->stock_quantity < 10 && $value->stock_quantity > 0 ){
                                    ?>
                                        <td style="background-color: yellow">Sắp hết hàng</td>
                                    <?php
                                }else{
                                    ?>
                                        <td style="background-color: red">Hết hàng</td>
                                    <?php
                                }
                                ?>
                                <td>
                                    <a href="?page=quan-li-kho&action=suasanpham&id_product=<?php echo $value->id; ?>">Sửa</a>
                                    <a href="?page=quan-li-kho&action=xoasanpham&id_product=<?php echo $value->id; ?>">Xóa</a>
                                </td>
                                <?php
                            }
                        ?>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </form>
    <?php
}
function them(){
    $arr_product = core_woo_ql()->get('products');
    ?>
    <h2 class="title_themsanpham">Thêm sản phẩm</h2>
    <form method="POST" class="themsanpham">
        <label for="tensanphamthem" id="tensanphamthem">
            <p>Tên sản phẩm</p>
            <input type="text" name="tensanpham" id="tensanpham">
        </label>
        <label for="danhmucthem" id="danhmucthem">
            <p>Danh mục</p>
               <?php 
                    foreach($arr_product as $value):
                        if($value->categories[0]->slug == "dien-thoai"){
                            ?>
                            <input type="text" name="danhmuc" id="danhmuc" value="<?php echo $value->categories[0]->name ?>" disabled>
                            <input type="hidden" name="danhmuc_id" value="<?php echo $value->categories[0]->id ?>">
                            <?php
                            break;
                        }
                    endforeach; 
                ?>
        </label>
        <label for="giathem" id="giathem">
            <p>Giá</p>
            <input type="number" name="gia" id="giathem">
        </label>
        <label for="soluongcon" id="soluongcon">
            <p>Số lượng</p>
            <input type="number" name="soluongcon" id="soluongcon">
        </label>
        <label for="motathem" id="motathem">
            <p>Mô tả</p>
            <input type="text" name="mota" id="mota">
        </label>
        <input type="submit" value="Thêm" class="them" name="them">
    </form>
    <?php
    if(isset($_POST['them'])){
        $data = [
            'name' => $_POST['tensanpham'],
            'regular_price' => $_POST['gia'],
            'description' => $_POST['mota'],
            'manage_stock'=> true,
            'stock_quantity' => $_POST['soluongcon'],
            'categories' => [
                [
                    'id' => $_POST['danhmuc_id']
                ]
            ],
        ];
        if(core_woo_ql()->post('products', $data)){
            ?>
            <h5 class="thongbao">Thành công!!!</h5>
            <?php
        }
        // echo '<pre>';
        // print_r($_POST);
    }
}