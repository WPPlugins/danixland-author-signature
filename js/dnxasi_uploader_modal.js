/*
 * Adapted from: http://mikejolley.com/2012/12/using-the-new-wordpress-3-5-media-uploader-in-plugins/
 */
jQuery(document).ready(function($){
// Uploading files
var file_frame;

  $('#dnxasi_uploadimage').on('click', function( event ){

    event.preventDefault();

    // If the media frame already exists, reopen it.
    if ( file_frame ) {
      file_frame.open();
      return;
    }

    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
      title: data.frameTitle,
      button: {
        text: data.buttonText,
      },
      multiple: false  // Set to true to allow multiple files to be selected
    });

    // When an image is selected, run a callback.
    file_frame.on( 'select', function() {
      // We set multiple to false so only get one image from the uploader
      attachment = file_frame.state().get('selection').first().toJSON();

      // Do something with attachment.id and/or attachment.url here
      $('#dnxasi_meta_signature').attr('value', attachment.url);
      $('#dnxasi_signature_preview').attr('src', attachment.url);
    });

    // Finally, open the modal
    file_frame.open();
  });

  $('#dnxasi_deleteimage').on( 'click', function(event) {
    event.preventDefault();

    $('#dnxasi_meta_signature').attr('value', '');
    $('#dnxasi_signature_preview').attr('src', data.placeholder);
  });

});
