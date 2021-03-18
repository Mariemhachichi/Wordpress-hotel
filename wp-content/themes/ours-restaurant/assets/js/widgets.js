jQuery(document).ready(function($) {

    var at_document = $(document);

    at_document.on('click','.media-image-upload', function(e){

        // Prevents the default action from occuring.
        e.preventDefault();
        var media_image_upload = $(this);
        var media_title = $(this).data('title');
        var media_button = $(this).data('button');
        var media_input_val = $(this).prev();
        var media_image_url_value = $(this).prev().prev().children('img');
        var media_image_url = $(this).siblings('.img-preview-wrap');

        var meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
            title: media_title,
            button: { text:  media_button },
            library: { type: 'image' }
        });
        // Opens the media library frame.
        meta_image_frame.open();
        // Runs when an image is selected.
        meta_image_frame.on('select', function(){

            // Grabs the attachment selection and creates a JSON representation of the model.
            var media_attachment = meta_image_frame.state().get('selection').first().toJSON();

            // Sends the attachment URL to our custom image input field.
            media_input_val.val(media_attachment.url);
            if( media_image_url_value !== null ){
                media_image_url_value.attr( 'src', media_attachment.url );
                media_image_url.show();
                LATESTVALUE(media_image_upload.closest("p"));
            }
        });
    });

    // Runs when the image button is clicked.
    jQuery('body').on('click','.media-image-remove', function(e){
        $(this).siblings('.img-preview-wrap').hide();
        $(this).prev().prev().val('');
    });

    var LATESTVALUE = function (wrapObject) {
        wrapObject.find('[name]').each(function(){
            $(this).trigger('change');
        });
    };

});


jQuery(document).ready(function($) {

    var count = 0;
    jQuery("body").on('click','.at-ample-add', function(e) {

        e.preventDefault();
        var additional = $(this).parent().parent().find('.at-ample-additional');
        var container = $(this).parent().parent().parent().parent();

        var container_class = container.attr('id');

        var arr = container_class.split('-');

        var val=  arr[1].split('_');

        val.shift();

        var liver=  val.join('_')

        var container_class_array = container_class.split(liver+"-");
        var instance = container_class_array[1];
        var add = $(this).parent().parent().find('.at-ample-add');
        count = additional.find('.at-ample-sec').length;

        //Json response
        $.ajax({
            type      : "GET",
            url       : ajaxurl,
            data      : {
                action: 'ours_restaurant_wp_pages_plucker',
            },
            dataType: "json",
            success: function (data) {

                var options = '<option disabled>Select pages</option>';

                $.each(data, function( index, value ) {
                    var option = '<option value="'+index+'">'+value+'</option>';
                    options += option;
                });


                additional.append(
                    '<div class="at-ample-sec"><div class="sub-option section widget-upload">'+
                    '<label for="widget-'+liver+'-'+instance+'-resources-'+count+'-page_ids">Pages</label>'+
                    '<select class="widefat" id="widget-'+liver+'-'+instance+'-resources-'+count+'-page_ids"'+
                    'name="widget-'+liver+'['+instance+'][resources]['+count+'][page_ids]">'+ options + '</select>' +
                    '<a class="at-ample-remove delete">Remove Section</a></div></div>' );

            }
        });

    });
    jQuery("body").on('click','.at-ample-remove', function(e) {


        jQuery(this).parents('.at-ample-sec').remove();


    });

});





