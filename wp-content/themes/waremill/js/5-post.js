jQuery(document).ready(function($){

    /********************************************* Constractor :  Editor Post  *********************************************/
    tinymce.init({ 
        selector:'.wysform',
        height: 500, 
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code'
        ],
        toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image ',
        content_css: '//www.tinymce.com/css/codepen.min.css'
   });


    /********************************************* Constractor :  Remove feature image  *********************************************/
	jQuery(document).on('click','.removefeatureimg',function(e){
        e.preventDefault();
        jQuery(this).parent().parent().remove();

	});

    /********************************************* Constractor :  Remove Post  *********************************************/
    jQuery(document).on('click', '.removepost', function(e){ 
        e.preventDefault();
        jQuery(this).parent().find('.removemessage').fadeIn();
    });

 
 	/********************************************* Constractor :  Add Post  *********************************************/
    jQuery(document).on('submit','#createpost_form',function(e){
        e.preventDefault();

        var form_name = '#createpost_form'; 
        var form_resp = '.responsecheck_addpost';
        var this_form = jQuery(this);
        var values = jQuery(form_name).serializeArray();
        var valuesAjax = jQuery(form_name).serialize(); //.serialize();
        var type = "text";
        var errors = new Array();
        var doAjax = false;
        var fieldName = fieldValue = "";
        var formData = new FormData(jQuery(form_name)[0]);

        jQuery(this_form).parent().parent().find('.loader').fadeIn();
        jQuery(form_resp).html('');

        jQuery( form_resp ).html(''); 

        var post_title = jQuery(this_form).find('input[name="post_name"]').val();
        var post_categories = jQuery(this_form).find('select[name="post_categories[]"]').val();
        var post_content 	= jQuery(this_form).find('textarea[name="post_content"]').val();

        if(post_title.length < 1){
        	jQuery( form_resp ).append('<p class="ferror fcenter">Error: You can not leave post title empty!</p>').fadeIn();
            doAjax = false;
        }

        if( !post_categories || post_categories == null || typeof  post_categories == "undefined" || post_categories.length < 1 ){
        	jQuery( form_resp ).append('<p class="ferror fcenter">Error: You can not leave post category empty!</p>').fadeIn();
            doAjax = false;
        }

        if(post_content.length < 1){
        	jQuery( form_resp ).append('<p class="ferror fcenter">Error: You can not leave post content empty!</p>').fadeIn();
            doAjax = false;
        }

        if (errors.length == 0 )  { // errors.length || 
            doAjax = true;
            e.preventDefault();
           
        }else{
        	jQuery(this_form).parent().parent().find('.loader').fadeOut();
        }
        
        if (doAjax === true) {

           jQuery.ajax({
                type: 'POST',
                dataType: 'json', 
                url: ajaxUrl,
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) { 
                    if(!jQuery.isEmptyObject(data.message)){
                        $.each(data, function(key, value){
                            
                            if(key != 'done' && key != 'edit'){
                            	$.each(data.message, function(key, value){
                            		jQuery( form_resp ).append(value);
                            	});
                            }
                            if(key == 'done' && value == true &&  key != 'edit'  ){ // reset form only in case of success
                                jQuery(form_name)[0].reset();

                                jQuery('.wrappselectitems').html('');
                                var obj = [];
		                        jQuery('select.SlectBox  option:selected').each(function () {
		                            obj.push(jQuery(this).index());
		                        });
		                        for (var i = 0; i < obj.length; i++) {
		                            jQuery('.SlectBox ')[0].sumo.unSelectItem(obj[i]);
		                        }
		                        jQuery( "select.SlectBox" ).each(function( index ) {
		                            jQuery(this)[0].sumo.reload();
		                        });
                            }

                            if(key != 'done' &&  key != 'edit'  ){
                                jQuery( form_resp ).append(value).fadeIn();
                            }

                            if( key == 'edit' && value == true ){
                            	setTimeout(function(){
									location.reload();
								}, 2000);
                            }
                        });
                    } 
                    jQuery(this_form).parent().parent().find('.loader').fadeOut();
                },
                error: function(data) {
                    jQuery(this_form).parent().parent().find('.loader').fadeOut();
                    jQuery(form_resp).html(data.message);
                }
            });
        }
        e.preventDefault();

    });
});