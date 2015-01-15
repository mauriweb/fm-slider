function getBiggerPos(){
    return Math.max.apply( Math, $(this).find('.slide_pos').map(function() { //con('max height: '+$(this).height());//con($(this).height());
                    return $(this).val();
                }).get());
}
/**
 * Callback function for the 'click' event of the 'Set Footer Image'
 * anchor in its meta box.
 *
 * Displays the media uploader for selecting an image.
 *
 * @param    object    $    A reference to the jQuery object
 * @since    0.1.0
 */
function renderMediaUploader( $ ) {
	'use strict';

	var file_frame, image_data, json;

	/**
	 * If an instance of file_frame already exists, then we can open it
	 * rather than creating a new instance.
	 */
	if ( undefined !== file_frame ) {

		file_frame.open();
		return;

	}

	/**
	 * If we're this far, then an instance does not exist, so we need to
	 * create our own.
	 *
	 * Here, use the wp.media library to define the settings of the Media
	 * Uploader. We're opting to use the 'post' frame which is a template
	 * defined in WordPress core and are initializing the file frame
	 * with the 'insert' state.
	 *
	 * We're also not allowing the user to select more than one image.
	 */
	file_frame = wp.media.frames.file_frame = wp.media({
		frame:    'post',
		state:    'insert',
		multiple: true
	});

	/**
	 * Setup an event handler for what to do when an image has been
	 * selected.
	 *
	 * Since we're using the 'view' state when initializing
	 * the file_frame, we need to make sure that the handler is attached
	 * to the insert event.
	 */
	file_frame.on( 'insert', function() {

		// Read the JSON data returned from the Media Uploader
		json = file_frame.state().get( 'selection' ).toJSON();
                
                var lastPosition=parseInt($('#last_position').val());
                var jsonLength=0;
                var nextPositions = Math.max.apply( Math, $('.slide_pos').map(function() { //con('max height: '+$(this).height());//con($(this).height());
                    return $(this).val();
                }).get());
                for( var i in json){
                    jsonLength++;
                    //console.log(i);console.log(json[i]);
                    if ( 0 > $.trim( json[i].url.length ) ) {
                            continue;
                    }
                    i=parseInt(i)
                    var newPosition=lastPosition+i;
                    nextPositions++;
                    var html_prev=$('#imagenes').html();
                    
                    $('#imagenes').html(html_prev + '<div class="img_cont" id="img_cont_'+newPosition+'"><img class="short-imgs" width="50" src="'+FMSLIDER_PLUGIN_URL+'/images/short.jpg"><div class="acciones"><a class="del_one_img" href="#">Eliminar</a></div><img width="200" src="'+json[i].url+'"> <input name="slides[src_'+newPosition+']" type="hidden" value="'+json[i].url+'"> <input name="slides[title_'+newPosition+']" type="hidden" value="'+json[i].title+'"> <input class="slide_pos" type="hidden" value="'+nextPositions+'" name="slides[position_'+newPosition+']">  </div>');
                }
                $('#last_position').val(lastPosition+jsonLength);
                console.log(json);
                

		// First, make sure that we have the URL of an image to display
		if ( 0 > $.trim( $('#imagenes').html() ) ) {
			return;
		}

		// After that, set the properties of the image and display it
		$( '#featured-image-container' )
			.children( 'img' )
				.attr( 'src', json.url )
				.attr( 'alt', json.caption )
				.attr( 'title', json.title )
				.show()
			.parent()
			.removeClass( 'hidden' );

		// Next, hide the anchor responsible for allowing the user to select an image
		$( '#featured-footer-image-container' )
			.prev()
			.hide();

		// Display the anchor for the removing the featured image
		$( '#featured-footer-image-container' )
			.next()
			.show();

		// Store the image's information into the meta data fields
		$( '#footer-thumbnail-src' ).val( json.url );
		$( '#footer-thumbnail-title' ).val( json.title );
		$( '#footer-thumbnail-alt' ).val( json.title );

	});

	// Now display the actual file_frame
	file_frame.open();

}

/**
 * Callback function for the 'click' event of the 'Remove Footer Image'
 * anchor in its meta box.
 *
 * Resets the meta box by hiding the image and by hiding the 'Remove
 * Footer Image' container.
 *
 * @param    object    $    A reference to the jQuery object
 * @since    0.2.0
 */
function resetUploadForm( $ ) {
	'use strict';

	// First, we'll hide the image
	$( '#featured-footer-image-container' )
		.children( 'img' )
		.hide();

	// Then display the previous container
	$( '#featured-footer-image-container' )
		.prev()
		.show();

	// We add the 'hidden' class back to this anchor's parent
	$( '#featured-footer-image-container' )
		.next()
		.hide()
		.addClass( 'hidden' );

	// Finally, we reset the meta data input fields
	$( '#featured-footer-image-info' )
		.children()
		.val( '' );

}

/**
 * Checks to see if the input field for the thumbnail source has a value.
 * If so, then the image and the 'Remove featured image' anchor are displayed.
 *
 * Otherwise, the standard anchor is rendered.
 *
 * @param    object    $    A reference to the jQuery object
 * @since    1.0.0
 */
function renderFeaturedImage( $ ) {

	/* If a thumbnail URL has been associated with this image
	 * Then we need to display the image and the reset link.
	 */
	if ( '' !== $.trim ( $( '#footer-thumbnail-src' ).val() ) ) {

		$( '#featured-footer-image-container' ).removeClass( 'hidden' );

		$( '#select_image' )
			.parent()
			.hide();

		$( '#remove-footer-thumbnail' )
			.parent()
			.removeClass( 'hidden' );

	}

}

(function( $ ) {
	'use strict';

	$(function() {

		renderFeaturedImage( $ );

		$( '#select_image' ).on( 'click', function( evt ) {

			// Stop the anchor's default behavior
			evt.preventDefault();

			// Display the media uploader
			renderMediaUploader( $ );

		});

		$( '#remove-footer-thumbnail' ).on( 'click', function( evt ) {

			// Stop the anchor's default behavior
			evt.preventDefault();

			// Remove the image, toggle the anchors
			resetUploadForm( $ );

		});
                
                $('body').on('click','.del_one_img', function(evt){
                    evt.preventDefault();
                    $(this).parent().parent().remove();
                });

	});

})( jQuery );