jQuery(document).ready(function($){
	// invitation page 
	$('#search').multiselect({
        search: {
            left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
            right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
        },
        fireSearch: function(value) {
            return value.length > 3;
        }
    });

    // invitation submit   
    jQuery(document).on('submit','.send_invitation_form',function(e){
        e.preventDefault();

        var form_name 		= '.send_invitation_form'; 
        var form_resp 		= '.responsecheck_send_invitations';
        var form_resp2 		= '.responsecheck_send_invitations2';
        var statusupdate 	= '#statusupdate'; 
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
        jQuery(form_resp2).html('');
        jQuery(statusupdate).html('');

       
        var to_select = jQuery(form_name).find('#search_to').val();
        //console.log(to_select);
        if (to_select != null && to_select.length > 0) {
        	doAjax = true;
        }else{
        	doAjax = false; 
        	jQuery(this_form).parent().parent().find('.loader').fadeOut();
        	jQuery(form_resp2).html("<p class='ferror fcenter'>Error: Please select some users!</p>");
        }
        
        if (doAjax === true) {

        	var size_to_select = to_select.length;
        	var contor = 0;
        	var contor_1 = 0; 
        
	        	$.each(to_select, function (i, item) {
	        		var formData = new FormData();
	            	formData.append('action', "send_invitation");
	            	formData.append('id', item);
	            	//console.log(formData);

	        		jQuery.ajax({
		                type: 'POST',
		                dataType: 'json', 
		                url: ajaxUrl,
		                data: formData,
		                processData: false,
		                contentType: false,
		                success: function(data) { 
		                    //console.log(data);
		                    jQuery(statusupdate).html((contor+1)+'/'+size_to_select);
		                    jQuery(form_resp).append('<p>'+(contor+1)+'. '+data.message+'</p>');
		                    contor++;
		                },
		                error: function(data) {
		                	
		                	jQuery(statusupdate).html((contor+1)+'/'+size_to_select);
		                	jQuery(form_resp).append('<p>'+(contor+1)+'. '+data.message+'</p>');
		                    //jQuery(this_form).parent().parent().find('.loader').fadeOut();
		                    //jQuery(form_resp).html(data.message);
		                    contor++;
		                }
		            });   
	        	});

        	jQuery(this_form).parent().parent().find('.loader').fadeOut();
        }
        //jQuery(this_form).parent().parent().find('.loader').fadeOut();
        e.preventDefault();

    });


	// generate token
	jQuery(document).on('submit','.generate_token_form',function(e){
		e.preventDefault();
        var form_name 	= '.generate_token_form'; 
        var form_resp 	= '.responsecheck_generate';
        var form_output = '#outputtoken'; 
        var this_form = jQuery(this);

        var formData = new FormData(jQuery(form_name)[0]);
        jQuery(this_form).parent().parent().find('.loader').fadeIn();

        jQuery(form_resp).html('');
        jQuery(form_output).html('');

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
        		jQuery(form_output).html(data.output);
	        },
	        error: function(data) {
	            jQuery(this_form).parent().parent().find('.loader').fadeOut();
	            jQuery(form_resp).html(data.message);
	        }
	    });
	});
	 

	// import CSV 
    jQuery(document).on('submit','.import_data_form',function(e){
        e.preventDefault();

        var form_name 	= '.import_data_form'; 
        var form_resp 	= '.responsecheck_import_data';
        var form_output = '#outputtoken'; 
        var this_form = jQuery(this);

        var formData = new FormData(jQuery(form_name)[0]);
        jQuery(this_form).parent().parent().find('.loader').fadeIn();

        jQuery(form_resp).html('');
        jQuery(form_output).html('');

		jQuery.ajax({
	        type: 'POST',
	        dataType: 'json', 
	        url: ajaxUrl,
	        data: formData,
	        processData: false,
	        contentType: false,
	        success: function(data) {
	            jQuery(this_form).parent().parent().find('.loader').fadeOut();
	           	//jQuery(form_resp).html(data.message);
        		//jQuery(form_resp).html(data.output);

        		
    			
    			$.each(data.message, function(key, value){
                    jQuery( form_resp ).append(value).fadeIn();
                });
	        },
	        error: function(data) {
	            jQuery(this_form).parent().parent().find('.loader').fadeOut();
	            jQuery(form_resp).html(data);
	        }
	    });

    }); 



});