$( document ).ready( function(){
	
	//resize canvas
	resizeCanvas();
	
	//slide next
	$( ".next" )
	
		.click( function( event ){
			
			//do other shit
			var el = $( this );
			var slide_num = parseInt( el.attr( "slide_num" ) );
			var slides = [ "about", "portfolio", "contact" ];
			
			//hide all slides
			$.each( slides, function( i, slide_name ){
				$( "#" + slide_name ).hide();
			});
			
			if( slide_num == 2 )
			{
				//do slide
				slideHorizontal( el, function( el ){});
				
				//show active slide
				var to_show = el.attr( "show" );
				$( "#" + to_show ).show( 100 );
			}
			else
			{
				//do slide
				slideHorizontal( el, function(){} );
			}
		})
		
		.mouseenter( function(){
			
			//show pointer
			$( this ).addClass( "mobile_row_hover" );
			
		})
		
		.mouseleave( function(){ 
		
			$( this ).removeClass( "mobile_row_hover" ); 

		});
		
});

function slideHorizontal( el, callback )
{	
	//vars
	var requested_slide = parseInt( $( el ).attr( "slide_num" ) );
	var max_slides = parseInt( $( "#max_slides" ).attr( "value" ) );
	var current_slide = parseInt( $( "#current_slide" ).attr( "value" ) );
	var slide_width = parseInt( $( ".slide" ).css( 'width' ).toString().replace( "px", "" ) );
	//alert( "cur_slide: " + current_slide + " max_slides: " + max_slides + " slide_width: " + slide_width + " requested: " + requested_slide );
	
	//do slide
	if( requested_slide != current_slide &&
		requested_slide <= max_slides &&
		requested_slide > 0 )
	{
		//calculate slide distance
		var requested_position = requested_slide * slide_width;
		var scroll_to = ( requested_position * -1 ) + slide_width;
		//alert( "requested: " + requested_position + " scroll_to: " + scroll_to );
		
		//update slide num
		$( "#current_slide" ).attr( "value", requested_slide );
		
		//slide
		var to_show = el.attr( "to_show" );
		$( "#slide_canvas" ).animate( { left:scroll_to.toString() }, 300, function(){ callback(); } );
	}
	
}//slideHorizontal()

function resizeCanvas()
{
	//calculate width
	var max_slides = parseInt( $( "#max_slides" ).attr( "value" ) );
	var slide_width = window.innerWidth;
	
	//resize slides
	var slide_width_string = slide_width.toString() + "px";
	$( ".slide" ).css( "width", slide_width_string );
	$( ".page" ).css( "width", slide_width_string );
	  
	//resize canvas
	var total_width = max_slides * slide_width;
	var width_string = total_width.toString() + "px";
	$( "#slide_canvas" ).css( "width", width_string );
	//alert( "resized to ... " + width_string + " new width: " + $( "#slide_canvas" ).css( "width" ).toString() );
	
}//resizeCanvas()

function hasAttr( el, attr_name )
{
	return ( typeof( el.attr( attr_name ) ) !== "undefined" ) ? true : false;
}//hasAttr
