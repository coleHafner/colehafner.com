$( document ).ready( function() {
	
/*----------------------------------------------------------------------------------------------------------
hotkeys
----------------------------------------------------------------------------------------------------------*/
	$(document).bind('keydown', 'up', function(){
		
		var slide_obj = new Object();
		slide_obj.direction = "down";
		slideVertical( slide_obj ); 
	});
	
	$(document).bind('keydown', 'down', function(){
		
		var slide_obj = new Object();
		slide_obj.direction = "up";
		slideVertical( slide_obj ); 
	});
	
	$(document).bind('keydown', 'left', function(){
		
		var slide_obj = new Object();
		slide_obj.direction = "back";
		slideHorizontal( slide_obj ); 
	});
	
	$(document).bind('keydown', 'right', function(){
		
		var slide_obj = new Object();
		slide_obj.direction = "forward";
		slideHorizontal( slide_obj ); 
	});
	
	$(document).bind( 'keydown', 'shift', function(){
		
		var slide_obj = new Object();
		slide_obj.slide_num = 1;
		slideHorizontal( slide_obj ); 
	});
	
	//start slideshow
	featured_ticker = setInterval( function(){
		
		//get current state
		var feature_state = $( "#feature_state" ).attr( "value" );
		
		if( feature_state == "playing" )
		{
			//slide featured
			var current_feature = parseInt( $( "#feature_current" ).attr( "value" ) );
			var el = $( "#feature_slide_" + current_feature );
			featureIncrement( current_feature, el );
			
			//update feature
			var new_feature = ( current_feature == 3 ) ? 1 : ( current_feature + 1 );
			$( "#feature_current" ).attr( "value", new_feature );
		}
		
	}, 3500 );
	
/*----------------------------------------------------------------------------------------------------------
element functions
----------------------------------------------------------------------------------------------------------*/
	
	//detect IE
	if( $.browser.msie )
	{
		alert( "This site is not compatible with Internet Explorer. Please use either Firefox, Chrome, or Safari for an optimal browsing experience." );
	}
	
	//element functions
	$( ".nav_table td a.overlay" )
		.click( function(){ 
			$( this ).blur(); 
		})
		
		.mouseenter( function( event ){	
			event.preventDefault();
			$( this ).css( 'text-decoration', 'none' );
		});
	
	$( ".nav_table td" )
		.mouseenter( function(){
			var td_id = "nav_selector_hover_" + $( this ).attr( 'process' );
			$( ".nav_table td div.nav_selector_hover" ).removeClass( "nav_selector_active" );
			$( "#" + td_id ).addClass( "nav_selector_active" );
		})
		
		.mouseleave( function(){
			var td_id = "nav_selector_hover_" + $( this ).attr( 'process' );
			$( "#" + td_id ).removeClass( "nav_selector_active" );
		})
	
		.click( function(){
			slideVertical( $( this ) );
		});
	
	$( ".featured_nav a.overlay" )
		.click( function( event ){
			
			event.preventDefault();
			var el = $( this )
			el.blur();
			
			var feature_num = el.attr( "feature_num" );
			featureIncrement( feature_num, el );
			featurePause();
		});
	
	$( ".port_grid td div.port_grid_item_container" )
		.mouseenter( function(){ $( this ).find( ".overlay" ).show(); })
		.mouseleave( function(){ $( this ).find( ".overlay" ).hide(); });
	
	$( ".show_slide_p" )
		.click( function( event ){
		
			$( this ).blur();
			event.preventDefault();
			slideHorizontal( $( this ) );
		});
});

/*----------------------------------------------------------------------------------------------------------
window events
----------------------------------------------------------------------------------------------------------*/
$( window ).resize( function(){
	pageResize();
});

$( window ).load( function(){
	pageResize();
});

function slideHorizontal( el )
{
	//vars
	var max_slides = parseInt( $( "#max_slides_h" ).attr( "value" ) );
	var current_slide = parseInt( $( "#current_slide_h" ).attr( "value" ) );
	var slide_width = parseInt( $( ".p_slide" ).css( 'width' ).toString().replace( "px", "" ) ) + 12;
	
	//requested slide
	if( $( el ).attr( "slide_num" ) )
	{
		var requested_slide = $( el ).attr( "slide_num" );
	}
	else
	{
		var direction = $( el ).attr( "direction" );
		var requested_slide = ( direction == "forward" ) ? current_slide + 1 : current_slide - 1;
	}
	
	//do slide
	if( requested_slide != current_slide &&
		requested_slide <= max_slides &&
		requested_slide > 0 )
	{
		//calculate slide distance
		var requested_position = requested_slide * slide_width;
		var scroll_to = ( requested_position * -1 ) + slide_width;
		
		//update page num
		$( "#current_slide_h" ).attr( "value", requested_slide );
		
		//slide
		$( "#p_holder" ).animate( { left:scroll_to.toString() }, 1000 );
	}
	
}//slideHorizontal()

