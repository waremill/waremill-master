jQuery(document).ready(function($){

	
	/********************************************* Customer: update settings *********************************************/
	jQuery(document).on('submit','.update_customer_form',function(e){
        e.preventDefault();
        var form_name = '.update_customer_form'; 
        var form_resp = '.responsecheck_update_customer';
        var this_form = jQuery(this);

        var formData = new FormData(jQuery(form_name)[0]);
        //var formData = jQuery(this).serialize();
        var values = jQuery(form_name).serializeArray();
        var doAjax = false;

        jQuery(this_form).find(form_resp).html('');
        jQuery(this_form).parent().parent().find('.loader').fadeIn();

        var user_type                       = jQuery(this_form).find('input[name="user_type"]').val();

        var update_email_customer 			= jQuery(this_form).find('input[name="update_email_customer"]').val();
        var update_old_password_customer 	= jQuery(this_form).find('input[name="update_old_password_customer"]').val();
        var update_new_password_customer 	= jQuery(this_form).find('input[name="update_new_password_customer"]').val();
        var update_new_password_customer_2 	= jQuery(this_form).find('input[name="update_new_password_customer_2"]').val();

        if(user_type == "3"){
            if(update_old_password_customer.length == 0 ){
                jQuery('body').find(form_resp).html('<p class="ferror fcenter">Error: You need to enter your password.</p>');
                jQuery(form_name).parent().parent().find('.loader').fadeOut();
                doAjax = false;
            }
        }

        if(update_email_customer.length > 0 && validateEmail(update_email_customer) == true){
    		if( (update_new_password_customer.length == 0 && update_new_password_customer_2 == 0 ) || 
    			(update_new_password_customer.toLowerCase() === update_new_password_customer_2.toLowerCase() ))	{
    				doAjax = true; 
    		}else{
    			jQuery('body').find(form_resp).html('<p class="ferror fcenter">Error: The passwords aren\'t match.</p>');
        		jQuery(form_name).parent().parent().find('.loader').fadeOut();
        		doAjax = false;
    		}
        }else {
        	jQuery('body').find(form_resp).html('<p class="ferror fcenter">Error: An email address is required.</p>');
	        jQuery(form_name).parent().parent().find('.loader').fadeOut();
	        doAjax = false;
        }

       // console.log(formData);

        if(doAjax == true){
        	jQuery.ajax({
	            type: 'POST',
	            dataType: 'json', 
	            url: ajaxUrl,
	            data: formData,
	            processData: false,
	            contentType: false,
	            success: function(data) {
	                jQuery(this_form).parent().parent().find('.loader').fadeOut();
	                //jQuery('body').find('.loader').fadeOut();
	                jQuery(form_resp).html('');
	                jQuery(form_resp).html(data.message);
	                if(data.redirect == true){
	                	setTimeout(function(){
						    window.location.href = data.link;
						}, 2000);	   
	                }
	            },
	            error: function(data) {
	                jQuery(this_form).parent().parent().find('.loader').fadeOut();
	                //jQuery('body').find('.loader').fadeOut();
	                jQuery(form_resp).html(data.message);
	            }
	        });
        }

    });

    /********************************************* Customer: remove project *********************************************/
        
    jQuery(document).on('click', '.removeproject', function(e){ 
        e.preventDefault();
      
        jQuery(this).parent().find('.removemessage').fadeIn();
    });

    jQuery(document).on('click', '.nobutton', function(e){ 
        e.preventDefault();
        //jQuery(this).parent().parent().parent().parent().parent().parent().parent().find('.removemessage').fadeOut();
        var id = jQuery(this).attr('data-id');
        jQuery('body').find('.removemessage[data-id='+id+']').fadeOut();
    });
 
    jQuery(document).on('click', '.yesbutton', function(e){ 
        e.preventDefault();
        var id_project  = jQuery(this).attr('data-id');
        var old_text    = ".wrappbasictext[data-id="+id_project+"]";
        var form_resp   = ".answerremoveproject[data-id="+id_project+"]";
        var this_form   = ".wrappbasictext[data-id="+id_project+"]";

        jQuery(this_form).parent().parent().find('.loader').fadeIn();

        if(id_project.length>0){
            //var data_sent = "action=removeproject&id=" + id_project;
            var formData = new FormData();
            formData.append('action', "removeproject");
            formData.append('id', id_project);
            
            jQuery.ajax({
                type: 'POST',
                dataType: 'json', 
                url: ajaxUrl,
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    jQuery(this_form).parent().parent().find('.loader').fadeOut();
                    
                    if(data.done == true){
                        jQuery(old_text).html('');
                        jQuery(form_resp).html('');
                        jQuery(form_resp).html(data.message);
                        setTimeout(function(){
                            location.reload();
                        }, 2000);   

                    }else{
                        jQuery(form_resp).html('');
                        jQuery(form_resp).html(data.message);
                    }
                    
                },
                error: function(data) {
                    jQuery(this_form).parent().parent().find('.loader').fadeOut();
                    //jQuery('body').find('.loader').fadeOut();
                    jQuery(form_resp).html(data.message);
                }
            }); 
        }
    });
    



    /********************************************* Customer: add new project *********************************************/
    jQuery(document).on('submit','#customer_createproject_form',function(e){
        e.preventDefault();

        var form_name = '#customer_createproject_form'; 
        var form_resp = '.responsecheck_addproject_customer';

        var this_form = jQuery(this);
        var formData = new FormData(jQuery(form_name)[0]);

        var values = jQuery(form_name).serializeArray();
        var doAjax = false;

        jQuery(this_form).find(form_resp).html('');
        jQuery(this_form).parent().parent().find('.loader').fadeIn();

        var customer_project_name = jQuery(this_form).find('input[name="new_project_customer"]').val();
        var customer_industry     = jQuery(this_form).find('select[name="new_project_industries[]"]').val();
        var customer_service      = jQuery(this_form).find('select[name="new_project_services[]"]').val();
       // var customer_material     = jQuery(this_form).find('input[name="new_project_material"]').val();
        var customer_description  = jQuery(this_form).find('textarea[name="new_project_description"]').val();
        var customer_color        = jQuery(this_form).find('input[name="new_project_color"]').val();
        var customer_expdate      = jQuery(this_form).find('input[name="new_project_expire_date"]').val();
        var customer_devivey      = jQuery(this_form).find('input[name="new_project_deadline_date"]').val();
        var customer_qnt          = jQuery(this_form).find('input[name="new_project_quantity"]').val();
        var customer_country      = jQuery(this_form).find('select[name="new_project_country"]').val();

        //console.log(customer_service);

        if( customer_project_name.length > 0 && customer_industry.length > 0 && 
                //customer_service.length > 0 && customer_material.length > 0  && 
                    customer_description.length > 100 && customer_color.length > 0 &&  
                        customer_expdate.length > 0 && customer_devivey.length > 0  && 
                             customer_qnt.length > 0 && customer_country 
             ){
            doAjax = true;
        }else{
            jQuery('body').find(form_resp).html('<p class="ferror fcenter">Error: Please check all the required fields.</p>');
            jQuery(form_name).parent().parent().find('.loader').fadeOut();
            doAjax = false;
        }

        jQuery('.add-project-answer').html('');
        if(doAjax == true){
            jQuery.ajax({
                type: 'POST',
                dataType: 'json', 
                url: ajaxUrl,
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    jQuery(this_form).parent().parent().find('.loader').fadeOut();
                    //jQuery('body').find('.loader').fadeOut();
                    jQuery(form_resp).html('');
                    jQuery(form_resp).html(data.message);

                    if(data.done == true){
                        jQuery(form_name)[0].reset();
                        jQuery('.wrappselectitems').html('');
                       // jQuery('select.SlectBox  option:selected').each(function () {
                        jQuery('select.styled').each(function () {
                            jQuery(this).find('option:first').attr('selected','selected'); //.prop('selectedIndex',0);
                            var first_val = jQuery(this).find("option:first").val(); 
                            jQuery(this).parent().find('span').html(first_val); 
                           // console.log(first_val);
                        }); 
                        
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

                        if(data.id != false){ // if project was successufy added 
                            // triger click register button 
                            jQuery('.add-project-answer').html(data.message);
                            jQuery('a[href="#sign-up-popup"]').trigger( "click" );
                        }
                        
                    }
                    
                    
                   // jQuery('select.SlectBox')[0].sumo.unSelectAll(); //.reload();
                    //jQuery(form_name).trigger("reset");
                },
                error: function(data) {
                    jQuery(this_form).parent().parent().find('.loader').fadeOut();
                    //jQuery('body').find('.loader').fadeOut();
                    jQuery(form_resp).html(data.message);
                }
            });
        }

    }); 

    /********************************************* Customer: add new project *********************************************/
    jQuery('.submitformaction').each(function() {
        jQuery(this).on('change', function() {
            jQuery('#search-archive').submit();
        });
    });

    /********************************************* Customer: edit project // remove file *********************************************/
    jQuery(document).on('click', '.removefile', function(e){ 
        e.preventDefault();
        jQuery(this).parent().parent().remove();
    });

    /********************************************* Customer: edit project *********************************************/
    jQuery(document).on('submit','#customer_editproject_form',function(e){
        e.preventDefault();

        var form_name = '#customer_editproject_form'; 
        var form_resp = '.responsecheck_editproject_customer';

        var this_form = jQuery(this);
        var formData = new FormData(jQuery(form_name)[0]);

        var values = jQuery(form_name).serializeArray();
        var doAjax = false;

        jQuery(this_form).find(form_resp).html('');
        jQuery(this_form).parent().parent().find('.loader').fadeIn();

        var customer_project_name = jQuery(this_form).find('input[name="new_project_customer"]').val();
        var customer_industry     = jQuery(this_form).find('select[name="new_project_industries[]"]').val();
        var customer_service      = jQuery(this_form).find('select[name="new_project_services[]"]').val();
        //var customer_material     = jQuery(this_form).find('input[name="new_project_material"]').val();
        var customer_description  = jQuery(this_form).find('textarea[name="new_project_description"]').val();
        var customer_color        = jQuery(this_form).find('input[name="new_project_color"]').val();
        var customer_expdate      = jQuery(this_form).find('input[name="new_project_expire_date"]').val();
        var customer_devivey      = jQuery(this_form).find('input[name="new_project_deadline_date"]').val();
        var customer_qnt          = jQuery(this_form).find('input[name="new_project_quantity"]').val();
        var customer_country      = jQuery(this_form).find('select[name="new_project_country"]').val();

        //console.log(customer_service);

        if( customer_project_name.length > 0 && customer_industry.length > 0 && 
                //customer_service.length > 0 && customer_material.length > 0  && 
                    customer_description.length > 100 && customer_color.length > 0 &&  
                        customer_expdate.length > 0 && customer_devivey.length > 0  && 
                             customer_qnt.length > 0 && customer_country 
             ){
            doAjax = true;
        }else{
            jQuery('body').find(form_resp).html('<p class="ferror fcenter">Error: Please check all the required fields.</p>');
            jQuery(form_name).parent().parent().find('.loader').fadeOut();
            doAjax = false;
        }


        if(doAjax == true){
            jQuery.ajax({
                type: 'POST',
                dataType: 'json', 
                url: ajaxUrl,
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    jQuery(this_form).parent().parent().find('.loader').fadeOut();
                    //jQuery('body').find('.loader').fadeOut();
                    jQuery(form_resp).html('');
                    jQuery(form_resp).html(data.message);

                    
                   // jQuery('select.SlectBox')[0].sumo.unSelectAll(); //.reload();
                    //jQuery(form_name).trigger("reset");
                },
                error: function(data) {
                    jQuery(this_form).parent().parent().find('.loader').fadeOut();
                    //jQuery('body').find('.loader').fadeOut();
                    jQuery(form_resp).html(data.message);
                }
            });
        }

    }); 

    /********************************************* Customer: contact customer  *********************************************/
    jQuery(document).on('click', '.contact_contractor', function(e){ 
        e.preventDefault();
        //console.log("asa");
        var project_id      = jQuery(this).attr('data-id');
        var contractor_id   = jQuery(this).attr('data-user');

        var redirect_answ  = '.redirect_answ';

        if(project_id.length > 0 && contractor_id > 0 ){
            //console.log(project_id);
            var effectoptions = '.effectoptions';
            jQuery(effectoptions).find('.loader').fadeIn();

            var formData = new FormData();
            formData.append('action', 'action_redirect_customer');
            formData.append('project_id', project_id); 
            formData.append('user_id', contractor_id);
 
            jQuery( redirect_answ ).html('');

            jQuery.ajax({
                type: 'POST',
                dataType: 'json', 
                url: ajaxUrl,
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if(data.done == true){
                        setTimeout(function(){
                            jQuery(effectoptions).find('.loader').fadeOut();
                            window.location.href = data.link;
                        }, 2000);   
                    }else{
                        jQuery(effectoptions).find('.loader').fadeOut();
                         if(!jQuery.isEmptyObject(data.message)){
                            $.each(data.message, function(key, value){
                                jQuery( redirect_answ ).append(value).fadeIn();
                            });
                        }
                    }
                },
                error: function(data) {
                    jQuery(effectoptions).find('.loader').fadeOut();
                    //jQuery(form_resp).html(data.message);
                }
            });
        }
    });

    // 
    /********************************************* Customer: activate draft projects  *********************************************/
    jQuery(document).on('click', '.activate-project', function(e){ 
        e.preventDefault();
        var id_project = jQuery(this).attr('data-id');
        if(id_project.length > 0){
            var formData = new FormData();
            formData.append('action', 'action_activate_project');
            formData.append('project_id', id_project); 
 
           // jQuery( '.redirect_answ' ).html('');
            var redirect_answ  = '.outputvalidate';
            jQuery('.project-item[data-project="'+ id_project +'"] ').find('.loader').fadeIn();

            jQuery.ajax({
                type: 'POST',
                dataType: 'json', 
                url: ajaxUrl,
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if(data.done == true){
                        setTimeout(function(){
                            location.reload();
                        }, 2000);   
                    }else{
                        jQuery('.project-item[data-project="'+ id_project +'"] ').find('.loader').fadeOut();
                        if(!jQuery.isEmptyObject(data.message)){
                            $.each(data.message, function(key, value){
                                jQuery('.project-item[data-project="'+ id_project +'"] ').find( redirect_answ ).append(data.message).fadeIn();
                            });
                        }
                    }

                },
                error: function(data) {
                     jQuery('.project-item[data-project="'+ id_project +'"] ').find('.loader').fadeOut();
                    //jQuery(form_resp).html(data.message);
                }
            }); 
        }

    });

    jQuery(document).on('click', '.confirmation_hire_contractor', function(e){ 
        e.preventDefault();
     
        jQuery(this).parent().find('.confirmation-hider').fadeIn();
        return false;
    })

    jQuery(document).on('click','.nohirebutton', function(e){
        e.preventDefault();
        jQuery('.effectoptions').find('.confirmation-hider').fadeOut();
        return false;
    });

    
    jQuery(document).on('click', '.hire_contractor', function(e){ 
        e.preventDefault();
        var project_id      = jQuery(this).attr('data-id');
        var contractor_id   = jQuery(this).attr('data-user');
        var bid_id          = jQuery(this).attr('data-bid');

        var redirect_answ  = '.redirect_answ';
        jQuery( redirect_answ ).html('');

        if(project_id.length > 0 && contractor_id > 0 ){

            var formData = new FormData();
            formData.append('action', 'action_hire_customer');
            formData.append('project_id', project_id); 
            formData.append('user_id', contractor_id);
            formData.append('bid_id', bid_id);

            
            jQuery('.effectoptions.wrappload .loader').fadeIn();

            jQuery.ajax({
                type: 'POST', 
                dataType: 'json', 
                url: ajaxUrl,
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if(data.done == true){
                        setTimeout(function(){
                            jQuery('.effectoptions.wrappload .loader').fadeOut();
                           // jQuery('.bid-item[data-id="'+project_id+'"]').addClass('bid-choose');
                            //jQuery('.bid-item[data-id="'+project_id+'"]').find('.image').append('<i class="fa fa-check" aria-hidden="true"></i>');
                           location.reload();
                        }, 2000);   
                    }else{
                        jQuery('.effectoptions.wrappload .loader').fadeOut();
                        jQuery(effectoptions).find('.loader').fadeOut();
                         if(!jQuery.isEmptyObject(data.message)){
                            $.each(data.message, function(key, value){
                                jQuery( redirect_answ ).append(value).fadeIn();
                            });
                        }
                    }
                },
                error: function(data) {
                    jQuery('.effectoptions.wrappload .loader').fadeOut();
                    //jQuery(form_resp).html(data.message);
                }
            });
        }
    });

});