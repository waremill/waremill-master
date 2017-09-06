jQuery(document).ready(function($){

    /********************************************* Constractor :  Bid Project  *********************************************/
    jQuery(document).on('submit','.bidding_form',function(e){
        e.preventDefault();

        var form_name = '.bidding_form'; 
        var form_resp = '.responsecheck_bidding';
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

        $.each(jQuery(form_name).find('input'), function (i, el){
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
                case "price":
                case "delivery_date":
                    if (fieldValue.length < 1)  {
                        errors[i] = "<p class='ferror fcenter'>You can not leave " + el.name.replace(/_/g , " ") + " empty!</p>";
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

           jQuery.ajax({
                type: 'POST',
                dataType: 'json', 
                url: ajaxUrl,
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) { 
                    //console.log(data);
                    if(!jQuery.isEmptyObject(data.message)){
                        $.each(data, function(key, value){
                            $.each(value, function(key, value){
                                if(key == 'done' && value == true){ // reset form only in case of success
                                    jQuery(form_name)[0].reset();
                                    setTimeout(function(){
                                        location.reload();
                                    }, 2000);   
                                }
                                if(key != 'done'){
                                    jQuery( form_resp ).append(value).fadeIn();
                                }
                            });
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

    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    jQuery('.devdate').fdatepicker({
        format: 'dd/mm/yyyy',
        disableDblClickSelection: true,
        onRender: function (date) {
            return date.valueOf() < now.valueOf() ? 'disabled' : '';
        }
    });

    /********************************************* Constractor :  End Bid Project  *********************************************/

    jQuery(document).on('click','.bids-listing .bid-item',function(e){

        var current = jQuery(this);

        current.find(".hidden-content").slideToggle();
        current.find(".view-more i").toggleClass('fa-angle-down fa-angle-up');

        if(current.hasClass("new")){
            var id_bid = current.attr('data-id');
            var id_project = current.attr('data-project');

            var formData = new FormData();
            formData.append('action', "markasread");
            formData.append('id', id_bid);
            formData.append('project_id', id_project);
            
            jQuery.ajax({
                type: 'POST',
                dataType: 'json', 
                url: ajaxUrl,
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if(data.done == true){
                        current.removeClass("new");  
                        jQuery('.newbids').html(data.new_bids);

                    }
                },
                error: function(data) { }
            }); 
        }
    });

	/********************************************* Constractor :  settings - add more  *********************************************/
	jQuery(document).on('click','.additems',function(e){
        e.preventDefault();
        var data_item = jQuery(this).attr('data-item');

       // console.log(data_item);
        switch (data_item) {
        	case "certificates":

                var items = jQuery(".placehere[data-item='"+data_item+"'] .row").length;
                var duplicate = jQuery('.duplicate[data-item="'+data_item+'"]>div');
                var clone = duplicate.clone();

                clone.html(clone.html().replace('document', 'document' + items));
                clone.appendTo(".placehere[data-item='"+data_item+"']");

            	//duplicate.clone().appendTo(".placehere[data-item='"+data_item+"']");

                break;
            case "portofolio":
            	//  console.log(data_item); 
            	var items = jQuery(".placehere[data-item='"+data_item+"'] .row").length;
            	var duplicate = jQuery('.duplicate[data-item="'+data_item+'"]>div');
            	var clone = duplicate.clone()
            	clone.html(clone.html().replace('images', 'images' + items));
            	clone.appendTo(".placehere[data-item='"+data_item+"']");

                break;
            default: break;      
        }

    });

    /********************************************* Constractor :  settings - remove more  *********************************************/
    jQuery(document).on('click','.removenewadd',function(e){
        e.preventDefault();
        jQuery(this).parent().remove();
    });

    
    /********************************************* Constractor :  settings - upload  *********************************************/
    jQuery(document).on('change','.upload',function(e){
        //e.preventDefault();
        var files = jQuery(this).prop("files")
	    var names = $.map(files, function (val) { return val.name; });
	    var all_files = "";
	    $.each(names, function (i, name) {
	    	all_files += name + "<br/>";
	        //console.log(name);
	    });
	    if(all_files.length > 0){ 
	    	jQuery(this).parent().parent().parent().parent().find('.filesname').html(all_files);
		}else{
			jQuery(this).parent().parent().parent().parent().find('.filesname').html("");
		}
    });


	/********************************************* Constractor :  settings - remove  *********************************************/
	jQuery(document).on('click','.removebutton',function(e){
        e.preventDefault();
        var data_item = jQuery(this).attr('data-item');
        jQuery(this).parent().parent().find('div[data-item="'+data_item+'"]').remove();
    });

	/********************************************* Constractor :  settings  *********************************************/
    jQuery(document).on('submit','.update_contractor_settings_form',function(e){
        e.preventDefault();

        var form_name = '.update_contractor_settings_form'; 
        var form_resp = '.responsecheck_update_settings_contractor';
        var this_form = jQuery(this);

        jQuery(form_name).find(form_resp).html(''); // empty response

        var formData = new FormData(jQuery(form_name)[0]);
        var values = jQuery(form_name).serializeArray();
        var valuesAjax = jQuery(form_name).serialize(); //.serialize();
        var type = "text";
        var errors = new Array();
        var doAjax = false;
        var fieldName = fieldValue = "";

        jQuery(this_form).parent().parent().find('.loader').fadeIn();

        var pass    = jQuery(form_name).find("input[name='contractor_password']").val();
        var user_type = jQuery(form_name).find("input[name='user_type']").val();

        $.each(jQuery(form_name).find('input, select, textarea'), function (i, el){
            fieldName = el.name;
            fieldValue = el.value;
            var element = jQuery('[name="'+el.name+'"]');

            // get field type to know what we are checking
            type = jQuery('[name="'+el.name+'"]').attr('type');
            if (type == "hidden" || fieldName == 'action' ) { // || typeof type == "undefined"
                // skip error checking
                return;
            } 

           // console.log(fieldName + " - " + fieldValue + " - " + type);
            switch(fieldName) {
                case "first_name":
                case "last_name":
                case "email":
                //case "company_name":
                //case "contact_person_name": 
                //case "contact_person_email":
                case "country":
                case "city":
                    if (fieldValue.length < 1)  {
                        errors[i] = "<p class='ferror fcenter'>You can not leave " + el.name.replace(/_/g , " ") + " empty!</p>";
                    }
                    doAjax = false;
                break;
                case "industry[]":
                case "service[]":
                    if(fieldValue.length == 0  ){
                         errors[i] = "<p class='ferror fcenter'>Please select at least a " + el.name.replace(/_/g , " ").replace("[]" , " ") + " !</p>";
                    }
                    break;
                case "additional_text":
                    if ( (fieldValue.length >= 0  && fieldValue.length < 100)  ||  (fieldValue.length > 100000) )  {
                        errors[i] = "<p class='ferror fcenter'>You need to add at between 100-100000 characters on " + el.name.replace(/_/g , " ") + "!</p>";
                    }   
                    doAjax = false;
                    break;
                default:  break;

            }

            switch(type) {
                case "email":  // check for email
                    if (!validateEmail(fieldValue)) {
                        if (!errors[i].length)  {
                            errors[i] = capitalizeFirstLetter(el.name.replace(/_/g , " ")) + " is not valid!";
                        }
                    }
                    break;
                case "tel": // check for numbers
                    if (!validatePhone(fieldValue)) {
                        errors[i] = capitalizeFirstLetter(el.name.replace(/_/g , " ")) + " is not valid!";
                    }
                    break;
                case "url":
                    if( fieldValue.length > 1 && isUrl(fieldValue) == false ){
                        errors[i] = capitalizeFirstLetter(el.name.replace(/_/g , " ")) + " is not valid!";
                    }
                    break;
                default:
                    // previous empty check is enough
            }

            if (  typeof  errors[i] != "undefined" &&  errors.length  && errors[i].length != 0 )  {
                jQuery( form_resp ).append('<p class="ferror fcenter">Error: '+errors[i]+'</p>').fadeIn();
                doAjax = false;
            }
           
            errors.splice(i);

        });

        if (errors.length == 0 )  { // errors.length || 
            if(user_type == "3"){
                // check password field required
                if(pass.length == 0){
                     doAjax = false;
                    jQuery(this_form).parent().parent().find('.loader').fadeOut(); 
                    jQuery( form_resp ).append('<p class="ferror fcenter">Error: You need to enter your password</p>').fadeIn();
                }else {
                    doAjax = true;
                }
            }else{
                doAjax = true;
            }
            
            e.preventDefault();
           
        }

       // console.log(doAjax + " " + errors.length); 

        if (doAjax === true) {
           jQuery.ajax({
                type: 'POST',
                dataType: 'json', 
                url: ajaxUrl,
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) { 
                    //console.log(data.message);
                    if(!jQuery.isEmptyObject(data.message)){
                        $.each(data, function(key, value){
                            $.each(value, function(key, value){
                                // console.log(key, value);
                                jQuery( form_resp ).append(value).fadeIn();
                            });
                        });
                    } 

         //            jQuery(form_resp).html('');
         //            jQuery(form_resp).html(data.message);
         //            if(data.redirect == true){
         //             setTimeout(function(){
                        //     window.location.href = data.link;
                        // }, 2000);       
         //            }
                    jQuery(this_form).parent().parent().find('.loader').fadeOut();
                },
                error: function(data) {
                    jQuery(this_form).parent().parent().find('.loader').fadeOut();
                    //jQuery('body').find('.loader').fadeOut();
                    jQuery(form_resp).html(data.message);
                }
            });
        }else{
             jQuery(this_form).parent().parent().find('.loader').fadeOut();
        }
        //jQuery(this_form).parent().parent().find('.loader').fadeOut();
        e.preventDefault();
    
    });

    /********************************************* Constractor :  change password  *********************************************/
    jQuery(document).on('submit','.changepassword_form',function(e){
        e.preventDefault();

        var form_name = '.changepassword_form'; 
        var form_resp = '.changepassword_resp';
        var this_form = jQuery(this);

        jQuery(form_name).find(form_resp).html(''); // empty response

        var formData = new FormData(jQuery(form_name)[0]);
        // var formData = jQuery(this).serialize();

        var values = jQuery(form_name).serializeArray();
        var valuesAjax = jQuery(form_name).serialize(); //.serialize();
        var type = "text";
        var errors = new Array();
        var doAjax = false;
        var fieldName = fieldValue = "";

        //console.log(valuesAjax);
        //console.log("=====");
        //console.log(values);

        jQuery(this_form).parent().parent().find('.loader').fadeIn();

        var pass    = jQuery(form_name).find("input[name='contractor_password']").val();
        var pass1   = jQuery(form_name).find("input[name='contractor_new_password']").val();
        var pass2   = jQuery(form_name).find("input[name='contractor_new_password2']").val();

        var user_type = jQuery(form_name).find("input[name='user_type']").val();

        var $next = true;

        if(pass1 != pass2){
            jQuery( form_resp ).append('<p class="ferror fcenter">Error: The passwords do not match!</p>').fadeIn();
            doAjax = false;
            $next = false;
        }else if(pass1 == pass2 && pass1.length < 5 && pass1 > 0) {
            jQuery( form_resp ).append('<p class="ferror fcenter">Error: The passwords should have at least 5 characters!</p>').fadeIn();
            doAjax = false;
            $next = false;
        }

        if( $next == true ){ 

            if(user_type == "3"){
                // check password field required
                if(pass.length == 0){
                     doAjax = false;
                    jQuery(this_form).parent().parent().find('.loader').fadeOut(); 
                    jQuery( form_resp ).append('<p class="ferror fcenter">Error: You need to enter your password</p>').fadeIn();
                }else {
                    doAjax = true;
                }
            }else{
                doAjax = true;
            }
            e.preventDefault();

           // console.log(doAjax + " " + errors.length); 

            if (doAjax === true) {
               jQuery.ajax({
                    type: 'POST',
                    dataType: 'json', 
                    url: ajaxUrl,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) { 
                        //console.log(data.message);
                        if(!jQuery.isEmptyObject(data.message)){
                            $.each(data, function(key, value){
                                $.each(value, function(key, value){
                                    // console.log(key, value);
                                    jQuery( form_resp ).append(value).fadeIn();
                                });
                            });
                        } 
                        jQuery(form_name).find("input[name='contractor_password']").val('');
                        jQuery(form_name).find("input[name='contractor_new_password']").val('');
                        jQuery(form_name).find("input[name='contractor_new_password2']").val('');

                        jQuery(this_form).parent().parent().find('.loader').fadeOut();
                    },
                    error: function(data) {
                        jQuery(this_form).parent().parent().find('.loader').fadeOut();
                        //jQuery('body').find('.loader').fadeOut();
                        jQuery(form_resp).html(data.message);
                    }
                });
            }
            //jQuery(this_form).parent().parent().find('.loader').fadeOut();
            e.preventDefault();
        }else{
            jQuery(this_form).parent().parent().find('.loader').fadeOut();
        }
    });

    /********************************************* Constractor :  change password and activate account  *********************************************/
    jQuery(document).on('submit','.form_get_new_password',function(e){
        e.preventDefault();

        var form_name = '.form_get_new_password'; 
        var form_resp = '.responsecheck_password';
        var this_form = jQuery(this);

        jQuery(form_name).find(form_resp).html(''); // empty response

        var formData = new FormData(jQuery(form_name)[0]);
        // var formData = jQuery(this).serialize();

        var values = jQuery(form_name).serializeArray();
        var valuesAjax = jQuery(form_name).serialize(); //.serialize();
        var type = "text";
        var errors = new Array();
        var doAjax = false;
        var fieldName = fieldValue = "";

        jQuery(this_form).parent().parent().find('.loader').fadeIn();
       
        var pass1   = jQuery(form_name).find("input[name='contractor_new_password']").val();
        var pass2   = jQuery(form_name).find("input[name='contractor_new_password2']").val();

        var $next = true;

        if(pass1 != pass2){
            jQuery( form_resp ).append('<p class="ferror fcenter">Error: The passwords do not match!</p>').fadeIn();
            doAjax = false;
            $next = false;
        }else if(pass1 == pass2 && pass1.length < 5 && pass1 > 0) {
            jQuery( form_resp ).append('<p class="ferror fcenter">Error: The passwords should have at least 5 characters!</p>').fadeIn();
            doAjax = false;
            $next = false;
        }else{
            doAjax = true;
        }

        if( $next == true ){ 

            e.preventDefault();
            //console.log(doAjax + " " + errors.length); 
            if (doAjax === true) {
               jQuery.ajax({
                    type: 'POST',
                    dataType: 'json', 
                    url: ajaxUrl,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) { 
                       // console.log(data);
                        if(!jQuery.isEmptyObject(data.message)){
                            $.each(data, function(key, value){ 
                                if(key == "message" ){
                                    //console.log(value);
                                    $.each(value, function(key, value){
                                        // console.log(key, value);
                                        jQuery( form_resp ).append(value).fadeIn();
                                    });
                                }
                            });
                        } 
                        
                        jQuery(this_form).parent().parent().find('.loader').fadeOut();


                        if( data.redirect == true){
                            setTimeout(function(){
                                window.location.href = data.link;
                               // jQuery( "a[href='#login-popup']" ).trigger( "click" );
                           }, 2000);      
                        }

                        // redirect to login page

                    },
                    error: function(data) {
                        jQuery(this_form).parent().parent().find('.loader').fadeOut();
                        //jQuery('body').find('.loader').fadeOut();
                        jQuery(form_resp).html(data.message);
                    }
                });
            }
            //jQuery(this_form).parent().parent().find('.loader').fadeOut();
            e.preventDefault();
        }else{
            jQuery(this_form).parent().parent().find('.loader').fadeOut();
        }
    });

    /********************************************* Constractor :   activate account = delete account *********************************************/
    jQuery(document).on('click','#remove_account',function(e){
        e.preventDefault();
        var data_id    = jQuery(this).attr('data-id');
        var data_token = jQuery(this).attr('data-token');

        if(data_id.length > 0 && data_token.length > 0){
            jQuery('.formpassword').find('.loader').fadeIn();

            var formData = new FormData();
            formData.append('action', "remove_user_old");
            formData.append('id', data_id);
            formData.append('token', data_token);

            jQuery.ajax({
                type: 'POST',
                dataType: 'json', 
                url: ajaxUrl,
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) { 
                   // console.log(data);
                    if(!jQuery.isEmptyObject(data.message)){
                        $.each(data, function(key, value){ 
                            if(key == "message" ){
                                //console.log(value);
                                $.each(value, function(key, value){
                                    // console.log(key, value);
                                    jQuery( '.outputremove' ).append(value).fadeIn();
                                });
                            }
                        });
                    } 
                    
                    jQuery('.formpassword').find('.loader').fadeOut();
                    jQuery('.boxhcontent').remove();
 
                    // redirect to login page

                },
                error: function(data) {
                    jQuery(this_form).parent().parent().find('.loader').fadeOut();
                  
                    jQuery('.outputremove').html(data.message);
                }
            });   
        }

    });

});