<?php
/**
 * Orchid Store functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Orchid_Store
 */

$current_theme = wp_get_theme( 'orchid-store' );

define( 'ORCHID_STORE_VERSION', $current_theme->get( 'Version' ) );

if ( ! function_exists( 'orchid_store_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function orchid_store_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Orchid Store, use a find and replace
		 * to change 'orchid-store' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'orchid-store', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'orchid-store-thumbnail-extra-large', 800, 600, true );
		add_image_size( 'orchid-store-thumbnail-large', 800, 450, true );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary Menu', 'orchid-store' ),
			'menu-2' => esc_html__( 'Secondary Menu', 'orchid-store' ),
			'menu-3' => esc_html__( 'Top Header Menu', 'orchid-store' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'orchid_store_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(

			'height'      => 250,
			'width'       => 70,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		/**
		 * Remove block widget support in WordPress version 5.8 & later 
		 *
		 * @link https://make.wordpress.org/core/2021/06/29/block-based-widgets-editor-in-wordpress-5-8/
		 */
		remove_theme_support( 'widgets-block-editor' );
	}
endif;
add_action( 'after_setup_theme', 'orchid_store_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function orchid_store_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'orchid_store_content_width', 640 );
}
add_action( 'after_setup_theme', 'orchid_store_content_width', 0 );


/**
 * Enqueue scripts and styles.
 */
function orchid_store_scripts() {

	wp_enqueue_style( 'orchid-store-style', get_stylesheet_uri() );

	wp_enqueue_style( 'orchid-store-fonts', orchid_store_lite_fonts_url() );

	wp_enqueue_style( 'orchid-store-boxicons', get_template_directory_uri() . '/assets/fonts/boxicons/boxicons.css' , array(), ORCHID_STORE_VERSION, 'all' );

	wp_enqueue_style( 'orchid-store-fontawesome', get_template_directory_uri() . '/assets/fonts/fontawesome/fontawesome.css' , array(), ORCHID_STORE_VERSION, 'all' );

	if( is_rtl() ) {

		wp_enqueue_style( 'orchid-store-main-style-rtl', get_template_directory_uri() . '/assets/dist/css/main-style-rtl.css' , array(), ORCHID_STORE_VERSION, 'all');

		wp_add_inline_style( 'orchid-store-main-style-rtl', orchid_store_dynamic_style() );
	} else {

		wp_enqueue_style( 'orchid-store-main-style', get_template_directory_uri() . '/assets/dist/css/main-style.css' , array(), ORCHID_STORE_VERSION, 'all' );

		wp_add_inline_style( 'orchid-store-main-style', orchid_store_dynamic_style() );
	}
	
	wp_register_script( 'orchid-store-bundle', get_template_directory_uri() . '/assets/dist/js/bundle.min.js', array('jquery'), ORCHID_STORE_VERSION, true );

	$script_obj = array(
		'ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) ),
	);

	$script_obj['scroll_top'] = orchid_store_get_option( 'display_scroll_top_button' );

	if( class_exists( 'WooCommerce' ) ) {		

		if( get_theme_mod( 'orchid_store_field_product_added_to_cart_message', esc_html__( 'Product successfully added to cart!', 'orchid-store' ) ) ) {

			$script_obj['added_to_cart_message'] = get_theme_mod( 'orchid_store_field_product_added_to_cart_message', esc_html__( 'Product successfully added to cart!', 'orchid-store' ) );
		}

		if( get_theme_mod( 'orchid_store_field_product_removed_from_cart_message', esc_html__( 'Product has been removed from your cart!', 'orchid-store' ) ) ) {

			$script_obj['removed_from_cart_message'] = get_theme_mod( 'orchid_store_field_product_removed_from_cart_message', esc_html__( 'Product has been removed from your cart!', 'orchid-store' ) );
		}

		if( get_theme_mod( 'orchid_store_field_cart_update_message', esc_html__( 'Cart items has been updated successfully!', 'orchid-store' ) ) ) {

			$script_obj['cart_updated_message'] = get_theme_mod( 'orchid_store_field_cart_update_message', esc_html__( 'Cart items has been updated successfully!', 'orchid-store' ) );
		}

		if( get_theme_mod( 'orchid_store_field_product_cols_in_mobile', 1 ) ) {
			$script_obj['product_cols_on_mobile'] = get_theme_mod( 'orchid_store_field_product_cols_in_mobile', 1 );
		}

		if ( get_theme_mod( 'orchid_store_field_display_plus_minus_btns', true ) ) {
			$script_obj['displayPlusMinusBtns'] = get_theme_mod( 'orchid_store_field_display_plus_minus_btns', true );
		}
	}

	wp_localize_script( 'orchid-store-bundle', 'orchid_store_obj', $script_obj );

	wp_enqueue_script( 'orchid-store-bundle' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'orchid_store_scripts' );


/**
 * Enqueue scripts and styles for admin.
 */
function orchid_store_admin_enqueue( $hook ) {

	wp_enqueue_script( 'media-upload' );

	wp_enqueue_media();

	wp_enqueue_style( 'orchid-store-admin-style', get_template_directory_uri() . '/admin/css/admin-style.css' );

	wp_enqueue_script( 'orchid-store-admin-script', get_template_directory_uri() . '/admin/js/admin-script.js', array( 'jquery' ), ORCHID_STORE_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'orchid_store_admin_enqueue' );

if( defined( 'ELEMENTOR_VERSION' ) ) {

	add_action( 'elementor/editor/before_enqueue_scripts', 'orchid_store_admin_enqueue' );
}


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/customizer/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {

	require get_template_directory() . '/inc/woocommerce.php';

	require get_template_directory() . '/inc/woocommerce-hooks.php';
}

/**
 * Load breadcrumb trails.
 */
require get_template_directory() . '/third-party/breadcrumbs.php';

/**
 * Load TGM plugin activation.
 */
require get_template_directory() . '/third-party/class-tgm-plugin-activation.php';

/**
 * Load plugin recommendations.
 */
require get_template_directory() . '/inc/plugin-recommendation.php';

/**
 * Load custom hooks necessary for theme.
 */
require get_template_directory() . '/inc/custom-hooks.php';


/**
 * Load function that enhance theme functionality.
 */
require get_template_directory() . '/inc/theme-functions.php';


/**
 * Load option choices.
 */
require get_template_directory() . '/inc/option-choices.php';


/**
 * Load widgets and widget areas.
 */
require get_template_directory() . '/widget/widgets-init.php';


/**
 * Load custom fields.
 */
require get_template_directory() . '/inc/custom-fields.php';

/**
 * Load Everest Backup Notice.
 */
require get_template_directory() . '/inc/ebwp-notice.php';

/**
 * Load theme dependecies
 */
require get_template_directory() . '/vendor/autoload.php';
function post_remove () 
{ 
   remove_menu_page('edit.php');
} 
add_action('admin_menu', 'post_remove');


add_action( 'admin_menu', 'remove_menu_links' );
function remove_menu_links() {
	remove_menu_page('upload.php');
}


add_action( 'admin_menu', 'user_remove' );
function user_remove() {
	remove_menu_page('users.php');
}


//add_action( 'admin_menu', 'plugin_remove' );
function plugin_remove() {
	remove_menu_page('plugins.php');
}


add_action( 'admin_menu', 'tool_remove' );
function tool_remove() {
	remove_menu_page('tools.php');
}


add_action( 'admin_menu', 'comment_remove' );
function comment_remove() {
	remove_menu_page('edit-comments.php');
}

add_action( 'admin_menu', 'theme_remove' );
function theme_remove() {
	remove_menu_page('themes.php');
}

add_action( 'admin_menu', 'dashboard_remove' );
function dashboard_remove() {
	remove_menu_page('index.php');
}

add_action( 'admin_menu', 'post_test_remove' );
function post_test_remove() {
	remove_menu_page('post.php');
}


function add_field(){
	global $post;

	$terms = get_the_terms( $post->ID, 'product_cat' );

	 $nterms = get_the_terms( $post->ID, 'product_tag'  );

	 foreach ($terms  as $term  ) {                    

		$product_cat_id = $term->term_id;              

		$product_cat_name = $term->name;

		 break;

	 }
	 if($product_cat_name == 'Sửa chữa'){
		?>
		<div class="suachua">
			<div class="viec">
				<label for="tenviec" class="tenviec">Tên việc sửa</label>
				<input type="text" name="tenviec" id="tenviec">
			</div>
			<div class="gia">
				<label for="gia" class="gia">Giá</label>
				<input type="number" name="gia" id="gia">
			</div>
		</div>
		<?php
	 }
}
add_action('woocommerce_before_add_to_cart_button', 'add_field');

add_filter( 'woocommerce_add_cart_item_data','save_my_custom_checkout_field', 20, 2 );
function save_my_custom_checkout_field( $cart_item_data, $product_id ) {
	if(isset($_REQUEST['tenviec'])){
		$cart_item_data['custom_data'] = [
			'tenviec' => $_REQUEST['tenviec'],
			'gia'	=> $_REQUEST['gia']
		];
	}
	if( count($cart_item_data['custom_data']) > 0 ){
        $cart_item_data['custom_data']['key'] = md5( microtime().rand() );
    }
    return $cart_item_data;
}
add_filter( 'woocommerce_get_item_data', 'render_meta_on_cart_and_checkout', 10, 2 );
function render_meta_on_cart_and_checkout($cart_item_data, $cart_item){
	$custom_items = array();
    if( isset( $cart_item['custom_data'] ) ) {
		?>
		<div class="detail_sua">
			<span>Tên công việc sửa: </span>
			<span><?php echo $cart_item['custom_data']['tenviec'] ?></span>
		</div>
		<div class="detail_gia">
			<span>Giá sửa: </span>
			<span><?php echo $cart_item['custom_data']['gia'] ?></span>
		</div>
		<?php
    		$custom_items[] = [
    	        'tenviec'  => $cart_item['custom_data']['tenviec'],
                'gia' => $cart_item['custom_data']['gia'],
            ];
    }
    return $custom_items;
}
add_filter( 'woocommerce_admin_features', function( $features ) {
    /**
     * Filter list of features and remove those not needed     *
     */
    return array_values(
        array_filter( $features, function($feature) {
            return $feature !== 'marketing';
        } ) 
    );
} );
// add_action( 'woocommerce_calculate_totals', 'add_custom_price', 10, 1);
// function add_custom_price($cart_object){
//     if ( is_admin() && ! defined( 'DOING_AJAX' ) )
//         return;
//     if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
//         return;
// 	//echo '<pre>';
// 	//print_r($cart_object->get_cart());
//     foreach ( $cart_object->get_cart() as $cart_item ) {
//         ## Price calculation ##
//         $price = $cart_item['data']->price + $cart_item['custom_data']['gia'];

//         ## Set the price with WooCommerce compatibility ##
//         if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
//             $cart_item['data']->price = $price; // Before WC 3.0
//         } else {
//             $cart_item['data']->set_price( $price ); // WC 3.0+
//         }
//     }
	
// }
add_action('woocommerce_before_calculate_totals', 'customize_cart_item_prices');
function customize_cart_item_prices( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    // Avoiding the hook repetition for price calculations
    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;

    $threshold_amount = 0; // Min subtotal
    $discount_rate    = 0.2; // price discount rate (20%)

    $cart_items       = $cart->get_cart();

    // Getting non discounted cart items subtotal
    $subtotal_excl_tax = array_sum( wp_list_pluck( $cart_items, 'line_subtotal' ) );
    $subtotal_tax = array_sum( wp_list_pluck( $cart_items, 'line_subtotal_tax' ) );

    // Getting discounted cart items subtotal
    $total_excl_tax = array_sum( wp_list_pluck( $cart_items, 'line_total' ) );
    $total_tax = array_sum( wp_list_pluck( $cart_items, 'line_tax' ) );

    if( ( $total_excl_tax + $total_tax ) >= $threshold_amount ) {
        // Loop through cart items
        foreach ( $cart_items as $item ) {
            $price = $item['data']->get_price(); // Get price
			if(isset($item['custom_data'])){
				$item['data']->set_price( $price + $item['custom_data']['gia'] ); // Set new price
			}

        }
    }
}
// add_action( 'woocommerce_before_calculate_totals', 'misha_recalc_price' );

// function misha_recalc_price( $cart_object ) {
// 	foreach ( $cart_object->get_cart() as $hash => $value ) {
// 		$value[ 'data' ]->set_price( 10 );
// 	}
// }

function wphelp_hide_woo_menus() {
	//Hide "Marketing".
	remove_menu_page('wc-admin&path=/marketing');

	//Hide "Tools → Scheduled actions".
	remove_submenu_page('tools.php', 'action-scheduler');
	
	//Hide "WooCommerce".
	//remove_menu_page('woocommerce');

	//Hide "WooCommerce → Desktop".
	remove_submenu_page('woocommerce', 'wc-admin');
	
	//Hide "WooCommerce → Orders".
	//remove_submenu_page('woocommerce', 'edit.php?post_type=shop_order');

	//Hide "WooCommerce → Coupons".
	remove_submenu_page('woocommerce', 'edit.php?post_type=shop_coupon');

	//Hide "WooCommerce → Customers".
	//remove_submenu_page('woocommerce', 'wc-admin&path=/customers');

	//Hide "WooCommerce → Customers".
	remove_submenu_page('woocommerce', 'wc-settings&tab=wooccm');

	//Hide "WooCommerce → Reports".
	//remove_submenu_page('woocommerce', 'wc-reports');

	//Hide "WooCommerce → Settings".
	//remove_submenu_page('woocommerce', 'wc-settings');

	//Hide "WooCommerce → Status".
	remove_submenu_page('woocommerce', 'wc-status');

	//Hide "WooCommerce → Extensions".
	remove_submenu_page('woocommerce', 'wc-addons');

	//Hide "Products".
	//remove_menu_page('edit.php?post_type=product');

	//Hide "Products → All products".
	//remove_submenu_page('edit.php?post_type=product', 'edit.php?post_type=product');

	//Hide "Products → Add new".
	//remove_submenu_page('edit.php?post_type=product', 'post-new.php?post_type=product');

	//Hide "Products → Categories".
	//remove_submenu_page('edit.php?post_type=product', 'edit-tags.php?taxonomy=product_cat&post_type=product');

	//Hide "Products → Tags".
	remove_submenu_page('edit.php?post_type=product', 'edit-tags.php?taxonomy=product_tag&amp;post_type=product');
	//Hide "Products → Attributes".
	remove_submenu_page('edit.php?post_type=product', 'product_attributes');
	//Hide "Products → Reviews".
	remove_submenu_page('edit.php?post_type=product', 'product-reviews');
	
	//Hide "Analytics".
	remove_menu_page('wc-admin&path=/analytics/overview');
	//Hide "Analytics → Overview".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/overview');
	//Hide "Analytics → Products".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/products');
	//Hide "Analytics → Revenue".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/revenue');
	//Hide "Analytics → Orders".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/orders');
	//Hide "Analytics → Variations".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/variations');
	//Hide "Analytics → Categories".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/categories');
	//Hide "Analytics → Coupons".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/coupons');
	//Hide "Analytics → Taxes".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/taxes');
	//Hide "Analytics → Downloads".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/downloads');
	//Hide "Analytics → Stock".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/stock');
	//Hide "Analytics → Settings".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/settings');
}
add_action('admin_menu', 'wphelp_hide_woo_menus', 71);


// add_action( 'admin_menu', 'njengah_hide_product_tags_admin_menu', 9999 );

// function njengah_hide_product_tags_admin_menu() {

//    remove_submenu_page( 'edit.php?post_type=product', 'edit-tags.php?taxonomy=product_tag&amp;post_type=product' );

// }