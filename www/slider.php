<? 
echo '
<!DOCTYPE html>
<html>
<head>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<meta charset=utf-8 />

<title>Slider Demo</title>

<!--[if IE]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<script type="text/javascript">

$(document).ready( function( $ ) {

	$( "#scroll_up" ).click( function() {
	
		var callback = function(){ $( "#down_container" ).fadeIn( "fast" ); $( "#up_container" ).fadeOut( "fast", function(){ $( "#up_title" ).show(); } ) };
		$( ".content_bottom" ).removeClass( "short" ).addClass( "tall" );
		scroll( -550, callback );
		
	});
	
	$( "#scroll_down" ).click( function() {
	
		var callback = function(){ 
		
			$( "#up_title" ).fadeOut( "fast", function(){ $( "#up_container" ).show(); } );
			$( "#down_container" ).fadeOut( "fast", function(){ $( ".content_bottom" ).removeClass( "tall" ).addClass( "short" ); } );  
		}
		
		scroll( 0, callback );
	});
	
	function scroll( scroll_to, callback )
	{
		$( ".slide_container" ).animate( { top:scroll_to.toString() }, 1000, function(){ callback(); } );
		
	}//slide
});

</script>

<style>

body {
    margin:0;
    padding:0;
    background-color:#FFFFFF;
    font-family:arial;
}

.bg_orange { background-color:#FF614A; }
.bg_white { background-color:#FFFFFF; }
.bg_blue { background-color:#1874CD; }

.container, .content_top, .content_bottom, .content_inner, .slide_container {
	position:relative;
    width:100%;
    overflow:hidden; 
}

.container {
    height:700px;
    overflow:hidden;
}

.content_top {
    height:600px;
    border-bottom:1px solid #333333;
}

.content_inner {
	padding:30px;
}

.content_bottom {
    -moz-box-shadow:inset 0px 3px 10px #333333;
    -webkit-box-shadow:inset 0px 3px 10px #333333;
    box-shadow:inset 0px 3px 10px #333333;
}

.short { height:100px; }
.tall { height:600px; }

.button_circle {
	border:1px solid #999999;
	-moz-border-radius:10em;
	-webkit-border-radius:10em;
	border-radius:10em;
	width:35px;
	height:30px;
	padding-top:5px;
	text-align:center;
}

.header { 
	font-size:20px; 
	color:#000000; 
	font-weight:bold;
}

.div_centered {
	position:relative;
	height:50px;
	width:180px;
	margin:auto;
	text-align:center;
}

.float_left {
	position:relative;
	float:left;
}

.going_up {
	padding-right:15px;
	padding-top:5px;
}

.overlay {
	position:relative;
	display:block;
	position:absolute;
	top:0px;
	left:0px;
	width:100%;
	height:100%;
	text-decoration:none;
}

.overlay:hover {
	text-decoration:none;
}

.scroll_down {
	position:absolute;
	bottom:5px;
	right:5px;
	width:180px;
	display:none;
}

.slider_form {
	position:relative;
	width:700px;
	height:525px;
	-moz-border-radius:.5em;
	-webkit-border-radius:.5em;
	border-radius:.5em;
	border:1px solid #333;
	margin:25px auto auto auto;
}

</style>
  
</head>
  
<body class="bg_white">

<div class="container">
	<div class="slide_container">
	
		<div class="content_top bg_blue">
			<div class="scroll_down" id="down_container">
				<div class="float_left header going_up" style="color:#FFFFFF;">
					Going Down
				</div>
				<div class="float_left">
					<div class="button_circle bg_orange">&darr;</div>
				</div>
				<div style="clear:both;"></div>
				<a class="overlay" href="#register" id="scroll_down">&nbsp;</a>					
			</div>
		</div> 
	   
		<div class="content_bottom short">
			<div class="content_inner">
			
				<div class="div_centered">
					
					<div id="up_container">
						<div class="float_left header going_up" >
							Going Up
						</div>
						<div class="float_left">
							<div class="button_circle bg_orange">&uarr;</div>
						</div>
						
						<div style="clear:both;"></div>
						<a class="overlay" href="#register" id="scroll_up">&nbsp;</a>
					</div>
					
					<div class="header" id="up_title" style="display:none;">
						Sign Up
					</div>
					
				</div>
				
				<div class="slider_form">
					This is some content...
				</div>
			</div>
		</div>
		
	</div>
</div>
  
</body>
</html>
';
?>