<?php


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
        
        add_filter('manage_edit-fm_slider_columns',          array($this, 'add_columns_head_fm_slider'));
        add_action('manage_fm_slider_posts_custom_column',   array($this, 'add_columns_content_fm_slider'), 10, 2);
    }
    
    //ADD COLUMNS TEAM MEMBER
    function add_columns_head_fm_slider($column ) {
        $column['Shodtcode'] = 'Short Code';
        
        return $column;
    }
    function add_columns_content_fm_slider($column, $post_id) {
        if($column=='Shodtcode'){
            echo '[fm_slider_shortcode id="'.$post_id.'" ]';
        }
        

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
    
    /**
	 * Create fm-slider post type
	 * @since 1.0.0
	 */
            
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
                    'supports'             => array('title'),
                    'register_meta_box_cb' => array($this, 'registerMetaBoxes')
                    )); 
        
        //dame($GLOBALS['wp_post_types']['fm_slider']->cap);
    }
    
    /**
	 * Get and prepare data from post slider slides
         * @param int $post_id
	 * @since 1.0.0
	 */
    
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
    
    /**
	 * Get and prepare data from post slider settiengs
     * * @param int $post_id
	 * @since 1.0.0
	 */
    
    public function save_fm_slider_settings($post_id){
        
        
        if ( !wp_verify_nonce( $_POST['settings_nonce'], 'settings_nonce' )) {
            return $post_id;
        }

        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
            return $post_id;
        
        
        if ( isset( $_POST['fm'] ) ) { 
            FmSliderSettings::save_settings($post_id, $_POST['fm'] );
        }

    }
    
    
    /**
	 * Adds custom meta boxes to slideshow post type.
	 *
	 * @since 1.0.0
	 */
	function registerMetaBoxes(){
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
	 * Display settiengs meta box
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
        
        /**
	 * Display slides meta box
	 * @since 1.0.0
	 */
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
