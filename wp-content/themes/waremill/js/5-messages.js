jQuery(document).ready(function($){

    /********************************************* Messages: height box  *********************************************/    
    if(jQuery('.wrappmessages').length > 0 ){
        if(jQuery('.wrappmessages').height() > 600){
            jQuery('.wrappmessages').css({'overflow-y':'scroll', 'height':'600px'});
        }
    }

	/********************************************* Messages: view older msg  *********************************************/
	jQuery(document).on('click','.view-older',function(e){
        e.preventDefault();
        var link 		= jQuery(this);
        var data_start 	= jQuery(this).attr('data-start');
        var data_id 	= jQuery(this).attr('data-message');

        var dataform = new FormData();
		dataform.append('action', "get_old_messages"); 
		dataform.append('start', data_start);
		dataform.append('page', data_id);

		jQuery('.wrappload-messages').find('.loader').fadeIn();

		jQuery.ajax({
            type: 'POST',
            dataType: 'json',  
            url: ajaxUrl,
            data: dataform,
            processData: false,
            contentType: false, 
            success: function(data) {
               	if(data.done == true){
               		if(data.content){
               			jQuery('#messages').prepend(data.content);
               			link.attr('data-start',data.content_nr);
               			if(data.content_nr == "0"){
               				link.fadeOut();	
               			}
               		}
               	}
				jQuery('.wrappload-messages').find('.loader').fadeOut();
            },
            error: function(data) {
              	jQuery('.wrappload-messages').find('.loader').fadeOut();
            }
	    });	
    });

	/********************************************* Messages: submit msg  *********************************************/
	jQuery(document).on('submit','#replay_message',function(e){
        e.preventDefault();

        var form_resp 		= '.answer_replay';
        var this_form 		= '#replay_message';
        var doAjax 			= true;
        var message_area 	= jQuery(this_form).find('textarea').val().length;
        var formData 		= new FormData(jQuery(this_form)[0]);

        jQuery(this_form).parent().parent().find('.loader').fadeIn();
        jQuery(form_resp).html('');

        if( message_area < 1 ){
        	jQuery(form_resp).append('<p class="ferror fcenter">Error: You need to add a message.</p>').fadeIn();
        	jQuery(this_form).parent().parent().find('.loader').fadeOut();
        	doAjax = false;
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
	              	jQuery(this_form).parent().parent().find('.loader').fadeOut();
	              	jQuery(form_resp).html(data.message);

	               if(data.message_alt){
	               		if(data.done == true && data.content_new){
	               			jQuery('#messages').append(data.content_new);
	               			jQuery(this_form).find('textarea').val('');
                            console.log(jQuery(this_form).find('input[type=file]'));
                            jQuery(this_form).find('input[type=file]').val('');
	               		}
	               		jQuery(form_resp).html(data.message_alt);
	               		var wtf = jQuery('.wrappmessages');
    					var height = wtf[0].scrollHeight;
    					wtf.scrollTop(height);
	               }
	            },
	            error: function(jqXHR, textStatus, errorThrown) {
	            	jQuery(this_form).parent().parent().find('.loader').fadeOut();
	            }
		    });	
		}

    });

    /********************************************* Messages: submit NEW msg  *********************************************/
    jQuery(document).on('submit','#new_message',function(e){
        e.preventDefault();

        var form_resp       = '.answer_replay';
        var form_name       = '#new_message';
        var doAjax          = true;
        var values          = jQuery(form_name).serializeArray();
        var valuesAjax      = jQuery(form_name).serialize(); //.serialize();
        var type            = "text";
        var errors          = new Array();
        var formData        = new FormData(jQuery(form_name)[0]);
        jQuery(form_name).parent().parent().find('.loader').fadeIn();
        jQuery(form_resp).html('');

        $.each(jQuery(form_name).find('input, textarea, select'), function (i, el){
            fieldName = el.name;
            fieldValue = el.value;
            var element = jQuery('[name="'+el.name+'"]');

            // get field type to know what we are checking
            type = jQuery('[name="'+el.name+'"]').attr('type');
            if (type == "hidden" || fieldName == 'action' ) { // || typeof type == "undefined"
                // skip error checking
                return;
            } 
            switch(fieldName) {
                case "message":
                    if (fieldValue.length < 1)  {
                        errors[i] = "You can not leave " + el.name.replace(/_/g , " ") + " empty!";
                    }
                    doAjax = false;
                case "recipient":
                case "project":
                    if (fieldValue.length < 1)  {
                        errors[i] = "You can not leave " + el.name.replace(/_/g , " ") + " empty!";
                    }
                    doAjax = false;
                break;
                default:  break;   
            }

            if (  typeof  errors[i] != "undefined" &&  errors.length  && errors[i].length != 0 )  {
                jQuery( form_resp ).append('<p class="ferror fcenter">Error: '+errors[i]+'</p>').fadeIn();
                doAjax = false;
            }
            errors.splice(i);
        });

        if (errors.length == 0 )  { // errors.length || 
            doAjax = true;
            e.preventDefault();
           
        }
        
        if (doAjax === true) {
            // ajax to create new message 
            jQuery.ajax({
                type: 'POST',
                dataType: 'json', 
                url: ajaxUrl,
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) { 
                    jQuery(form_name).parent().parent().find('.loader').fadeOut();
                    if(data.done != true){
                        if(!jQuery.isEmptyObject(data.message)){
                            $.each(data.message, function(key, value){
                                jQuery( form_resp ).append(value).fadeIn();
                            });
                        }
                    }else  {
                        if(!jQuery.isEmptyObject(data.success)){
                            jQuery( form_resp ).append(data.success.notice).fadeIn();
                            jQuery('#messages').html(data.success.content_add);
                            setTimeout(function(){
                                window.location.href = data.success.link;
                            }, 2000); 
                        }
                    }
                },
                error: function(data) {
                    jQuery(form_name).parent().parent().find('.loader').fadeOut();
                    jQuery(form_resp).html(data.message);
                }
            });
        }

    });
    
    /********************************************* Messages: submit NEW msg => User no logged *********************************************/
    jQuery(document).on('submit','#contact_customer_form',function(e){
        e.preventDefault();
        var form_resp       = '.answer_contact_customer';
        var form_name       = '#contact_customer_form';
        var doAjax          = true;
        var values          = jQuery(form_name).serializeArray();
        var valuesAjax      = jQuery(form_name).serialize(); //.serialize();
        var type            = "text";
        var errors          = new Array();
        var formData        = new FormData(jQuery(form_name)[0]);
        jQuery(form_name).parent().parent().find('.loader').fadeIn();
        jQuery(form_resp).html('');
        
        $.each(jQuery(form_name).find('input, textarea, select'), function (i, el){
            fieldName = el.name;
            fieldValue = el.value;
            var element = jQuery('[name="'+el.name+'"]');

            // get field type to know what we are checking
            type = jQuery('[name="'+el.name+'"]').attr('type');
            if (type == "hidden" || fieldName == 'action' ) { // || typeof type == "undefined"
                // skip error checking
                return;
            } 
            switch(fieldName) {
                case "contact_contractor_name":
                    if (fieldValue.length < 1)  {
                        errors[i] = "You can not leave name empty!";
                    }
                    doAjax = false;
                    break;
                case "contact_contractor_email":
                    if (fieldValue.length < 1)  {
                        errors[i] = "You can not leave email empty!";
                        doAjax = false;
                    }else if( validateEmail(fieldValue) == false){
                        errors[i] = "Invalid email address!";
                        doAjax = false;
                    }
                   
                    break;
                case "contact_contractor_message":
                    if (fieldValue.length < 1)  {
                        errors[i] = "You can not leave message empty!";
                        doAjax = false;
                    }else if( fieldValue.length  < 50 && fieldValue.length > 1 ){
                        errors[i] = "Your message should have at least 50 characters.";
                        doAjax = false;
                    }
                  
                    break;
                default:  break;            
            }
            if (  typeof  errors[i] != "undefined" &&  errors.length  && errors[i].length != 0 )  {
                jQuery( form_resp ).append('<p class="ferror fcenter">Error: '+errors[i]+'</p>').fadeIn();
                doAjax = false;
            }
            errors.splice(i);
        });


        if (errors.length == 0 )  { // errors.length || 
            doAjax = true;
            e.preventDefault();
        }

        if (doAjax === true) {
            // ajax to create new message 
            jQuery.ajax({
                type: 'POST',
                dataType: 'json', 
                url: ajaxUrl,
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) { 
                    jQuery(form_name).parent().parent().find('.loader').fadeOut();
                    if(data.done != true){  
                        if(!jQuery.isEmptyObject(data.message)){
                            $.each(data.message, function(key, value){
                                jQuery( form_resp ).append(value).fadeIn();
                            });
                        }
                    }else{
                        // open the register form;
                        jQuery( form_resp ).append('<p class="fsuccess fcenter">You need to register and after that your message will be sent.</p>').fadeIn();
                        setTimeout(function(){
                                jQuery( ".fancybox  a[href='#sign-up-popup']" ).trigger("click");
                        }, 2000); 
                        
                    }
                },
                error: function(data) {
                    jQuery(form_name).parent().parent().find('.loader').fadeOut();
                    jQuery(form_resp).html(data.message);
                }
            });
        }
    });

    /********************************************* Messages: populate select // populate_projects_by_user // populate_customer_by_user  *********************************************/
    jQuery('#populate_customer_by_user').on('change', function (e) {
        var optionSelected = jQuery("option:selected", this);
        var valueSelected = this.value;
        
        var dataform = new FormData();
        dataform.append('action', "get_projects_by_user"); 
        dataform.append('user_id', valueSelected);

        jQuery('#waitloadproj').fadeIn();
        jQuery('#selectproject').html('');
        jQuery('#populate_projects_by_user').html('');
        
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',  
            url: ajaxUrl,
            data: dataform,
            processData: false,
            contentType: false, 
            success: function(data) {
                jQuery('#populate_projects_by_user').append(data.message);
                jQuery('#waitloadproj').fadeOut();
                jQuery('#selectproject').html(data.first_option);
            },
            error: function(data) {
                jQuery('#populate_projects_by_user').append('<option value="0" disabled="disabled" selected="selected">Please select a project</option>');
                jQuery('#waitloadproj').fadeOut();
                jQuery('#selectproject').html('Please select a project');
            }
        }); 

        
    });

});


