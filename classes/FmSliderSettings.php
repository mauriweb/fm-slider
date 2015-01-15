<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FmSliderSettings
 *
 * @author franmadj
 */
class FmSliderSettings {
    
    static $defaults=array(
                    'slider_speed'=>10,
                    'slider_pause'=>'100',
                    'show_auto_controls'=>false,
                    'show_page_controls'=>false,
                    'show_dir_controls'=>false,
                    'stops_hover'=>false,
                    'hide_dir_controls_end'=>false,
                    'slider_width'=>false,
                    'slider_mode'=>'ho'   
                );
    
    static $settings=array(
                    'slider_speed'=>array('n','speed'),
                    'slider_pause'=>array('n','pause'),
                    'slider_width'=>array('s','sliderWidth'),
                    'slider_mode'=>array('s','slideMode'),
        
                    'show_auto_controls'=>array('b','autoControls'),
                    'show_page_controls'=>array('b','controls'),
                    'show_dir_controls'=>array('b','controlsDir'),
                    'stops_hover'=>array('b','stopOnHober'),
                    'hide_dir_controls_end'=>array('b','checkVisibilityControlsDir'),
                    
        
        );
    
    /*autoControls: true,
        controls: true,
        stopOnHober: false,
        controlsDir: true,
        speed: 2000,
        pause: 20,
        checkVisibilityControlsDir: true,
        responsive: false,
        sliderWidth: '80',
        fullScreen: false,
        //slideMode: 'horizontal'
        //slideMode: 'vertical'
        slideMode: 'fade'*/
    

    
    
    
    static function mount_slides($postId=NULL){
        $htmlSlide='';
        if($slides=self::get_slides($postId)){
            $htmlSlide='<ul class="fmslider">';
            foreach($slides as $slide){
                $htmlSlide.='<li><img title="'.$slide['title'].'" src="'.$slide['src'].'" /></li>';
            }
            $htmlSlide.='</ul>';
            
            $settigns=self::get_settings($postId);
            $htmlSlide.='<script>';
            $htmlSlide.="jQuery(document).ready(function($){
                    $('.fmslider').fmSlider({";
                foreach($settigns as $key=>$val){
                   if(self::$settings[$key][0]=='b'){
                       $val=boolval($val)?'true':'false';
                       $htmlSlide.=self::$settings[$key][1].':'.$val.',';
                   }elseif(self::$settings[$key][0]=='n'){
                       $htmlSlide.=self::$settings[$key][1].':'.$val.',';
                   }else{
                       $htmlSlide.=self::$settings[$key][1].':"'.$val.'",';
                   }
                   
                   
                } 
                $htmlSlide.='}); }); </script>';
                
        }
        return $htmlSlide;
 
    }
    
    static function get_slides($postId=NULL){
        
        if(!is_int(intval($postId)))return false; 
        if ( FALSE === get_post_status( $postId ))return false;
 
        
        if(!$slides= maybe_unserialize(get_post_meta($postId, 'fm_slides', true)))//dame($postId.get_post_meta($postId, 'fm_slides'));
                return array();

        return $slides;
 
    }
    
    
    
    
    
    static function get_settings($postId=NULL){
        
        if(!is_int(intval($postId)))return self::$defaults; 
        if(!$settings=get_post_meta($postId))return self::$defaults; 
        
        $new_settings=array();
        foreach(self::$settings as $setting=>$val){
            $setting_new =NULL;
            $setting_new =  trim(get_post_meta($postId, $setting, true));

            if($setting_new === '1')$setting_new=true;
            else
            if($setting_new === '0')$setting_new=false;
            else
            if($setting_new == 'ho')$setting_new='horizontal';
            else
            if($setting_new == 'ver')$setting_new='vertical';
            
            if($setting_new!='')
                $new_settings[$setting]=  $setting_new;
        }
//        dame(self::$defaults);
//        dame($new_settings);
//        dame($new_settings+self::$defaults,1);
       return $new_settings+self::$defaults;  
    }
    
    
    
    static function save_settings($post_id=NULL, $fm_data){
        if ( FALSE === get_post_status( $post_id ) or !is_array($fm_data) )
            return $post_id;
        foreach($fm_data as $key=>$val){
            if(array_key_exists($key, self::$settings))//echo $key;
                update_post_meta($post_id, $key, $val);
        }
        //dame($fm_data,1);
        return $post_id;
    }
    
    static function save_slides($post_id=NULL, $fm_data){ 
        if ( FALSE === get_post_status( $post_id ) )
            return $post_id;
        
        
        if(!is_array($fm_data)){
            delete_post_meta($post_id, 'fm_slides');
            return $post_id;
        }
        //dame($fm_data);
        $newData=array();
        foreach($fm_data as $key=>$val){
            if(substr($key, 0, 4)=='src_'){
                $id=substr($key, 4);
                $newData[]=array(
                    'src'=>$val, 
                    'title'=>$fm_data['title_'.$id],
                    'position'=>$fm_data['position_'.$id]
                        );
            }
        }//dame($newData,1);
        update_post_meta($post_id, 'fm_slides', serialize($newData));
        
        return $post_id;
    }
}
