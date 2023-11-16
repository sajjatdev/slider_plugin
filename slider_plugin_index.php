<?php
/*
Plugin Name: Mobile Slider Rest API
Author: FlowBD and DebugCity
Description: This plugin is Mobile App Home Slider Image Upload
Version: 1.0.0
*/

// Your plugin code goes here
    define("PLUGIN_DIR_PATH",plugin_dir_path(__FILE__));
    define("PLUGINS_URL",plugins_url());

    function plugin_menu_action(){
        add_menu_page("Slider Rest API","Slider Rest API","manage_options","slider_rest_api","slider_menu_action","dashicons-admin-plugins",2);        
    }
    function slider_menu_action(){
    include_once PLUGIN_DIR_PATH.'/views/add_new.php';
    }

    add_action("admin_menu","plugin_menu_action");

    register_activation_hook( __FILE__, 'slider_plugin_install' );
    function slider_plugin_install() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'slider_plugin_table';
       
        $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name (
                            id mediumint(9) NOT NULL AUTO_INCREMENT,
                            create_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                            category_id varchar(255) DEFAULT '' NOT NULL,
                            image_url varchar(1000) DEFAULT '' NOT NULL,
                            PRIMARY KEY  (id)
                            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
           
      
    }
    register_deactivation_hook( __FILE__, 'slider_plugin_uninstall' );    
    function slider_plugin_uninstall(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'slider_plugin_table';
    $wpdb->query("DROP table IF Exists $table_name");
    }
 
 // REST API Section Block
function custom_rest_endpoint_callback($data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'slider_plugin_table';
        $query = "SELECT * FROM $table_name ORDER BY id DESC LIMIT 5";
        $results = $wpdb->get_results($query, OBJECT);
        return $results;
    }

add_action('rest_api_init', function () {
        register_rest_route('sajjatdev/v1', '/slider/', array(
            'methods' => 'GET',
            'callback' => 'custom_rest_endpoint_callback',
        ));
    });


?>