<?php 
/**
 * @package Chấm công
 * @version 1.0.0
 */
/*
Plugin Name: Chấm công
Plugin URI: http://wordpress.org/plugins/
Description: User manager
Author: Dev
Version: 1.0.0
Author URI: http://ma.tt/
*/
function test() {
    wp_enqueue_style('plugin_style', plugins_url('quanli/styles.css'), array(), null);
    wp_enqueue_script('plugin_js', plugins_url('quanli/index.js'), array('jquery'), null);
    wp_enqueue_style('boostrap_plugin', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css', array(), null);
}
add_action( 'admin_enqueue_scripts', 'test' );

function add_admin_menu()
{
    add_menu_page (
        'Chấm công', 
        'Chấm công', 
        'manage_options', 
        'cham-cong', 
        'show_plugin_options', 
        '', 
        '57'
    );
}
function show_plugin_options()
{
    ?>
    <h2 class="title_cham">Chấm công</h2>
    <?php
        if(isset($_GET['action'])){
            $action = $_GET['action'];
            switch ($action) {
                case 'edit':
                    # code...
                    if(isset($_GET['id'])){
                        $id = $_GET['id'];
                        global $wpdb; // Biến toàn cục lớp $wpdb được sử dụng trong khi tương tác với databse wordpress
                        $table = $wpdb->prefix . 'chamcong'; // Khai báo bảng cần lấy
                        $sql = "SELECT * FROM {$table} WHERE id = '$id'"; // cậu sql query 
                        $arr_id = $wpdb->get_results( $wpdb->prepare($sql), ARRAY_A);
                        ?>
                        <div class="row">
                            <div class="col-md-5">
                            <form method="post">
                                <label for="text">Tìm kiếm</label>
                                <input type="text" name="key" id="key">
                                <input type="submit" name='find' value="Tìm">
                            </form>
                            <?php 
                                if(isset($_POST['find'])){
                                    show_search();
                                }
                            ?>
                        </div>
                        <div class="col-md-6">
                            <form method="POST" class="form-suacongviec">
                                <div class="row">
                                    <label for="ten">Tên</label>
                                    <input type="text" name="ten" id="ten" value="<?php echo $arr_id[0]['ten'] ?>" required>
                                    <label for="congviec">Công việc</label>
                                    <input type="text" name="congviec" id="congviec" value="<?php echo $arr_id[0]['tenviec'] ?>" required>
                                    <label for="ngay">Ngày</label>
                                    <input type="date" name="ngay" id="ngay" value="<?php echo $arr_id[0]['ngaylam'] ?>" required>
                                    <label for="gia">Giá tiền</label>
                                    <input type="number" name="gia" id="gia" value="<?php echo $arr_id[0]['gia'] ?>" required>
                                    <input type="submit" name='ok' value="OK" id="ok">
                                </div>
                            </form>
                            <?php
                            if(isset($_POST['ok'])){
                                global $wpdb;
                                $table_name = $wpdb->prefix . 'chamcong';      
                                $wpdb->query("UPDATE $table_name SET ten='$_POST[ten]', tenviec='$_POST[congviec]', ngaylam='$_POST[ngay]', gia='$_POST[gia]' WHERE id = '$id'"); 
                                header("Location: http://localhost/quanlikho/wp-admin/admin.php?page=cham-cong");
                            }
                        ?>
                        </div>
                        <?php
                    }
                    break;
                case 'delete':
                    # code...
                    if(isset($_GET['id'])){
                        $id = $_GET['id'];
                        global $wpdb; // Biến toàn cục lớp $wpdb được sử dụng trong khi tương tác với databse wordpress
                        $table = $wpdb->prefix . 'chamcong'; // Khai báo bảng cần lấy
                        $wpdb->query("DELETE FROM $table WHERE id = '$id'");
                        header("Location: http://localhost/quanlikho/wp-admin/admin.php?page=cham-cong");
                    }
                    break;
            }
        }else{
            ?>
            <div class="row cham">
                <div class="col-md-5">
                    <form method="post">
                        <label for="text">Tìm kiếm</label>
                        <input type="text" name="key" id="key">
                        <input type="submit" name='find' value="Tìm">
                    </form>
                    <?php 
                        if(isset($_POST['find'])){
                            show_search();
                        }
                    ?>
                </div>
                <div class="col-md-6">
                    <form method="POST" class="form-cham">
                        <div class="row">
                            <label for="ten">Tên</label>
                            <input type="text" name="ten" id="ten" required>
                            <label for="congviec">Công việc</label>
                            <input type="text" name="congviec" id="congviec" required>
                            <label for="ngay">Ngày</label>
                            <input type="date" name="ngay" id="ngay" required>
                            <label for="gia">Giá tiền</label>
                            <input type="number" name="gia" id="gia" required>
                            <input type="submit" name='ok' value="OK" id="ok">
                        </div>
                    </form>
                    <?php
                    show_table();
                    if(isset($_POST['ok'])){
                        global $wpdb;
                        $table_name = $wpdb->prefix . 'chamcong';      
                        $wpdb->query("INSERT INTO $table_name(id, ten, tenviec, ngaylam, gia) VALUES(NULL, '$_POST[ten]', '$_POST[congviec]', '$_POST[ngay]', '$_POST[gia]')"); 
                        show_table();
                    }
                ?>
            </div>
        </div>
        <?php
    }
}
function show_table(){
    global $wpdb; // Biến toàn cục lớp $wpdb được sử dụng trong khi tương tác với databse wordpress
    $limit = 10; // Số lượng record cần lấy
    $offset = 0; // Số lượng record bỏ qua
    $table = $wpdb->prefix . 'chamcong'; // Khai báo bảng cần lấy
    $sql = "SELECT * FROM {$table} ORDER BY ngaylam DESC"; // cậu sql query 
    $arr_chamcong = $wpdb->get_results( $wpdb->prepare($sql, $limit, $offset), ARRAY_A);
    ?>
    <?php
        ?>
    <table class="table_cham">
        <thead>
            <th>STT</th>
            <th>Tên</th>
            <th>Tên công việc</th>
            <th>Ngày làm</th>
            <th>Giá tiền</th>
            <th>Hành động</th>
        </thead>
        <tbody>
    <?php
        if(count($arr_chamcong) > 0){
            $i = 1;
            foreach ($arr_chamcong as $key => $value) {
                # code...
                $date = strtotime($value['ngaylam']);
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $value['ten']; ?></td>
                    <td><?php echo $value['tenviec']; ?></td>
                    <td><?php echo date('d-m-Y', $date); ?></td>
                    <td><?php echo number_format($value['gia'], 0, '', ','); ?></td>
                    <td>
                        <a onclick="return confirm('Bạn có  chắc chắn muốn sửa?')" href="?page=cham-cong&action=edit&id=<?php echo $value['id']?>">Sửa</a>
                        <a onclick="return confirm('Bạn có chắc chắn muốn xóa?')" href="?page=cham-cong&action=delete&id=<?php echo $value['id']?>">Xóa</a>
                    </td>
                </tr>
                <?php
                $i++;
            }
        }
        ?>
        </tbody>
    </table>
    <?php
}
function show_search(){
    global $wpdb; // Biến toàn cục lớp $wpdb được sử dụng trong khi tương tác với databse wordpress
    $limit = 10; // Số lượng record cần lấy
    $offset = 0; // Số lượng record bỏ qua
    $table = $wpdb->prefix . 'chamcong'; // Khai báo bảng cần lấy
    $sql = "SELECT * FROM {$table} WHERE ten LIKE '%$_POST[key]%'"; // cậu sql query 
    $arr_chamcong_find = $wpdb->get_results( $wpdb->prepare($sql, $limit, $offset), ARRAY_A);
    ?>
    <table class="table_search">
        <thead>
            <th>STT</th>
            <th>Tên</th>
            <th>Tên công việc</th>
            <th>Ngày làm</th>
            <th>Giá tiền</th>
            <th>Hành động</th>
        </thead>
        <tbody>
    <?php
        $i = 1;
        $sum = 0;
        foreach ($arr_chamcong_find as $key => $value) {
            # code...
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $value['ten']; ?></td>
                <td><?php echo $value['tenviec']; ?></td>
                <td><?php echo $value['ngaylam']; ?></td>
                <td><?php echo $value['gia']; ?></td>
                <td>
                    <a onclick="return confirm('Bạn có  chắc chắn muốn sửa?')" href="?page=cham-cong&action=edit&id=<?php echo $value['id']?>">Sửa</a>
				    <a onclick="return confirm('Bạn có chắc chắn muốn xóa?')" href="?page=cham-cong&action=delete&id=<?php echo $value['id']?>">Xóa</a>
                </td>
            </tr>
            <?php
            $sum += $value['gia'];
            $i++;
        }
        ?>
        </tbody>
    </table>
    <div class="tong-tien">
        <h3>Tổng tiền: <?php echo number_format($sum, 0, '', ','); ?></h3>
    </div>
    <?php
}
add_action('admin_menu', 'add_admin_menu');
