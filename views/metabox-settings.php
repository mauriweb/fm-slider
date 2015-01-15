
<?php 
$checked='checked="checked"'; 
$selected='selected="selected"';
//dame($settings);
?>
<table class="settings">
    <input type="hidden" value="<?php echo wp_create_nonce( 'settings_nonce' ); ?>" name="settings_nonce" /> 
  <tr>
    <td >Show Auto Controls:</td>
    <td >
    <label>Si:</label><input type="radio"  name="fm[show_auto_controls]" <?php if($settings['show_auto_controls']) echo $checked; ?> value="1">
    <label>No:</label><input type="radio"  name="fm[show_auto_controls]" <?php if(!$settings['show_auto_controls']) echo $checked; ?>  value="0">
    
    </td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td>Show Controls Pages:</td>
    <td><label>Si:</label><input type="radio"  name="fm[show_page_controls]" <?php  if($settings['show_page_controls']) echo $checked ; ?> value="1">
    <label>No:</label><input type="radio"  name="fm[show_page_controls]" <?php if(!$settings['show_page_controls']) echo $checked; ?>  value="0"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Show controls Direction:</td>
    <td><label>Si:</label><input type="radio"  name="fm[show_dir_controls]" <?php if($settings['show_dir_controls']) echo $checked ; ?> value="1">
    <label>No:</label><input type="radio"  name="fm[show_dir_controls]" <?php if(!$settings['show_dir_controls']) echo $checked ; ?>  value="0"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Stop On Hover:</td>
    <td><label>Si:</label><input type="radio"  name="fm[stops_hover]" <?php if($settings['stops_hover']) echo $checked; ?> value="1">
    <label>No:</label><input type="radio"  name="fm[stops_hover]" <?php if(!$settings['stops_hover']) echo $checked; ?>  value="0"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Hide Direction Controls On End:</td>
    <td><label>Si:</label><input type="radio"  name="fm[hide_dir_controls_end]" <?php if($settings['hide_dir_controls_end']) echo $checked; ?> value="1">
    <label>No:</label><input type="radio"  name="fm[hide_dir_controls_end]" <?php if(!$settings['hide_dir_controls_end']) echo $checked; ?>  value="0"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Speed:</td>
    <td><input type="range" min="20" name="fm[slider_speed]" max="5000" value="<?php echo $settings['slider_speed'];  ?>" step="100"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Pause:</td>
    <td><input type="range" name="fm[slider_pause]" min="20" max="5000" value="<?php echo $settings['slider_pause'];  ?>" step="10"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Slider width:</td>
    <td><input type="range" name="fm[slider_width]" min="20" max="100" value="<?php echo $settings['slider_width'];  ?>" step="1"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Slider Mode:</td>
    <td><select name="fm[slider_mode]">
    <option <?php if($settings['slider_mode']=='ho') echo $selected; ?> value="ho">Horizontal</option>
    <option <?php if($settings['slider_mode']=='ver') echo $selected; ?>value="ver">Vertical</option>
    <option <?php if($settings['slider_mode']=='fade') echo $selected; ?>value="fade">Fade</option>
    </select></td>
    <td>&nbsp;</td>
  </tr>

</table>