function slideVertical( el )
{
	var max_slides = $( "#max_slides_v" ).attr( "value" );
	var current_slide = parseInt( $( "#current_slide_v" ).attr( "value" ) );
	var slide_height = parseInt( $( ".slide" ).css( 'height' ).toString().replace( "px", "" ) );
	
	//requested slide
	if( $( el ).attr( "slide_num" ) )
	{
		var requested_slide = $( el ).attr( "slide_num" );
	}
	else
	{
		var direction = $( el ).attr( "direction" );
		var requested_slide = ( direction == "up" ) ? current_slide + 1 : current_slide - 1;
	}
	
	//alert( "requested_slide: " + requested_slide + " current slide: " + current_slide + " max_slides " + max_slides );
	
	if( requested_slide != current_slide &&
		requested_slide <= max_slides &&
		requested_slide > 0 )
	{
		//grab page name
		var requested_slide_string = requested_slide.toString();
		var page = $( "#slide_v_key_" + requested_slide_string ).attr( 'name' );
		var td_id = "nav_selector_" + page;
		
		//resume slideshow
		if( page != "about" ) { featureResume(); }
		
		//change page title
		var delim = " - ";
		var title_split = document.title.split( delim );
		var page_mod = page.charAt( 0 ).toUpperCase() + page.slice( 1 );
		document.title = page_mod + delim + title_split[1];
		
		//change anchor
		window.location = "#" + page;
		
		//control navigation effect
		$( ".nav_table td div.nav_selector_hover, .nav_table td div.nav_selector" ).removeClass( "nav_selector_active" );
		$( "#" + td_id ).addClass( "nav_selector_active" );
		
		//calculate slide distance
		var requested_position = requested_slide * slide_height;
		var scroll_to = ( requested_position * -1 ) + slide_height;
		
		//update current slide info
		$( "#current_slide_v" ).attr( "value", requested_slide );
		$( "#current_slide_v_name" ).attr( "value", page );
		
		//toggle portfolio controls
		if( page != "portfolio" ) 
		{ 
			$( ".port_controls" ).hide(); 
		}
		else
		{
			$( ".port_controls" ).fadeIn( 2000 );
		}
		
		//slide content
		$( ".slide_controls" ).children().removeClass( "selected" );
		$( "#slide_holder" ).animate( { top:scroll_to.toString() }, 1000, function(){} );
	}
	
}//slideVertical()

function showMessage( message, form_result )
{	
	//class names
	var target = ".result_message";
	var success = "result_success";
	var failure = "result_failure";
	var result_class = ( form_result == 0 ) ? failure : success;
	var opposite_class = ( result_class == success ) ? failure : success;
	
	//show message
	if( $( target ).hasClass( opposite_class ) ) { $( target ).removeClass( opposite_class ); }
	$( target ).addClass( result_class ).html( message );
	
}//showMessage()

function featureIncrement( feature_num, el )
{
	//switch off other active items
	$( ".featured_nav div.featured_selector" ).removeClass( "featured_selector_active" ).addClass( "featured_selector_inactive" );
	
	//switch on current item
	$( el ).parent().find( "div.featured_selector" ).removeClass( "featured_selector_inactive" ).addClass( "featured_selector_active" );
	
	//toggle photos
	var reveal = "#photo_" + feature_num;
	$( ".featured_photo" ).hide();
	$( reveal ).fadeIn( 2000 );
	
	//toggle blurbs
	$( ".featured_blurb" ).hide();
	$( "#blurb_" + feature_num ).fadeIn( 2000 );
	
	//update current featured slide
	$( "#feature_current" ).attr( "value", feature_num );
	
}//runFeatured()

function featurePause()
{
	$( "#feature_state" ).attr( "value", "pasued" );
}//pauseFeatured()

function featureResume()
{
	$( "#feature_state" ).attr( "value", "playing" );
}//pauseFeatured()

function pageResize()
{
	//grab dimensions
	var inner_height = window.innerHeight;
	var inner_width = window.innerWidth;
	
	//resize page
	$( "#page" ).css( "height", ( inner_height - 15 ) );
	
}//pageResize()