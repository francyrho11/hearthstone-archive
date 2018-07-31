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
        $this->views_dir = trailingslashit(dirname(__FILE__)) . 'src/views';
        $this->version = '1.0';
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_shortcode('hearthstone-archive', array($this, 'archive_shortcode'));
    }

    public function admin_menu()
    {

        $title = __('Hearthstone', $this->plugin_domain);

        $hook_suffix = add_menu_page($title, $title, 'export', $this->plugin_domain, array(
            $this,
            'load_admin_view',
        ),
            'data:image/svg+xml;base64,' . base64_encode('<svg fill="black" enable-background="new 0 0 128 128" version="1.1" viewBox="0 0 128 128" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><path d="m96.92 97.413l-4.277-13.028c1.592-2.26 2.925-4.714 3.96-7.32l22.487-13.425-22.758-13.408c-0.941-2.233-2.108-4.347-3.466-6.319l3.755-14.792-14.339 4.707c-1.707-1.057-3.511-1.97-5.395-2.729l-13.247-22.189-13.019 22.098c-2.123 0.839-4.144 1.875-6.043 3.088l-16.25-4.677 4.83 16.452c-0.931 1.563-1.742 3.206-2.428 4.912l-21.82 12.857 21.545 12.863c0.732 1.942 1.63 3.801 2.676 5.563l-4.503 15.646 15.042-4.416c2.154 1.471 4.481 2.704 6.94 3.676l13.03 22.118 13.258-22.207c2.071-0.836 4.044-1.861 5.899-3.055l14.123 3.585zm-49.192-15.152c-0.297-0.404-0.73-1.866-0.73-1.866l-0.812-1.704s-0.73-1.217 0.812-0.974 7.141 2.434 10.224 2.516c3.084 0.081 4.707-1.542 5.275-1.704s3.895-1.461 4.869-1.623 6.979-3.836 7.871-12.67c0.893-8.834-7.06-14.027-11.604-14.108 0 0-2.84-0.081-3.489 0.325s-4.869 2.353-6.735 2.597c0 0-1.542 1.461-1.298 3.246 0.243 1.785 0.243 7.93 6.735 7.941 0 0 1.55-0.837 1.384-6.904 0 0 8.08-0.672 8.29 6.904s-7.24 8.625-11.857 8.416-14.377-6.613-13.642-16.791l0.569-3.625 1.623-1.866s3.733-7.547 12.253-9.819c0 0 0.73-1.217 1.623-1.217s1.623 0.974 2.921 1.136 1.785-1.217 3.002-1.136 1.298 0.974 2.597 1.217c1.298 0.243 7.628 0.974 12.74 7.952 0 0 0.243 1.785 0.974 2.11 0.73 0.325 2.434 1.055 2.678 1.623 0.243 0.568 1.948 6.654 1.785 9.251s-0.893 14.688-8.926 18.42l-0.162 2.191s-6.005 5.356-15.175 4.707c-9.169-0.65-11.638-1.615-13.795-4.545z"/></svg>'),
            24
        );
        // add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '' )
        add_submenu_page(
            $this->plugin_domain, 
            'Impostazioni', 
            'Impostazioni',
            'export',
            $this->plugin_domain . '-settings',
            array($this, 'load_admin_settings')
        );

        add_action('load-' . $hook_suffix, array($this, 'load_bundle'));
    }

    public function register_settings()
    {
        add_settings_section(
            'hs_setting_section',
            'Impostazioni plugin',
            array($this, 'hs_setting_section'),
            'hearthstone-archive'
        );

        // Add the field with the names and function to use for our new
        // settings, put it in our new section
        add_settings_field(
            $this->plugin_domain . 'api_url',
            'API Url',
            array($this, 'print_input_field'),
            'hearthstone-archive',
            'hs_setting_section',
            array(
                'option_name' => $this->plugin_domain . 'api_url',
                'type' => 'text',
                'label_for' => $this->plugin_domain . 'api_url'
            )
        );
        register_setting('hearthstone-archive', $this->plugin_domain . 'api_url');

        add_settings_field(
            $this->plugin_domain . 'api_key',
            'API Key',
            array($this, 'print_input_field'),
            'hearthstone-archive',
            'hs_setting_section',
            array(
                'option_name' => $this->plugin_domain . 'api_key',
                'type' => 'text',
                'label_for' => $this->plugin_domain . 'api_key'
            )
        );
        register_setting('hearthstone-archive', $this->plugin_domain . 'api_key');
    }

    public function hs_setting_section($arg)
    {
        // echo section intro text here
        echo '';
    }

    public function print_input_field(array $args)
    {
        $type = $args['type'];
        $id = $args['label_for'];
        $data = get_option($args['option_name'], '');
        $value = $data;

        'email' == $type and '' == $value and $value = $this->admin_mail;
        $value = esc_attr($value);
        $name = $args['option_name'];

        print "<input type='$type' value='$value' name='$name' id='$id'
        class='regular-text code' />";
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

    public function load_admin_settings()
    {
        $this->load_view('admin-settings.php');
    }

    public function load_bundle()
    {
        wp_enqueue_script($this->plugin_domain . '-bundle', plugin_dir_url(__FILE__) . 'dist/bundle.js', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_domain . '-css', plugin_dir_url(__FILE__) . 'dist/style.css', array(), $this->version, 'all');
    }

    public function archive_shortcode()
    {
        $this->load_bundle();
        ob_start();
        $this->load_view('admin.php');
        return ob_get_clean();
    }

}

new Hearthstone_Archive();
