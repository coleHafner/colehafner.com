/**
 * A class to handle user records
 * @package	halfnerdCMS
 * @author	Hafner
 * @since	20101215
 */

$( document ).ready( function(){
	
	$( ".user_toggle_photo_options" )
		.live( "change", function(){
			var active_option = $( this ).attr( "active_option" );
			
			if( active_option == "gravatar" )
			{
				$( "#user_photo_file" ).val( "" );
			}
			else if( active_option == "file" )
			{
				$( "#user_photo_gravatar" ).attr( "checked", false );
			}
		});
	
	$( ".user_auto_search" )
		.live( "keypress", function( event ){

    		//search on enter
    		if( event.keyCode == 13 )
    		{
	    		user = new User( 0 );
	    		user.doSearch();
	    	}
    	});
    
	$( "#user" )
    	.live( "click", function( event ){
    	
    	//cancel event
		event.preventDefault();
		
		//get vars
		var user_id = ( hasAttr( $( this ), "user_id" ) ) ? $( this ).attr( "user_id" ) : 0;
		var process = $( this ).attr( "process" );
		
		//create authentication object
		var user = new User( user_id );
		
		//do action
		switch( process.toLowerCase() )
		{
			case "add":
				user.validateAddModForm( "add", user.user_id );
				break;
				
			case "modify":
				user.validateAddModForm( "modify", user.user_id );
				break;
				
			case "delete":
				user.deleteRecord( user.user_id );
				break;
				
			case "refresh_user_type_selector":
				user.refreshUserTypeSelector( user.user_id );
				break;
			
			case "update_photo":
				user.updatePhoto();
				break;
				
			case "search":
				user.doSearch();
				break;
				
			default:
				alert( "Error: jquery.halfnerd.user.js says 'Process '" + process + "' is invalid.'" );
				break;
		}
	});
    
});//end document.ready


function User( user_id )
{
	this.user_id = user_id;
	
/**********************************************************************************************************************************
action functions
**********************************************************************************************************************************/

	this.add = function()
	{
		$.ajax({
			type: 'post',
			url: "/ajax/halfnerd_helper.php?task=user&process=add&user_id=0",
			data: $( "#user_add_mod_form_0" ).serialize( true ),
			success: function( reply ){
				
				//show delete confirmation
				showMessage( "User Added", 1, function(){ reloadPage( 1500 ) } );
			}
		});
	
	}//add()
	
	this.modify = function( user_id )
	{
		$.ajax({
			type: 'post',
			url: "/ajax/halfnerd_helper.php?task=user&process=modify&user_id=" + this.user_id,
			data: $( "#user_add_mod_form_" + user_id ).serialize( true ),
			success: function( reply ){
				
				//show delete confirmation
				showMessage( "Changes Saved", 1 );
			}
		});
	
	}//modify()
	
	this.deleteRecord = function( user_id )
	{
		$.ajax({
			type: 'post',
			url: '/ajax/halfnerd_helper.php?task=user&process=delete&user_id=' + this.user_id,
			data: $( "#user_add_mod_form_" + user_id ).serialize( true ),
			success: function( reply ){
				
				//show message and refresh list
				showMessage( "User Deleted", 1, function(){ setTimeout( 'window.location.reload();', 1000 ) } );
			}
		});
	
	}//modify()
	
	this.updatePhoto = function()
	{
		if( $( "#user_photo_file" ).val().length == 0 )
		{
			//save gravatar preferences
			$.ajax({
				type: 'post',
				url: '/ajax/halfnerd_helper.php?task=user&process=update_photo&user_id=' + this.user_id,
				data: $( "#user_image_upload_form" ).serialize( true ),
				success: function( reply ){
					showMessage( "Photo Updated", 1, function(){ reloadPage(); } );
				}
			});
		}
		else
		{
			var validation_result = validateImageFile( "#user_photo_file" );
			
			if( validation_result.length > 0 )
			{
				//show error
				showMessage( validation_result, 0 );
			}
			else
			{
				//upload new user photo
				var file = new File();
				var file_name = $( "#unique_file_name" ).attr( "value" );
				
				//show loader
				$( "#file_loader" ).fadeIn( "slow" );
				
				file.uploadFile( "user_image_upload_form", file_name );
			}
		}
	}//updatePhoto()
	
	this.doSearch = function()
	{
		var location = "http://" + $( "#user_base_url" ).val() + "/_users/search/" + $( "#user_search_term" ).val();
		window.location = location; 
		
	}//doSearch()
	
/**********************************************************************************************************************************
validation functions
**********************************************************************************************************************************/

	this.validateAddModForm = function( process, user_id )
	{
		//task=user&process=validate&user_id=' + this.user_id,
		
		$.ajax({
			type: 'post',
			url: '/ajax/halfnerd_helper.php?task=user&process=validate&user_id=' + this.user_id,
			data: $( "#user_add_mod_form_" + user_id ).serialize( true ),
			success: function( reply ){
				
				//get vars
				var reply_split = reply.split( "^" );
				var result =  reply_split[0];
				var message = reply_split[1];  
				
				//clear form
				if( result == 1 )
				{
					var inner = new User( user_id ); 
					
					switch( process.toLowerCase() )
					{
						case "add":
							inner.add();
							break;
							
						case "modify":
							inner.modify( user_id );
							break;
							
						case "delete":
							inner.deleteRecord();
							break;

					}
				}
				else
				{
					showMessage( message, result );
				}
			}
		});
		
	}//validateAddModForm()
	
/**********************************************************************************************************************************
ui functions
**********************************************************************************************************************************/
	
	this.refreshUserTypeSelector = function( user_id )
	{
		$.ajax({
			type: 'post',
			url: '/ajax/halfnerd_helper.php?task=user&process=refresh_user_type_selector&user_id=' + user_id,
			data:{},
			success: function( reply ){

				$( ".user_type_selector_" + user_id ).html( reply );
			}
		});
	}//refreshUserTypeSelector()
	
}//class User