jQuery(document).ready(function($){

	if((window.location.hash) || ($('#how-it-works').length)) {
	    var hash = window.location.hash.substring(1);
	    if(( hash = "how-it-works") || ($('#how-it-works').length)){
	      	jQuery("#menu-main-menu .menu-item-object-custom").removeClass("current-menu-item");
	      	jQuery("#menu-main-menu-users .menu-item-object-custom").removeClass("current-menu-item");
	      	jQuery("#menu-main-menu-no-login-users .menu-item-object-custom").removeClass("current-menu-item");

	    }
	} else {
	    
	}



	//tabs
    $('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	});


	jQuery.fn.extend({
	    toggleText: function (a, b){
	        var that = this;
	            if (that.text() != a && that.text() != b){
	                that.text(a);
	            }
	            else
	            if (that.text() == a){
	                that.text(b);
	            }
	            else
	            if (that.text() == b){
	                that.text(a);
	            }
	        return this;
	    }
	});
	//mobile menu
	$('.mobile-menu, .close-menu').on('click',function(e){
		e.preventDefault();
		$('body').toggleClass('menu-open');
	});

	//FANCYBOX
	$(".fancybox a").fancybox();
	$(".fancybox_item").fancybox();

	$('a.close-popup').click(function(e){
	    e.preventDefault();
	   	//window.history.pushState("", document.title, window.location.pathname);  
	    $.fancybox.close();

	  });

	//don't handle clicks for empty anchors
	$('a[href=""],a[href="#"]').on('click',function(e){
		e.preventDefault();
	});

	///HOME SCROLL
	$(".scroll-down").click(function() {
		$('html, body').animate({
			scrollTop: $("#scroll-down").offset().top
		}, 800);
	});

	$('.SlectBox').SumoSelect({search: true, searchText: 'Enter here or select.'});
	$(".SumoSelect li").bind('click.check', function(event) {
		var main_parent 	= jQuery(this).parent().parent().parent().parent().parent().parent();
		var item_position 	= jQuery(this).index();
		// console.log(jQuery(this));
		var this_text 		= jQuery(this).context.innerText;
		var action 			= $(this).hasClass('selected'); // true = select; false = remove
		if(action == true){ // add item
			main_parent.find('.wrappselectitems').append('<div class="selected-option" data-item="'+item_position+'"><span>'+this_text+'</span><a href="#" title="" class="removesummo" data-item="'+item_position+'"><i class="fa fa-times"></i></a></div>');
		}else{ // remove item 
			main_parent.find('.wrappselectitems .selected-option[data-item="'+item_position+'"]').remove();
			main_parent.find('select')[0].sumo.unSelectItem(item_position);
			//main_parent.find('select')[0].sumo.reload();
		}
	   
    });

    $(document).on('click', '.removesummo', function(e){ 
    	e.preventDefault();
    	var parent_wrapper 		= jQuery(this).parent().parent();
    	var parent_wrapper_main = jQuery(this).parent().parent().parent();
    	var parent 				= jQuery(this).parent(); 
    	var item_index 			= parseInt(jQuery(this).attr('data-item'));

    	//console.log(parent_wrapper_main.find('select')[0]); 
    	//console.log(item_index);
    	parent_wrapper_main.find('select')[0].sumo.unSelectItem(item_index);
    	parent.remove(); // remove parent 
    	
    	//parent_wrapper_main.find('select')[0].sumo.reload();
    });



	$(".project-item .text-content .expand-details").click(function(e) {
		e.preventDefault();
		$(this).parent().parent().find(".hidden-content").slideToggle();
		$(this).toggleText("More Details", "Less Details");
	});
	
	//STAR RAITING//

	$('.stars_raiting .star').click(function(){
		var total=$(this).parent().children().length;
		var clickedIndex=$(this).index();
		$('.stars_raiting .star').removeClass('filled');
		for(var i=clickedIndex;i<total;i++){
			$('.stars_raiting .star').eq(i).addClass('filled');
		}
	});

	//tabs
	$('.tabs-wrapper .navigator .tab-selector').on('click', function() {
		$(this).parent().find('.tab-selector.active').removeClass('active');
		$(this).addClass('active');

		$(this).parent().parent().find('.tab.active').removeClass('active');
		$($(this).parent().parent().find('.tab').get($(this).index())).addClass('active');
    
	});

	//review scroll
	$(".review-btn").click(function(e) {
		e.preventDefault();
		$('html, body').animate({
			scrollTop: $("#review").offset().top
		}, 2000);
	});

	//isotope
	var $container = $('.isotope-wrapper').isotope({
		itemSelector: '.cell.x3',
		layoutMode: 'masonry',
		percentPosition: true,
		masonry: {
		  columnWidth: '.grid-sizer',
		}
	});

	$("nav#mobile-navigation-menu").mmenu({
      	offCanvas: {
         	position: "right"
      	}
   });

	// if ( $( "#uploadBtn" ).length ) {

	// 	document.getElementById("uploadBtn").onchange = function () {
	// 	    //document.getElementById("uploadFile").value = this.value;
	// 	    jQuery('#flname').html(this.value);
	// 	};
	// }

	
	jQuery(document).on('change', '#uploadBtn', function(e){ 
		var files = jQuery( '#uploadBtn').prop("files")
	    var names = $.map(files, function (val) { return val.name; });
	    var all_files = "";
	    $.each(names, function (i, name) {
	    	all_files += name + "<br/>";
	        //console.log(name);
	    });
	    if(all_files.length > 0){ 
	    	jQuery('#flname').html(all_files);
		}else{
			jQuery('#flname').html("Choose File");
		}

	});
	  

	// implementation of disabled form fields
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	
	var checkin = $('#rfq-expire-date').fdatepicker({
		onRender: function (date) {
			return date.valueOf() < now.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function (ev) {
		//if (ev.date.valueOf() > checkout.date.valueOf()) {
			var newDate = new Date(ev.date)
			//newDate.setDate(newDate.getDate() + 1);
			newDate.setDate(newDate.getDate() );
			//checkout.update(newDate);
			//console.log(newDate);
			
			var curr_date 	= newDate.getDate();
			var curr_month 	= newDate.getMonth()+1;
			var curr_year 	= newDate.getFullYear();

			var final_new_date = curr_date + "." + curr_month + "." + curr_year; 

			jQuery('#expire-date-input').text(final_new_date); // ......... text + input val
			jQuery('#date_expire').val(final_new_date); 

			// update checkout
			newDate.setDate(newDate.getDate()); // +1
			checkout.update(newDate);
			var curr_date 	= newDate.getDate();
			var curr_month 	= newDate.getMonth()+1;
			var curr_year 	= newDate.getFullYear();
			var final_new_date = curr_date + "." + curr_month + "." + curr_year; 	
			jQuery('#delivery-deadline-input').text(final_new_date); // ................ text + input val
			jQuery('#date_deadline').val(final_new_date); 


		//}
		checkin.hide();
		$('#rfq-delivery-deadline')[0].focus();
	}).data('datepicker');
	
	var checkout = $('#rfq-delivery-deadline').fdatepicker({
		onRender: function (date) {
			return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
		}

	}).on('changeDate', function (ev) {
		var newDate = new Date(ev.date);
		//console.log(newDate);	

		var curr_date 	= newDate.getDate();
		var curr_month 	= newDate.getMonth()+1;
		var curr_year 	= newDate.getFullYear();

		var final_new_date = curr_date + "." + curr_month + "." + curr_year; 	

		jQuery('#delivery-deadline-input').text(final_new_date); // ................ text + input val
		jQuery('#date_deadline').val(final_new_date); 

		checkout.hide();
	}).data('datepicker');

/*
	var startDate = new Date('Y, M, d');

	var endDate = new Date('Y, M, d');
	$('#rfq-expire-date').fdatepicker()
		.on('changeDate', function (ev) {
		if (ev.date.valueOf() > endDate.valueOf()) {
			//$('#alert').show().find('strong').text('The start date can not be greater then the end date');
		} else {
			//$('#alert').hide();
			startDate = new Date(ev.date);
			$('#expire-date-input').text($('#rfq-expire-date').data('date'));
		}
		$('#rfq-expire-date').fdatepicker('hide');
	});
	$('#rfq-delivery-deadline').fdatepicker()
		.on('changeDate', function (ev) {
		if (ev.date.valueOf() < startDate.valueOf()) {
			//$('#alert').show().find('strong').text('The end date can not be less then the start date');
		} else {
			//$('#alert').hide();
			endDate = new Date(ev.date);
			$('#delivery-deadline-input').text($('#rfq-delivery-deadline').data('date'));
		}
		$('#rfq-delivery-deadline').fdatepicker('hide');
	}); */



		

});

$(window).load(function() {
	$("nav#mobile-navigation-menu").removeClass('hide');

	$('.portofolio-carousel').owlCarousel({
	    loop:true,
	    margin:0,
	    nav:true,
	    dots: false,
	    autoplay: true,
	    autoplayHoverPause: true,
	    navText: [
		   "<i class='fa fa-chevron-left'></i>",
		   "<i class='fa fa-chevron-right'></i>"
		],
	    responsive:{
	        0:{
	            items:1
	        },
	        600:{
	            items:1
	        },
	        1000:{
	            items:1
	        }
	    }
	});
});

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 



function validatePhone(phone)	{
	var re = /^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$/i;
	return re.test(phone);
}

function isUrl(s) {
    var regexp = /^(http:\/\/www\.|https:\/\/www\.)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/; // /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
    //if(/^(http:\/\/www\.|https:\/\/www\.)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/.test(myVariable)){

    return regexp.test(s);
}


// Google Maps = Company Page

(function($) {

/*
*  new_map
*
*  This function will render a Google Map onto the selected jQuery element
*
*  @type	function
*  @date	8/11/2013
*  @since	4.3.0
*
*  @param	$el (jQuery element)
*  @return	n/a
*/

function new_map( $el ) {
	var $markers = $el.find('.marker');
	var args = {
		zoom		: 16,
		center		: new google.maps.LatLng(0, 0),
		mapTypeId	: google.maps.MapTypeId.ROADMAP
	};        	
	var map = new google.maps.Map( $el[0], args);
	map.markers = [];
	$markers.each(function(){
    	add_marker( $(this), map );
	});
	center_map( map );
	return map;
	
}

/*
*  add_marker
*
*  This function will add a marker to the selected Google Map
*
*  @type	function
*  @date	8/11/2013
*  @since	4.3.0
*
*  @param	$marker (jQuery element)
*  @param	map (Google Map object)
*  @return	n/a
*/

function add_marker( $marker, map ) {

	var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );
	var marker = new google.maps.Marker({
		position	: latlng,
		map			: map
	});

	map.markers.push( marker );
	if( $marker.html() ){
		// create info window
		var infowindow = new google.maps.InfoWindow({
			content		: $marker.html()
		});

		// show info window when marker is clicked
		google.maps.event.addListener(marker, 'click', function() {
			infowindow.open( map, marker );
		});
	}

}

/*
*  center_map
*
*  This function will center the map, showing all markers attached to this map
*
*  @type	function
*  @date	8/11/2013
*  @since	4.3.0
*
*  @param	map (Google Map object)
*  @return	n/a
*/

function center_map( map ) {
	var bounds = new google.maps.LatLngBounds();
	// loop through all markers and create bounds
	$.each( map.markers, function( i, marker ){
		var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );
		bounds.extend( latlng );

	});

	// only 1 marker?
	if( map.markers.length == 1 ){
		// set center of map
	    map.setCenter( bounds.getCenter() );
	    map.setZoom( 16 );
	}
	else{
		// fit to bounds
		map.fitBounds( bounds );
	}

}

/*
*  document ready
*
*  This function will render each map when the document is ready (page has loaded)
*
*  @type	function
*  @date	8/11/2013
*  @since	5.0.0
*
*  @param	n/a
*  @return	n/a
*/
// global var
var map = null;

$(document).ready(function(){
	$('.acf-map').each(function(){
		// create map
		map = new_map( $(this) );
	});

});

})(jQuery);