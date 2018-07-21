<?php
/*
Plugin Name:  Hearthstone Archive
Plugin URI:   http://localhost
Description:  The Hearthstone Card archive made for Battlecraft Website
Version:      0.1
Author:       Francesco Rho
Author URI:   https://rhofrances.co/
License:      MIT License
License URI:  https://opensource.org/licenses/MIT
Text Domain:  hearthstone-archive
Domain Path:  /languages
 */
defined('ABSPATH') or die('No script kiddies please!');

class Hearthstone_Archive
{
    public function __construct()
    {
        $this->plugin_domain = 'hearthstone-archive';
        $this->views_dir     = trailingslashit( dirname( __FILE__ ) ) . 'src/views';
        $this->version = '1.0';
        add_action('admin_menu', array($this, 'admin_menu'));
    }

    public function admin_menu()
    {

        $title = __('Hearthstone Archive', $this->plugin_domain);

        $hook_suffix = add_management_page($title, $title, 'export', $this->plugin_domain, array(
            $this,
            'load_admin_view',
        ));

        add_action('load-' . $hook_suffix, array($this, 'load_bundle'));
    }

    public function load_view($view)
    {
        $path = trailingslashit($this->views_dir) . $view;
        if (file_exists($path)) {
            include $path;
        }
    }

    public function load_admin_view()
    {
        $this->load_view('admin.php');
    }

    public function load_bundle()
    {
        wp_enqueue_script($this->plugin_domain . '-bundle', plugin_dir_url(__FILE__) . 'dist/main.js', array(), $this->version, 'all');
    }

}

new Hearthstone_Archive();
