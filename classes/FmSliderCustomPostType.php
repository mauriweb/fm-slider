<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FmSliderCustomPostType
 *
 * @author franmadj
 */
class FmSliderCustomPostType {
    
    var $post_type='fm_slider';
    
    function __construct() {
        add_action('init', array($this, 'create_post_type'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_styles'));
        add_action('save_post', array ($this, 'save_fm_slider_settings'));
        add_action('save_post', array ($this, 'save_fm_slider_slides'));
    }
    
    public function  admin_enqueue_scripts(){
        $screen=get_current_screen();
        if($screen->post_type=='fm_slider'){
            wp_enqueue_media();
            wp_enqueue_script(
                'fm-slider-admin-js',
                FMSLIDER_PLUGIN_URL . 'js/admin.js',
                array( 'jquery' ),
                FMSLIDER_VERSION,
                'all'
            );
        }
    }
    public function admin_enqueue_styles() {
        wp_enqueue_style(
        'fm-slider-admin-css',
        FMSLIDER_PLUGIN_URL . 'css/admin.css',
        array()
        );
    }
            
    public function create_post_type(){
        $labels = array(
		'name'                => __( 'Fm Sliders', 'twentythirteen' ),
		'singular_name'       => __( 'Fm Slider', 'twentythirteen' ),
		'menu_name'           => __( 'Fm Sliders', 'twentythirteen' ),
		'parent_item_colon'   => __( 'Parent Fm Slider', 'twentythirteen' ),
		'all_items'           => __( 'All Fm Sliders', 'twentythirteen' ),
		'view_item'           => __( 'View Fm Slider', 'twentythirteen' ),
		'add_new_item'        => __( 'Add New Fm Slider', 'twentythirteen' ),
		'add_new'             => __( 'Add New', 'twentythirteen' ),
		'edit_item'           => __( 'Edit Fm Slider', 'twentythirteen' ),
		'update_item'         => __( 'Update Fm Slider', 'twentythirteen' ),
		'search_items'        => __( 'Search Fm Slider', 'twentythirteen' ),
		'not_found'           => __( 'Not Found', 'twentythirteen' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'twentythirteen' ),
	);
        
        register_post_type($this->post_type, 
                array(
                    'labels'=>$labels,
                    'public'=>true,
                    'has_archive'=>true,
//                    'capability_type'      => 'fm_slider',
//                    'map_meta_cap'=>true,
//				'capabilities'         => array(
//                                    'create_posts' => 'create_fm_sliders',
//					'edit_post'              => 'edit_fm_slider',
//					'read_post'              => 'read_slider',
//					'delete_post'            => 'delete_fm_slider',
//					'edit_posts'             => 'edit_fm_sliders',
//					'edit_others_posts'      => 'edit_others_fm_sliders',
//					'publish_posts'          => 'publish_fm_sliders',
//					'read_private_posts'     => 'read_private_fm_sliders',
//
//					'delete_posts'           => 'delete_fm_sliders',
//					'delete_private_posts'   => 'delete_private_fm_sliders',
//					'delete_published_posts' => 'delete_published_fm_sliders',
//					'delete_others_posts'    => 'delete_others_fm_sliders',
//					'edit_private_posts'     => 'edit_private_fm_sliders',
//					'edit_published_posts'   => 'edit_published_fm_sliders',
//				),
                    'supports'             => array('title'),
                    'register_meta_box_cb' => array($this, 'registerMetaBoxes')
                    )); 
        
        //dame($GLOBALS['wp_post_types']['fm_slider']->cap);
    }
    
    public function save_fm_slider_slides($post_id){
        
        
        if ( !wp_verify_nonce( $_POST['settings_nonce'], 'settings_nonce' )) {
            return $post_id;
        }

        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
            return $post_id;
        $fm_data='';
        if(isset($_POST['slides']))
            $fm_data=$_POST['slides'];
        FmSliderSettings::save_slides($post_id, $fm_data );

    }
    
    public function save_fm_slider_settings($post_id){
        
        
        if ( !wp_verify_nonce( $_POST['settings_nonce'], 'settings_nonce' )) {
            return $post_id;
        }

        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
            return $post_id;
        
        
        if ( isset( $_POST['fm'] ) ) { //dame($post_id,1);
            FmSliderSettings::save_settings($post_id, $_POST['fm'] );
        }

    }
    
    
    /**
	 * Adds custom meta boxes to slideshow post type.
	 *
	 * @since 1.0.0
	 */
	function registerMetaBoxes(){
//		add_meta_box(
//			'information',
//			__('Information', 'slideshow-plugin'),
//			array($this, 'informationMetaBox'),
//			$this->post_type,
//			'normal',
//			'high'
//		);





		add_meta_box(
			'settingsFmSlider',
			__('Slider Settings', 'slideshow-plugin'),
			array($this, 'slider_html_settings_meta_box'),
			$this->post_type,
			'normal',
			'low'
		);
                add_meta_box(
			'slidesfmSlider',
			__('Slides', 'slideshow-plugin'),
			array($this, 'slider_html_slides_meta_box'),
			$this->post_type,
			'normal',
			'low'
		);

		// Add support plugin message on edit slideshow
		if (isset($_GET['action']) &&
			strtolower($_GET['action']) == strtolower('edit'))
		{
			//add_action('admin_notices', array(__CLASS__,  'supportPluginMessage'));
		}
	}
        
        
        /**
	 * Shows slides currently in slideshow
	 *
	 * TODO Tidy up, it's probably best to move all to 'slides.php'
	 *
	 * @since 1.0.0
	 */
	function slider_html_settings_meta_box(){ 
            global $post;
            $settings=FmSliderSettings::get_settings($post->ID);
            //dame($settings);
            $view=FMSLIDER_PLUGIN_DIR.'views/metabox-settings.php';
		if(is_file($view)){
                    require_once $view;
                }
                
	}
        
        function slider_html_slides_meta_box(){ 
            global $post;
            $slides=FmSliderSettings::get_slides($post->ID);
            //dame($slides);
            $view=FMSLIDER_PLUGIN_DIR.'views/metabox-slides.php';
		if(is_file($view)){
                    require_once $view;
                }
                
	}
        

}
new FmSliderCustomPostType;