// scroll to bottom of this section
jQuery(window).load(function() {

  	if ( jQuery( ".wrappmessages" ).length ) {
	    var wtf = jQuery('.wrappmessages');
	    var height = wtf[0].scrollHeight;
	    wtf.scrollTop(height);
	}

    // remove "new from divs"
    setTimeout( function(){ 
    	jQuery("#messages .message-item").each(function(){
	   		if(jQuery(this).hasClass('new')){
	   			jQuery(this).removeClass('new');
	   		}
		});

		// call ajax to update fields from this post type
        if( jQuery("#msg").length ){
            var id_msg = jQuery("#msg").val();
            var formData = new FormData();
                formData.append('action', "update_as_read_specific_message");
                formData.append('id', id_msg);
                        
            jQuery.ajax({
                type: 'POST',
                dataType: 'json', 
                url: ajaxUrl,
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    jQuery('.newmessages').html(data.no);
                },
                error: function(data) {
                   console.log('error on update messages');
                }
            });
        }
  	}, 2000);


    // call ajax to update message nr 
    var formData2 = new FormData();
        formData2.append('action', "update_no_message");
    jQuery.ajax({
        type: 'POST',
        dataType: 'json', 
        url: ajaxUrl,
        data: formData2,
        processData: false,
        contentType: false,
        success: function(data) {
            jQuery('.newmessages').html(data.nr);
            jQuery('.newbids').html(data.bids);
        },
        error: function(data) {
           console.log('error on update no messages');
        }
    });       


});