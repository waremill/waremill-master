jQuery(document).ready(function($){
 
 	/********************************************* Switch User Role   *********************************************/

 	// button from menu 
    jQuery(document).on('click','.switchrole',function(e){
    	e.preventDefault();	
    	var role 	= jQuery(this).attr('data-role');
    	var page_id = jQuery(this).attr('data-page');

    	if(role.length > 0 && (role=="customer" || role=="contractor")){
    		jQuery('.switchcontent').fadeIn();
    		var dataform = new FormData();
			dataform.append('role', role); 
			dataform.append('action', "changerole"); 
			dataform.append('redirect', true);
			dataform.append('page', page_id);

				jQuery.ajax({
	            type: 'POST',
	            dataType: 'json',  
	            url: ajaxUrl,
	            data: dataform,
	            processData: false,
	            contentType: false,
	            success: function(data) {
	               
	               	if(data.redirect == true){
	               		if(data.page.length >0 ){
	               			setTimeout(function(){
								jQuery('.switchcontent').fadeOut();
								//location.reload();
								window.location.href = data.page;
							}, 2000);	   
	               		}else{
	               			setTimeout(function(){
								jQuery('.switchcontent').fadeOut();
								location.reload();
							}, 2000);
	               		}
	               	}else{
						setTimeout(function(){
							jQuery('.switchcontent').fadeOut();
							location.reload();
						}, 2000);		
	               		
	               	}
					
	            },
	            error: function(data) {
	               jQuery('.switchcontent').fadeOut();
	               console.log(data);
	            }
	        });	
		}

    });

    // button from page 
    jQuery(document).on('click','.switch_role',function(e){
    	e.preventDefault();

    	var role = jQuery(this).attr('data-role');
    	var form_resp = '.answerswitch';
    	var this_form = 'body .dashboard-page';

    	jQuery(form_resp).html('');
    	// console.log(role);	
		if(role.length > 0 && (role=="customer" || role=="contractor")){
			jQuery(this_form).find('.loader').fadeIn();


			var dataform = new FormData();
			dataform.append('role', role); 
			dataform.append('once', true); 
			dataform.append('action', "changerole"); 

			jQuery.ajax({
	            type: 'POST',
	            dataType: 'json',  
	            url: ajaxUrl,
	            data: dataform,
	            processData: false,
	            contentType: false,
	            success: function(data) {
	                jQuery(this_form).find('.loader').fadeOut();
	                jQuery(form_resp).html(data.message);

	                //console.log(data.link);
	                if(data.link.length >0 ){
	                	window.location.href = data.link;
	                }
	                /*jQuery(this_form).find('.boxuser .user-role').removeClass('active');
	                jQuery(this_form).find('.boxuser[data-role="'+role+'"] .user-role').addClass('active');
	                jQuery('.skipbutton').attr("href", data.link);
	                jQuery('#page').removeClass(function (index, className) {
					    return (className.match (/\S+-theme(^|\s)/g) || []).join(' ');
					});
					//jQuery('#page').removeClass(makeRemoveClassHandler(/^-theme/));
					var classes = jQuery('#page').attr('class');
					classes = role+'-theme'+' ' +classes;
					jQuery('#page').attr('class', classes);
					jQuery('#userrl').html(role);*/
					//setTimeout(function(){
					//	location.reload();
					//}, 2000);	   
					

	            },
	            error: function(data) {
	                jQuery('body .user-roles').find('.loader').fadeOut();
	                //jQuery('body').find('.loader').fadeOut();
	                jQuery(form_resp).html(data.message);
	            }
	        });	
		}
	});
});