var $ = jQuery;
jQuery(document).ready( function($) {

    function media_upload(button_class) {

        var _custom_media = true,

        _orig_send_attachment = wp.media.editor.send.attachment;



        $('body').on('click', button_class, function(e) {

            var button_id ='#'+$(this).attr('id');

            var self = $(button_id);

            var send_attachment_bkp = wp.media.editor.send.attachment;

            var button = $(button_id);

            var id = button.attr('id').replace('_button', '');

            _custom_media = true;

            wp.media.editor.send.attachment = function(props, attachment){

                if ( _custom_media  ) {

                    $('.custom_media_id').val(attachment.id);

                    $('.custom_media_url_info').val(attachment.url);
					
					if( $('#custom_media_image_info') ){
						$('#custom_media_image_info').attr('src', attachment.url );
					}

                    $('.custom_media_image_info').attr('src',attachment.url).css('display','block');

                } else {

                    return _orig_send_attachment.apply( button_id, [props, attachment] );

                }

            }

            wp.media.editor.open(button);
			$(this).prev().change();
                return false;

        });

    }

    media_upload('.custom_media_button_info.button');

});


jQuery(document).ready( function($) {

    function media_upload(button_class) {

        var _custom_media = true,

        _orig_send_attachment = wp.media.editor.send.attachment;



        $('body').on('click', button_class, function(e) {

            var send_attachment_bkp = wp.media.editor.send.attachment;

            var button = $(this);

            //var id = button.attr('id').replace('_button', '');

            _custom_media = true;

            wp.media.editor.send.attachment = function(props, attachment){

                if ( _custom_media  ) {
					
					button.parent('.room-card p').find('input[type=hidden]').val(attachment.url);
					
					button.parent('.room-card p').find('img').attr('src', attachment.url );

                } else {

                    return _orig_send_attachment.apply( $(this).attr('id'), [props, attachment] );

                }

            }

            wp.media.editor.open(button);
			$(this).prev().change();
                return false;

        });

    }

    media_upload('.room_image_btn.button');
	
	$('.room_close').click(function(){
		var p = $(this);
		p.parent('.room-card p').find('input[type=hidden]').val('');
		p.parent('.room-card p').find('img').attr('src','http://placehold.it/1920x1080');
	});

});