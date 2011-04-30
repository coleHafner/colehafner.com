$( document ).ready( function() {
	
	
	//hotkeys
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
			$( this ).blur();
			
			var feature_num = $( this ).attr( "feature_num" );
			
			//switch off other active items
			$( ".featured_nav div.featured_selector" ).removeClass( "featured_selector_active" ).addClass( "featured_selector_inactive" );
			
			//switch on current item
			$( this ).parent().find( "div.featured_selector" ).removeClass( "featured_selector_inactive" ).addClass( "featured_selector_active" );
			
			//toggle photos
			$( ".featured_photo" ).hide();
			$( "#photo_" + feature_num ).fadeIn( 2000 );
			
			//toggle blurbs
			$( ".featured_blurb" ).hide();
			$( "#blurb_" + feature_num ).fadeIn( 2000 );
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
		
		//change page title
		var delim = " - ";
		var title_split = document.title.split( delim );
		var page_mod = page.charAt( 0 ).toUpperCase() + page.slice( 1 );
		document.title = page_mod + delim + title_split[1];
		
		//control navigation effect
		$( ".nav_table td div.nav_selector_hover, .nav_table td div.nav_selector" ).removeClass( "nav_selector_active" );
		$( "#" + td_id ).addClass( "nav_selector_active" );
		
		//calculate slide distance
		var requested_position = requested_slide * slide_height;
		var scroll_to = ( requested_position * -1 ) + slide_height;
		
		//update current slide info
		$( "#current_slide_v" ).attr( "value", requested_slide );
		$( "#current_slide_v_name" ).attr( "value", page );
		
		//hide portfolio controls
		if( page != "portfolio" ) $( ".port_controls" ).hide();
		
		//slide content
		$( ".slide_controls" ).children().removeClass( "selected" );
		$( "#slide_holder" ).animate( { top:scroll_to.toString() }, 1000, function(){ 
			
			//show portfolio controls
			if( page == "portfolio" ) $( ".port_controls" ).fadeIn( 2000 );
		});
	}
	
}//slideVertical()