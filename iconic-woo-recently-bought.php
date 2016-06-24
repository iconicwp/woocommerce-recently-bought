<?php
/**
 * Plugin Name: WooCommerce Recently Bought
 * Plugin URI: https://github.com/iconicwp/woocommerce-recently-bought
 * Description: A simple plugin that display recently bought items to your customer in a slide in pop-up.
 * Version: 1.0.0
 * Author: Iconic <support@iconicwp.com>
 * Author URI: https://iconicwp.com
 * Text Domain: iconic-woo-recently-bought
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Iconic_WooCommerce_Recently_Bought {

    /**
     * Long name
     *
     * @since 1.0.0
     * @access protected
     * @var string $name
     */
    protected $name = "WooCommerce Recently Bought";

    /**
     * Short name
     *
     * @since 1.0.0
     * @access protected
     * @var string $shortname
     */
    protected $shortname = "Recently Bought";

    /**
     * Slug - Hyphen
     *
     * @since 1.0.0
     * @access public
     * @var string $slug
     */
    public $slug = "iconic-woo-recently-bought";

    /**
     * Slug - Underscore
     *
     * @since 1.0.0
     * @access public
     * @var string $slug_alt
     */
    public $slug_alt;

    /**
     * Plugin path
     *
     * @since 1.0.0
     * @access public
     * @var string $plugin_path trailing slash
     */
    public $plugin_path;

    /**
     * Plugin URL
     *
     * @since 1.0.0
     * @access protected
     * @var string $plugin_url trailing slash
     */
    protected $plugin_url;

    /**
     * Construct
     */
    public function __construct() {

        $this->textdomain();
        $this->set_constants();

        add_action( 'init', array( $this, 'initiate_hook' ) );

    }

    /**
     * Load textdomain
     */
    public function textdomain() {

        load_plugin_textdomain( 'iconic-woo-recently-bought', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    }

    /**
     * Set constants
     */
    public function set_constants() {

        $this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );
        $this->slug_alt = str_replace( '-', '_', $this->slug );

    }

    /**
     * Init
     */
    public function initiate_hook() {

        if(is_admin()) {

        } else {

            $this->get_recent_product_purchases();

        }

    }

    /**
     * Get recent product purchases
     *
     * @param int $limit
     * @return array
     */
    public function get_recent_product_purchases( $limit = 5 ) {

        $products = array();

        $line_items = $this->get_line_items( $limit );

        if( empty( $line_items ) )
            return $products;

        foreach( $line_items as $line_item ) {

            $product_info = $this->get_product_info( $line_item->order_item_id );
            $order_info = $this->get_order_info( $line_item->order_id );

            if( !$product_info || !$order_info )
                continue;

            $products[] = array_merge( $product_info, $order_info );

        }

        error_log( print_r( $products, true ) );

        return $products;

    }

    /**
     * Get line items
     *
     * @param int $limit
     * @return array
     */
    public function get_line_items( $limit ) {

        global $wpdb;

        $line_items = $wpdb->get_results( $wpdb->prepare("
            SELECT *
            FROM {$wpdb->prefix}woocommerce_order_items
            WHERE order_item_type = 'line_item'
            GROUP BY order_id
            LIMIT %d",
            $limit
        ), OBJECT );

        return $line_items;

    }

    /**
     * Get product by order item ID
     *
     * @param int $order_item_id
     * @return bool|obj
     */
    public function get_product_by_order_item_id( $order_item_id ) {

        global $wpdb;

        $product_id = $wpdb->get_var( $wpdb->prepare("
            SELECT meta_value
            FROM {$wpdb->prefix}woocommerce_order_itemmeta
            WHERE order_item_id = %d
            AND meta_key = '_product_id'",
            $order_item_id
        ) );

        if( !$product_id )
            return false;

        $product = wc_get_product( $product_id );

        return $product;

    }

    /**
     * Get product info by ID
     *
     * @param int $product_id
     * @retrun bool|array
     */
    public function get_product_info( $product_id ) {

        $product = $this->get_product_by_order_item_id( $product_id );

        if( !$product )
            return false;

        return array(
            'product_name' => $product->get_title(),
            'product_url'  => $product->get_permalink(),
            'product_image' => $product->get_image('shop_thumbnail'),
        );

    }

    /**
     * Get order info by ID
     *
     * @param int $order_id
     * @return bool|array
     */
    public function get_order_info( $order_id ) {

        $customer_name = get_post_meta( $order_id, '_billing_first_name', true );
        $customer_city = get_post_meta( $order_id, '_billing_city', true );
        $customer_country = get_post_meta( $order_id, '_billing_country', true );
        $location = array();

        if( !$customer_name || !$customer_city || !$customer_country )
            return false;

        if( $customer_city ) { $location[] = $customer_city; }
        if( $customer_country ) { $location[] = $customer_country; }

        return array(
            'customer_name' => $customer_name,
            'customer_location' => implode(', ', $location)
        );

    }

}

$iconic_woo_recently_bought = new Iconic_WooCommerce_Recently_Bought();