
<?php 
$checked='checked="checked"'; 
$selected='selected="selected"';
//dame($settings);
?>
<table class="settings">
    <input type="hidden" value="<?php echo wp_create_nonce( 'slides_nonce' ); ?>" name="slides_nonce" /> 

  <tr>
    <td>
        <a id="select_image" title="Set Footer Image" href="javascript:;" >Set featured image</a>
        
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
      <td colspan="3">
          <div id="featured-image-container">
          <img src="<?php echo get_post_meta( $post_id, 'footer-thumbnail-src', true ); ?>" alt="<?php echo get_post_meta( $post_id, 'footer-thumbnail-alt', true ); ?>" title="<?php echo get_post_meta( $post->ID, 'footer-thumbnail-title', true ); ?>" />
          </div>
        </td>
  </tr>
</table>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
 <script>
     var ajaxurl = "<?php echo admin_url("admin-ajax.php"); ?>";
    jQuery(function($) {
        $( "#imagenes" ).sortable({
            cursor:'move',
            opacity:0.5,
            axis:'y',
            update: function(){
                $('.img_cont').each(function(i){
                    $(this).find('.slide_pos').val(i);
                });
//                var order=$(this).sortable('serialize')+'&action=update_slides_order';console.log(order);
//                $.post(ajaxurl, order, function(data){
//                    console.log(data);
//                });

            }
        
        });
        $( "#imagenes" ).disableSelection();
    });
    var FMSLIDER_PLUGIN_URL="<?php echo FMSLIDER_PLUGIN_URL; ?>";
    
</script>

<!--<div id="imagenes"><div id="img_cont0" class="img_cont"><div class="acciones"><a href="#" class="del_one_img">Eliminar</a></div><img width="200" src="http://localhost:8080/aecop2/wp-content/uploads/2014/12/Screenshot-125.png"> <input type="hidden" value="undefined" name="src0"> <input type="hidden" value="Screenshot (125)" name="title0"> </div><div id="img_cont1" class="img_cont"><div class="acciones"><a href="#" class="del_one_img">Eliminar</a></div><img width="200" src="http://localhost:8080/aecop2/wp-content/uploads/2014/12/Screenshot-124.png"> <input type="hidden" value="undefined" name="src1"> <input type="hidden" value="Screenshot (124)" name="title1"> </div><div id="img_cont2" class="img_cont"><div class="acciones"><a href="#" class="del_one_img">Eliminar</a></div><img width="200" src="http://localhost:8080/aecop2/wp-content/uploads/2014/12/Screenshot-123.png"> <input type="hidden" value="undefined" name="src2"> <input type="hidden" value="Screenshot (123)" name="title2"> </div><div id="img_cont3" class="img_cont"><div class="acciones"><a href="#" class="del_one_img">Eliminar</a></div><img width="200" src="http://localhost:8080/aecop2/wp-content/uploads/2014/12/Screenshot-122.png"> <input type="hidden" value="undefined" name="src3"> <input type="hidden" value="Screenshot (122)" name="title3"> </div></div>-->
<div id="imagenes">
    <?php
    $i=0;
    foreach($slides as $slide){
        echo '
            <div id="img_cont_'.$i.'" class="img_cont">
                <img class="short-imgs" width="50" src="'.FMSLIDER_PLUGIN_URL.'/images/short.jpg">
                <div class="acciones">
                    <a href="#" class="del_one_img">Eliminar</a>
                </div>
                
                <img style="max-width:200px; max-height:100px;" src="'.$slide['src'].'"> 
                <input type="hidden" value="'.$slide['src'].'" name="slides[src_'.$i.']"> 
                <input type="hidden" value="'.$slide['title'].'" name="slides[title_'.$i.']"> 
                <input class="slide_pos" type="hidden" value="'.$slide['position'].'" name="slides[position_'.$i.']"> 
            </div>';
        $i++;
    }
    //if($i>0)$i-=1;
    echo '<input type="hidden" id="last_position" value="'.$i.'">';
    
    ?>
    
</div>
