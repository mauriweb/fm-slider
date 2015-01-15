<?php
/*
Plugin Name: Fm Slider
Plugin URI: https://github.com/mauriweb/fm-slider
Description: Slider Show for images
Version: 1.0.0
Author: Francisco Mauri
Author URI: https://github.com/mauriweb/fm-slider
License: GPLv2 or later
Text Domain: fm-slider
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'FMSLIDER_VERSION', '1.0.0' );
define( 'FMSLIDER_MINIMUM_WP_VERSION', '1.0.0' );
define( 'FMSLIDER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'FMSLIDER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

class fm_slider{
    
    function __construct() {
        require_once (FMSLIDER_PLUGIN_DIR.'/classes/FmSliderSettings.php');
        require_once (FMSLIDER_PLUGIN_DIR.'/classes/FmSliderCustomPostType.php');
        
        if(is_admin()){
            add_action('wp_ajax_update_slides_order', array($this, 'update_slides_order'));
        }else{
            
            add_shortcode('fm_slider_shortcode', array($this, 'fm_slider_shortcode'));
            add_action('wp_enqueue_scripts', array($this, 'fm_slider_scripts'));
            
            
        }

    }
    


    public function  fm_slider_scripts(){
        wp_enqueue_script('jquery-ui',
                'http://code.jquery.com/ui/1.11.2/jquery-ui.js',
                array( 'jquery' ),
                FMSLIDER_VERSION,
                'all' );

        wp_enqueue_script('fm-slider',
                FMSLIDER_PLUGIN_URL . 'js/fm-slider.js',
                array( 'jquery' ),
                FMSLIDER_VERSION,
                'all' );

        wp_enqueue_style('fm-slider',
                FMSLIDER_PLUGIN_URL . 'css/fm-slider.css'
                 );
    }


    public function fm_slider_shortcode($atts){
        
        $postId=$atts['id'];
        if(!is_int(intval($postId)))return false; 
        if ( FALSE === get_post_status( $postId ))return false;
        
        $slides=FmSliderSettings::mount_slides($postId);
                //dame($postId,1);
        return $slides;
    }
    
    
    

    
    
    
}

new fm_slider;









