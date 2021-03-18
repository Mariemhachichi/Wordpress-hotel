
var RepeatableCustomize = function (  control  ){
    var that = this;
    var $ = jQuery;
    var container =  control.container;
    var default_data =  control.params.fields;
    var values = JSON.parse( control.params.value ) ;

    that.getData = function ( ){
        var f = $( '.form-data', container );
        var data =  $( 'input, textarea, select', f ).serialize();
        //console.log( data );
        return  JSON.stringify( data ) ;
    };

    that.rename = function(){

        $( '.list-repeatable li', container ).each( function( index ) {
            var li =  $( this );

            $( 'input, textarea, select', li ).each( function(){
                var input = $( this );
                var name = input.attr( 'data-repeat-name' ) || undefined;
                if(  typeof name !== "undefined" ) {
                    name = name.replace(/__i__/g, index );
                    input.attr( 'name',  name );
                }
            } );

        } );
    };

    //----------------------------


    var frame = wp.media({
        title: wp.media.view.l10n.addMedia,
        multiple: false,
        library: {type: 'image'},
        //button : { text : 'Insert' }
    });

    frame.on('close', function () {
        // get selections and save to hidden input plus other AJAX stuff etc.
        var selection = frame.state().get('selection');
        // console.log(selection);
    });


    that.handleMedia = function( $context ) {
        $('.item-media', $context).each( function(){

            var _item = $( this );
            // when remove item
            $( '.remove-button', _item ).on( 'click', function( e ){
                e.preventDefault();

                $( '.image_id, .image_url', _item).val( '' );
                $( '.thumbnail-image', _item ).html( '' );

                $( '.current', _item ).removeClass( 'show' ).addClass( 'hide' );

                $( this).hide();

                $('.upload-button', _item ).text( $('.upload-button', _item ).attr( 'data-add-txt' ) );
                $( '.image_id', _item).trigger( 'change' );

            } );

            // when upload item
            $('.upload-button', _item ).on('click', function () {
                var btn = $( this );

                frame.on('select', function () {
                    // Grab our attachment selection and construct a JSON representation of the model.
                    var media_attachment = frame.state().get('selection').first().toJSON();
                    // media_attachment= JSON.stringify(media_attachment);

                    $( '.image_id', _item ).val(media_attachment.id);
                    var preview, img_url;
                    img_url = media_attachment.url;

                    $( '.current', _item ).removeClass( 'hide').addClass( 'show' );

                    $( '.image_url', _item ).val(img_url);
                    preview = '<img src="' + img_url + '" alt="">';
                    //$(' img', _item).remove();
                    $( '.thumbnail-image', _item ).html( preview );
                    $( '.remove-button', _item).show();
                    $( '.image_id', _item).trigger( 'change' );

                    btn.text( btn.attr( 'data-change-txt' ) );

                });

                frame.open();

            });


        } );
    };

    that.colorPicker =  function( $context ){
        // Add Color Picker to all inputs that have 'color-field' class
        $('.color-field', $context).wpColorPicker( {
            change: function(event, ui){
                that.updateValue();
            },

        });
    };

    that.actions = function( $context ){

        $( '.widget .widget-action, .widget .repeat-control-close, .widget-title' , $context ).click( function( e ){
            //console.log( 'clicked' );
            var p =  $('.widget', $context );

            if ( p.hasClass( 'explained' ) ) {
                //console.log( 'has: explained' );
                $( '.widget-inside', p ).slideUp( 200, 'linear', function(){
                    $( '.widget-inside', p ).removeClass( 'show').addClass('hide');
                    p.removeClass( 'explained' );
                } );
            } else {
               // console.log( 'No: explained' );
                $( '.widget-inside', p ).slideDown( 200, 'linear', function(){
                    $( '.widget-inside', p ).removeClass( 'hide').addClass('show');
                    p.addClass( 'explained' );
                } );
            }

            return false;
        } );

        if ( typeof control.params.live_title_id !== "undefined" ) {

            //console.log( $( "[data-live-id='"+ control.params.live_title_id+"']").eq(0).val() );
            var v = $( "[data-live-id='"+ control.params.live_title_id+"']", $context ).eq(0).val();
            if ( v== '' ){
                v = '[Untitled]';
            }

            if ( typeof control.params.title_format !== "undefined" && control.params.title_format !== ''  ) {
                v = control.params.title_format.replace('[live_title]', v );
            }
            $( '.widget-title .live-title', $context ).text( v );

            $context.on( 'keyup', "[data-live-id='"+ control.params.live_title_id+"']", function(){
                var v = $( this).val();
                if ( v== '' ){
                    v = '[Untitled]';
                }

                if ( typeof control.params.title_format!== "undefined"  && control.params.title_format !== ''  ) {
                    v = control.params.title_format.replace('[live_title]', v );
                }

                $( '.widget-title .live-title', $context ).text( v );
            } );
        }

        $context.on( 'click', '.repeat-control-remove' , function( e ){
            e.preventDefault();
            $context.remove();
            that.rename();
            that.updateValue();
        } );

    };



    /**
     * Function that loads the Mustache template
     */
    that.repeaterTemplate = _.memoize(function () {
        var compiled,
        /*
         * Underscore's default ERB-style templates are incompatible with PHP
         * when asp_tags is enabled, so WordPress uses Mustache-inspired templating syntax.
         *
         * @see trac ticket #22344.
         */
        options = {
            evaluate: /<#([\s\S]+?)#>/g,
            interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
            escape: /\{\{([^\}]+?)\}\}(?!\})/g,
            variable: 'data'
        };

        return function ( data ) {
            compiled = _.template( container.find('.repeatable-js-template').first().html(), null, options);
            return compiled( data );
        };
    });

    that.template = that.repeaterTemplate();

    that.newItem = function(){
        $( '.add-new-repeat-item', control.container ).click( function(){
            var $html = $( that.template( default_data ) );
            $( '.list-repeatable', control.container ).append( $html );
            that.int( $html );
            that.actions( $html );
            that.updateValue();
        } );
    };


    that.int = function( $context ){
        that.rename();
        that.colorPicker( $context );
        that.handleMedia( $context );
    };

    $( '.list-repeatable li').each( function(){
        that.actions( $( this ) );
    } );


   that.updateValue = function(){
       var data = that.getData();
       //console.log( data );
       $( "[data-hidden-value]", container ).val( data );
       $( "[data-hidden-value]", container ).trigger( 'change' );
   };

    $( ".list-repeatable", container ).sortable({
        handle: ".widget-title",
        containment: ".customize-control-repeatable",
       /// placeholder: "sortable-placeholder",
        update: function( event, ui ) {
            that.rename();
            that.updateValue();
        }
    });


    if ( values.length ) {
        var _templateData, _values;

        //console.log( _templateData );
        //console.log( '-----' );

        for (var i = 0; i < values.length; i++) {

            _templateData = $.extend( true, {}, control.params.fields );

            _values = values[i];
            if ( values[i] ) {
                for ( var j in _values ) {
                    if ( _values.hasOwnProperty( j ) && _values.hasOwnProperty( j ) ) {
                       // console.log( _values[j] );
                        _templateData[ j ].value = _values[j];
                    }
                }
            }


            var $html = $( that.template( _templateData ) );
            $( '.list-repeatable', container ).append( $html );
            that.int( $html );
            that.actions( $html );

        }

        //console.log( '--------' );
        //console.log( _templateData );
    }

    that.newItem();
    that.int( container );

    $( '.list-repeatable', container ).on( 'keyup change', 'input, select, textarea', function( e ) {
        that.updateValue();
    });


};


//------------------------------------------------

( function( api ) {
    //console.log( api.controlConstructor );

    api.controlConstructor['repeatable'] = api.Control.extend( {
        ready: function() {
            var control = this;
            //console.log( settingValue );
            new RepeatableCustomize(  control, jQuery );

        }
    } );

} )( wp.customize );
