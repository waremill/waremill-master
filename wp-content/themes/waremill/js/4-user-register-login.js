jQuery(document).ready(function($){
 

	/********************************************* login user  *********************************************/
    jQuery(document).on('submit','form.login_form',function(e){
        e.preventDefault();

        var form_name = '.login_form'; // id form
        var form_resp = '.responsecheck_login';
        var this_form = jQuery(this);
        var redirect = jQuery(form_name).attr('data-redirect');
        var formData = new FormData(jQuery(this_form)[0]);
        var login_email = jQuery(this_form).find('input[name="login_email"]').val();
        var login_password = jQuery(this_form).find('input[name="login_password"]').val();

        jQuery(this_form).find(form_resp).html('');
        jQuery(this_form).parent().parent().find('.loader').fadeIn();

        if( login_email.length > 0 || login_password.length > 0 ){
	    	jQuery.ajax({
	            type: 'POST',
	            dataType: 'json', 
	            url: ajaxUrl,
	            data: formData,
	            processData: false,
	            contentType: false,
	            success: function(data) {
	                jQuery(this_form).parent().parent().find('.loader').fadeOut();
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
	                jQuery(form_resp).html(data.message);
	            }
	        });
        }else {
        	jQuery(this_form).find(form_resp).html('<p class="ferror fcenter">Error: Please check all the fields.</p>');
            jQuery(this_form).parent().parent().find('.loader').fadeOut();
        }
    });

	
	/********************************************* register user  *********************************************/
  	jQuery(document).on('submit','#register_form',function(e){
        e.preventDefault();

        var form_name = '#register_form'; 
        var form_resp = '.responsecheck_register';
        var formData = new FormData(jQuery(form_name)[0]);
        var values = jQuery(form_name).serializeArray();
        var doAjax = false;
        
        jQuery(form_resp).html('');
        jQuery(form_name).parent().parent().find('.loader').fadeIn();

        var register_first_name 	= jQuery('input[name="register_first_name"]').val();
        var register_family_name	= jQuery('input[name="register_family_name"]').val();
        var register_company_name 	= jQuery('input[name="register_company_name"]').val(); 
        var register_email 			=  jQuery('input[name="register_email"]').val(); 
        var register_password 		= jQuery('input[name="register_password"]').val();
        var register_password2 		= jQuery('input[name="register_password2"]').val();

        if( register_first_name.length > 0 || register_family_name.length > 0 || register_email.length > 0  ||  register_password.length > 0 ||  register_password2.length > 0 ){
		   
		   if( validateEmail(register_email) == true ){
		        
		        if( register_password == register_password2){
		            
		            if( register_password.length  > 5 &&  register_password2.length >5 ){

		               doAjax = true;  

		            }else{
	                    jQuery('body').find(form_resp).html('<p class="ferror fcenter">Error: The passwords should contain more than 5 characters.</p>');
	                    jQuery(form_name).parent().parent().find('.loader').fadeOut();
	                    doAjax = false; 
		            }

			    }else{
	                jQuery('body').find(form_resp).html('<p class="ferror fcenter">Error: The passwords do not match.</p>');
	                jQuery(form_name).parent().parent().find('.loader').fadeOut();
	                doAjax = false;
	    		}
			    	
		    }else{
				jQuery('body').find(form_resp).html('<p class="ferror fcenter">Error: Invalid email address.</p>');
	            jQuery(form_name).parent().parent().find('.loader').fadeOut();
	            doAjax = false;
		   } 

        }else{
        	jQuery('body').find(form_resp).html('<p class="ferror fcenter">Error: Please check all the fields.</p>');
            jQuery(form_name).parent().parent().find('.loader').fadeOut();
            doAjax = false;
        }

        if(doAjax == true){
	    	jQuery.ajax({
	            type: 'POST',
	            dataType: 'json',
	            url: ajaxUrl,
	            data: formData ,
	            processData: false,
	            contentType: false,
	            success: function(data) {
	                jQuery(form_name).parent().parent().find('.loader').fadeOut();
	                jQuery(form_resp).html('');
	                jQuery(form_resp).html(data.message);
	                jQuery(form_name)[0].reset();
	                // if(data.redirect == true){
	                //     setTimeout(function(){
	                //         window.location.href = redirect;
	                //     }, 2000);                       
	                // }
	            },
	            error: function(data) {
	                jQuery(form_name).parent().parent().find('.loader').fadeOut();
	                jQuery(form_resp).html(data.message);
	              
	            }
	        });
	    }

    });    

  	
  	/********************************************* forgot password   *********************************************/
    jQuery(document).on('submit','form#forgot_email',function(e){
        e.preventDefault();

        var form_name = '#forgot_email'; // id form
        var form_resp = '.responsecheck_forgot';
        var this_form = jQuery(this);
        var formData = new FormData(jQuery(this_form)[0]);
        var forgot_email = jQuery('input[name="forgot_email"]').val();
       
        jQuery(this_form).find(form_resp).html('');
       	jQuery(this_form).parent().parent().find('.loader').fadeIn();

        if( forgot_email.length > 0  && validateEmail(forgot_email) == true){
	    	jQuery.ajax({
	            type: 'POST',
	            dataType: 'json', 
	            url: ajaxUrl,
	            data: formData,
	            processData: false,
	            contentType: false,
	            success: function(data) {
	               	jQuery(this_form).parent().parent().find('.loader').fadeOut();
	                jQuery(form_resp).html('');
	                jQuery(form_resp).html(data.message);
	                
	            },
	            error: function(data) {
	                jQuery(this_form).parent().parent().find('.loader').fadeOut();
	                jQuery(form_resp).html(data.message);
	            }
	        });
        }else {
        	jQuery('body').find(form_resp).html('<p class="ferror fcenter">Error: Invalid email address</p>');
            jQuery(this_form).parent().parent().find('.loader').fadeOut();
        }
    });

    
    /********************************************* login with google   *********************************************/
    var googleUser = {};
	var startApp = function(e) {
	    gapi.load('auth2', function(){
	      	// Retrieve the singleton for the GoogleAuth library and set up the client.
	     	auth2 = gapi.auth2.init({
	        	client_id: '608539247509-0jjbusfa3r2hm6tqcc10i70ro0ltgv74.apps.googleusercontent.com',
	        	cookiepolicy: 'single_host_origin',
	        	// Request scopes in addition to 'profile' and 'email'
	        	//scope: 'additional_scope'
	     	});
	      	//attachSignin(document.getElementById('customBtn'));
	      	attachSignin(document.getElementById(e));
	    });
	};

	function attachSignin(element) {
	    
		var type 			= jQuery("#" + element.id).attr('data-type');
		var parrent_loader 	= jQuery("#" + element.id).parent().parent().parent().parent().parent().parent(); // .contentloader 
    	var form_resp 		= jQuery("#" + element.id).parent().parent().find('.errorgplus');

	    auth2.attachClickHandler(element, {},
	        function(googleUser) {
	            var data_google = "action=login_user_google&type="+type+"&name=" + 
	            					googleUser.getBasicProfile().getGivenName() + "&last=" + 
	            					googleUser.getBasicProfile().getFamilyName()+"&email="+
	            					googleUser.getBasicProfile().getEmail() +"&user_id=" + googleUser.getBasicProfile().getId();
	            parrent_loader.find('.loader').fadeIn();					
	            jQuery.ajax({
		            type: 'GET',
		            dataType: 'json', 
		            url: ajaxUrl,
		            data:data_google,
		            processData: false,
		            contentType: false,
		            success: function(data) {
            			parrent_loader.find('.loader').fadeOut();
		                form_resp.html('');
		                form_resp.html(data.message);
		                console.log(data.message);
		                if(data.redirect == true){
		                	setTimeout(function(){
							    window.location.href = data.link;
							}, 2000);	   
		                }
		               
		            },
		            error: function(data) {
		               	parrent_loader.find('.loader').fadeOut();
		                form_resp.html('');
		                form_resp.html(data.message);
		            }
		        });

	        }, function(error) {
	          	//alert(JSON.stringify(error, undefined, 2));
	          	parrent_loader.find('.loader').fadeOut();
		        form_resp.html('');
		        form_resp.html(JSON.stringify(error, undefined, 2));
	        });
	}

	
	jQuery('.google').on('click', function (e) { 
    	e.preventDefault();
    	var this_item = jQuery(this).attr('id');
    	startApp(this_item);
    });


});
