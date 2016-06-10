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

        }

    }

}

$iconic_woo_recently_bought = new Iconic_WooCommerce_Recently_Bought();