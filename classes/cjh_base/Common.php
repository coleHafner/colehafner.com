 <?

/**
 * A class to handle common functions.
 * @since 20100508, hafner
 */

require_once( "cjh_base/Database.php" );
require_once( "cjh_base/File.php" );
require_once( "cjh_base/FileType.php" );
require_once( "cjh_base/FileHandler.php" );

class Common {
	
	/**
	 * Instance of the Database class.
	 * @var Database
	 */
	protected $m_db;
	
	/**
	 * Sets the environment( local, dev, live )
	 * @return string
	 */
	protected $m_env;
	
	public function __construct() {
		
		//set relative path
		$this->m_env = $this->determineEnv();
		
		$all_paths = $this->getPathInfo();
		$cur_paths = $all_paths[$this->m_env];
		
		//setup db connection
		$this->m_db = new Database( $cur_paths );
		
	}//constructor()
	
	/**
	 * Generates a link.
	 * @since	20100620, hafner
	 * @return	string
	 * @param 	array		$get		variables collected by the current controller + additional variables
	 */
	public function makeLink( $get )
	{
		$return = "";
		$counter = 0;
		$valid_fields = array( "v", "sub", "id1", "id2" );
			
		foreach( $get as $field => $val )
		{
			$field = strtolower( $field );
			
			if( in_array( $field, $valid_fields ) )
			{
				if( $this->m_env == "local" && FALSE )
				{
					$val = ( strtolower( $field ) == "v" ) ? "_" . $val : $val;
					$return .= "/" . strtolower( $val );
				}
				else
				{
					$delim = ( $counter == 0 ) ? "/?" : "&"; 
					$return .= $delim . $field . "=" . strtolower( $val );
					$counter++;		
				}
			}
		}//loop through controller vars
		
		return $return;
		 	
	}//makeLink()
	
	public function makeFilePath( $type )
	{
		
	}//makeFilePath()
	
	public function getPathInfo() 
	{
		return array(
			'local' => array(
				'absolute' => "/usr/local/www/colehafner.com",
				'web' => "www",
				'css' => "/css",
				'css_ex' => "/css/extensions",
				'images' => "/images",
				'images_ex' => "/images/extensions",
				'user_images' => "/images/users",
				'js' => "/js",
				'js_ex' => "/js/extensions",
				'js_nerd' => "/js/halfnerd",
				'classes' => "/classes",
				'classes_ex' => "/classes/ex",
				'db_host' => "localhost",
				'db_name' => "cjh",
				'db_user' => "cms_user",
				'db_pass' => "passwd1000!"
			),
			
			//dev server
			'dev' => array(
				'absolute' => "/home8/colehafn/public_html/colehafner.com",
				'web' => "www",
				'css' => "/css",
				'css_ex' => "/css/extensions",
				'images' => "/images",
				'images_ex' => "/images/extensions",
				'user_images' => "/images/users",
				'js' => "/js",
				'js_ex' => "/js/extensions",
				'js_nerd' => "/js/halfnerd",
				'classes' => "/classes",
				'classes_ex' => "/classes/ex",
				'db_host' => "localhost",
				'db_name' => "colehafn_cjh",
				'db_user' => "colehafn_cjh",
				'db_pass' => "passwd1000!"
			),
			
			//live server
			'live' => array(
				'absolute' => "/home8/colehafn/public_html/colehafner.com",
				'web' => "www",
				'css' => "/css",
				'css_ex' => "/css/extensions",
				'images' => "/images",
				'images_ex' => "/images/extensions",
				'user_images' => "/images/users",
				'js' => "/js",
				'js_ex' => "/js/extensions",
				'js_nerd' => "/js/halfnerd",
				'classes' => "/classes",
				'classes_ex' => "/classes/ex",
				'db_host' => "localhost",
				'db_name' => "colehafn_cjh",
				'db_user' => "colehafn_cjh",
				'db_pass' => "passwd1000!"
			)
		);
		
	}//getPathInfo()
	
	/**
	 * Turns an array of sql constraints into a string.
	 * @since	20100620, hafner
	 * @return string
	 * @param	array			$constraints		array( '[field_name1]' => '[value1]', '[field_name2]' => '[value2]' etc. . . ) )
	 * @param	array			$operators			if TRUE $constraints = array( [0] => array( '[field_name1]' => '[value1]', ['operator'] => "<= || >= || =" ), [1]  => array( '[field_name2]' => '[value2]', ['operator'] => "<= || >= || =" ) etc. . . ) )
	 */
	public function compileSqlConstraints( $constraints )
	{
		if( is_array( $constraints ) && count( $constraints ) > 0 )
		{	
			$counter = 1;
			$return = " WHERE ";
			$total_vals = count( $constraints );
			
			foreach( $constraints as $field => $val )
			{
				$joiner = ( $counter != $total_vals ) ? " AND" : "";
				$l = ( !is_numeric( $val ) ) ? "'" : "";
				$r = ( !is_numeric( $val ) ) ? "'" : "";
				
				$return .= "
				LOWER( TRIM( " . $field . " ) ) = " . $l .  strtolower( trim( $val ) ) . $r . $joiner;  
				$counter++;
			}
		}
		else
		{
			print_r( $dup_check );
			throw new exception( "Error: Invalid input for Common->compileSqlConstraints()" );
		}
		
		return $return;
	
	}//compileSqlConstraints()
	
	/**
	 * Determines the environment.
	 * @since	20100621, hafner
	 * @return	string
	 */
	public function determineEnv()
	{
		$return = "local";
		$paths = $this->getPathInfo();
		
		$dev_path = $paths['dev']['absolute'] . "/" . $paths['dev']['web'];
		$live_path = $paths['live']['absolute'] . "/" . $paths['live']['web'];
		
		if( file_exists( $dev_path . "/is_dev.txt" ) )
		{
			$return = "dev";	
		}
		else if( file_exists( $live_path . "/is_live.txt" ) )
		{
			$return = "live";	
		}
		
		return $return;
		
	}//determineEnv()
	
	/**
	 * Used primarily in the mdp_helper.php file.
	 * @since	20100628, hafner
	 * @return	mixed
	 * @param	boolean			$return			whether or not the action was a success
	 * @param	string			$message		success/failure message
	 */
	function sendJsonResponse( $return, $message )
	{
		//send JSON header and response
		header( 'Content-type: application/x-json' );
		echo json_encode( $return );
		
	}//sendJsonResponse()
	
	public function convertTimestamp( $ts, $include_time = TRUE )
	{
		$format = ( $include_time ) ? "F j \@ g:i a" : "F d";
		return date( $format, $ts );
	}//convertTimestamp()
	
	public function compileHiddenFields( $array )
	{
		$return = '';
		
		foreach( $array as $k => $v )
		{
			if( $k != "task" && 
				$k != "process" )
			{
				$return .= '
				<input type="hidden" name="' . $k . '" id="' . $k . '" value="' . $v . '"/>
				';
			}
		}
		
		return $return;
		
	}//compileHiddenFields()
	
	public function formatText( $text, $class = "" )
	{
		if( strlen( $class ) > 0 )
		{
			$class = 'class="' . $class . '"';
		}
		
		$text = str_replace( "\\n", "\n", $text );
		
		$return ='<p ' . $class . '>' . $this->convertLinks( $text ) . '</p>';
		
		if( strlen( $text ) > 0 &&
			strpos( $text, "\n" ) )
		{
			$return = '';
			$body_split = explode( "\n", $text );
			
			foreach( $body_split as $p )
			{
				$return .= '<p ' . $class . '>' . $this->convertLinks( $p ) . '</p>'; 
			}
		}
		
		return $return;
		
	}//formatText()
	
	public function convertLinks( $str, $style = "" )
	{
		return ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a target='_blank' ' . $style . ' href=\"\\0\">\\0</a>", $str );
		
	}//convertLinks()
	
	/**
	 * Validates the authenticity of an email address.
	 * Returns TRUE if valid, FALSE otherwise.
	 * @since	20100909, Hafner
	 * @return	boolean
	 * @param 	string			$email			email address to validate
	 */
	public function validateEmailAddress( $email )
	{
		$return = FALSE;
		$email = strtolower( trim( $email ) );
		
		if( !$return )
		{
			if( strlen( $email ) == 0 )
			{
				$return ="You must provide an email address.";
			}
		}
		
		if( !$return )
		{
			if( strpos( $email, "@" ) === FALSE || 
				strpos( $email, "." ) === FALSE ||
				strpos( $email, " " ) !== FALSE )
			{
				$return = "You must provide a valid email address";
			}
		}
		
		return $return;
		
	}//validateEmailAddress()
	
	public function convertViewAlias( $alias, $type )
	{
		switch( strtolower( $type ) )
		{
			case "url":
				$return = strtolower( str_replace( " ", "-", $alias ) );
				break;
				
			case "interface":
				$return = ucfirst( strtolower( str_replace( "-", " ", $alias ) ) );
				break;
				
			default:
				throw new Exception( "Error: Type '" . $type . "' is invalid." );
				break;
		}
		
		return $return;
		
	}//convertViewAlias()
	
	public function getListRecords( $table )
	{
		$return = FALSE;
		
		switch( strtolower( trim( $table ) ) )
		{
			case "common_views":
				$pk = "view_id";
				$title = "alias";
				break;
				
			case "common_sections":
				$pk = "section_id";
				$title = "title";
				break;
				
			default:
				throw new Exception( "Error: Invalid Table Name." );
				break;
		}
		
		$sql = "
		SELECT " . $pk . " AS id," . $title . " AS title
		FROM " . $table . "
		WHERE " . $pk . " > 0 AND active = 1
		";
		
		//echo $sql . "\n<br/>";
		$result = $this->m_db->query( $sql, __FILE__, __LINE__ );
		
		while( $row = $this->m_db->fetchAssoc( $result ) )
		{
			$return[] = $row;
		}
		
		return $return;
		
	}//getListRecords()
	
	/**
	 * Gets HTML
	 * @since	20101007
	 * @author	20101007, hafner
	 * @param	string			$cmd			command html to get
	 * @param	boolean			$is_addition	if we are adding a new record, is_addition = TRUE, FALSE otherwise.			 
	 */
	public static function getHtml( $cmd, $vars = array() )
	{
		switch( strtolower( $cmd ) )
		{
			case "under-construction":
				$return = array( 
					'body' => '
					<div class="under_construction_container">
						This section is  under construction...
					</div>
					' 
				);
				break;
				
			case "full-div":
				$return = array(
					'out' => '<div class="grid_12 rounded_corners"> ',
					'in' => '<div class="padder">' 
				);
				break;
				
			case "title-bar":
				$center = ( array_key_exists( "center", $vars ) && $vars['center'] === FALSE ) ? "" : "center ";
				$return = '
				<div class="padder_10_bottom">
					<div class="' . $center . 'header color_accent ' . $vars['classes'] . '">
						' .  $vars['title'] . '
					</div>
				</div>
				';
				break;
				
			case "selector-module":
			
				$container_style = ( array_key_exists( "container_style", $vars ) ) ? $vars['container_style'] : "";
				$content_style = ( array_key_exists( "content_style", $vars ) ) ? $vars['content_style'] : "";
			
				$return = '
				<div class="rounded_corners bg_color_tan selector_module border_dark_grey" ' . $container_style . '>
				
					<div class="padder">
						<div class="bg_color_white rounded_corners padder center color_orange">
							<b>' . $vars['title'] . '</b>
						</div>
						<div class="center ' . $vars['content_class'] . '" ' . $content_style . '>
							' .  $vars['content'] . '
						</div>
					</div>
					
				</div>
				';
				break;
				
			case "get-form-buttons":
			
				$table_style = ( array_key_exists( "table_style", $vars ) ) ? $vars['table_style'] : 'style="postion:relative;margin:auto;"';
				$container_id = ( array_key_exists( "container_id", $vars ) ) ? 'id="' . $vars['container_id'] . '"' : '';
			
				$return = '
				 <table ' . $table_style . ' ' . $container_id . '>
					<tr>
						<td class="center">
							' . self::getHtml( "get-button", $vars['left'] ) . '
						</td>
						<td>
							&nbsp;
						</td>
						<td class="center">
							' . self::getHtml( "get-button", $vars['right'] ) . '
						</td>
					</tr>
				</table>
				';
				break;
				
			case "get-button":
			
				$style = ( array_key_exists( "extra_style", $vars ) ) ? $vars['extra_style'] : "";
				$extra_classes = ( array_key_exists( "extra_classes", $vars ) ) ? $vars['extra_classes'] : "";
				
				if( !array_key_exists( "href", $vars) )
				{
					$additional_attributes = ( array_key_exists( "additional_attributes", $vars ) ) ? $vars['additional_attributes'] : "";
					$link_guts = 'id="' . $vars['id'] . '" process="' . $vars['process'] . '" ' . $vars['pk_name'] . '="' . $vars['pk_value'] . '"' . $additional_attributes;
				}
				else
				{
					$link_guts = 'href="' . $vars['href'] . '"';
				}
				
				$return = '
				<a ' . $link_guts . ' class="button rounded_corners color_accent center no_hover bg_color_white ' . $extra_classes . '" ' . $style . '>
					' . $vars['button_value'] . '
				</a>
				';
				break;
				
			case "get-button-mega":
				
				$style = ( array_key_exists( "extra_style", $vars ) ) ? $vars['extra_style'] : "";
				$extra_classes = ( array_key_exists( "extra_classes", $vars ) ) ? $vars['extra_classes'] : "";
				
				$return = '
				<a href="' . $vars['href'] . '" class="button rounded_corners center no_hover bg_color_orange ' . $extra_classes . '" ' . $style . ' ignore_hover="1" target="_blank" >
					' . $vars['button_value'] . '
				</a>
				';
				break;
				
			case "get-button-round":
			
				$active = '';
				$border_class = 'border_color_white';
				$link_style = $style = ( array_key_exists( "link_style", $vars ) ) ? $vars['link_style'] : "";
				$inner_div_style = ( array_key_exists( "inner_div_style", $vars ) ) ? $vars['inner_div_style'] : "";
				$additional_attributes = ( array_key_exists( "additional_attributes", $vars ) ) ? $vars['additional_attributes'] : "";
				
				//determine selected
				if( array_key_exists( "selected", $vars ) &&
					$vars['selected'] == 1 )
				{
					$active = 'active="1"';
					$border_class = 'border_color_orange';
				}
				
				//determine link guts
				if( !array_key_exists( "href", $vars) )
				{
					$link_guts = 'href="#" id="' . $vars['id'] . '" process="' . $vars['process'] . '" ' . $vars['pk_name'] . '="' . $vars['pk_value'] . '"' . $additional_attributes;
				}
				else
				{
					$link_guts = 'href="' . $vars['href'] . '"';
				}
				
				$return = '
				<a ' . $link_guts . ' class="no_hover admin_button bg_color_white center ' . $border_class . '" ' . $link_style . ' ' . $active . '>
					<div ' . $inner_div_style . '>
						' . $vars['button_value'] . '
					</div>
				</a>
				';

				break;
				
			case "get-button-square":
				$link_style = $style = ( array_key_exists( "link_style", $vars ) ) ? $vars['link_style'] : "";
				$inner_div_style = ( array_key_exists( "inner_div_style", $vars ) ) ? $vars['inner_div_style'] : "";
				
				if( !array_key_exists( "href", $vars) )
				{
					$link_guts = 'href="#" id="' . $vars['id'] . '" process="' . $vars['process'] . '" ' . $vars['pk_name'] . '="' . $vars['pk_value'] . '"';
				}
				else
				{
					$link_guts = 'href="' . $vars['href'] . '"';
				}
				
				$return = '
				<a ' . $link_guts . ' class="orange_hover square_button bg_color_white center border_color_white rounded_corners" ' . $link_style . '>
					<div ' . $inner_div_style . '>
						' . $vars['button_value'] . '
					</div>
				</a>
				';

				break;
				
			case "selector-module-spacer":
				$return = '	
				<div class="selector_module_spacer"></div>
				';
				break;
				
			case "select-list":
				
				$return = '';
				
				if( is_array( $vars['options'] ) &&
					count( $vars['options'] ) > 0 )
				{
				
					$return = '
					<select name="' . $vars['name'] . '" class="select_list ' . $vars['class'] . '">
						<option value="0">
							' . $vars['default_option'] . '
						</option>
						';
				
					foreach( $vars['options'] as $i => $info )	
					{
						$selected = ( $vars['selected_option'] == $info['id'] ) ? 'selected="selected"' : '';
						
						$return .= '
						<option value="' . $info['id'] . '" ' . $selected . '>
							' . $info['title'] . '
						</option>
						';
					}
						
					$return .= '
					</select>
					';
				}
					
				break;
				
			case "show-missing-controller-message":
				$return = '
				<div class="missing_controller rounded_corners bg_color_light_tan border_dark_grey center font_normal header bold">
					
					<p>
						Error: Controller File <span class="color_accent">"' . $vars['requested_controller'] . '"</span> does not exist.
						<br/>						
						It is expected @ <span class="color_accent">"' . $vars['controller_path'] . '"</span>.
						<br/>
						Please contact your site administrator to remedy this problem.
						<br/>
						&lt;&nbsp;&lt; <a href="#" onClick="history.back();return false;">Go Back</a>
					</p>
					
				</div>
				';
				break;
				
			case "get-reorder-tab":
				
				$extra_container_style = ( array_key_exists(  'extra_container_style', $vars ) ) ? $vars['extra_container_style'] : '' ;
				$extra_content_style = ( array_key_exists(  'extra_content_style', $vars ) ) ? $vars['extra_content_style'] : '' ;
				
				$return = '
				<div class="item_reorder_container" ' . $extra_container_style . '>
					<div class="item_reorder_bar bg_color_accent border_dark_grey"  ' . $extra_content_style . '></div>
					<div class="item_reorder_bar bg_color_accent border_dark_grey"  ' . $extra_content_style . '></div>
					<div class="item_reorder_bar bg_color_accent border_dark_grey" ' . $extra_content_style . '></div>
				</div>
				';
				break;
				
			case "get-side-bar":
				
				$html = '
				<div class="center rounded_corners color_accent border_dark_grey margin_10_bottom side_bar_container">
					<div class="padder">
						Ad 1
					</div>
				</div>
				
				<div class="center rounded_corners color_accent border_dark_grey margin_10_bottom side_bar_container">
					<div class="padder">
						Ad 2
					</div>
				</div>
				
				<div class="center rounded_corners color_accent border_dark_grey margin_10_bottom side_bar_container">
					<div class="padder">
						Ad 3
					</div>
				</div>
				';
				
				$return = array( 'html' => $html );
				break;
				
			case "display-grid":
				
				//table data
				$records = $vars['records'];
				$num_records = count( $records );
				$records_per_row = $vars['records_per_row'];
				$num_rows = ceil( $num_records / $records_per_row );
				
				//item renderer vars
				$html_cmd = $vars['html_cmd'];
				$empty_message = $vars['empty_message'];
				$active_controller = $vars['active_controller'];
				$active_user = new User( Authentication::getLoginUserId() );
								
				//optional vars
				$id = ( array_key_exists( "id", $vars ) ) ? $vars['id'] : '';
				$html_vars = ( array_key_exists( 'html_vars', $vars ) ) ? $vars['html_vars'] : FALSE;
				$extra_style = ( array_key_exists( "extra_style", $vars ) ) ? $vars['extra_style'] : '';
				$extra_classes = ( array_key_exists( "extra_classes", $vars ) ) ? $vars['extra_classes'] : '';
				$is_static = ( array_key_exists( 'is_static', $vars ) && $vars['is_static'] === TRUE ) ? $vars['is_static'] : FALSE;
				
				$html = '
				<table ' . $extra_classes . ' ' . $extra_style . ' ' . $id . '>
				';
				
				if( $num_records > 0 )
				{
					for( $i = 0; $i < $num_rows; $i++ )
					{
						$html .= '
					<tr>			
								';
							
						for( $j = 1; $j <= $records_per_row; $j++ )
						{
						
							$key = $j + ( $records_per_row * $i );
							
							if( $key > $num_records )
							{
								//add empty cell
								$html .= '
						<td>
							&nbsp;
						</td>
						';
								break;
							}
							
						$active_record = $records[$key - 1];
						$content_vars = array( 'active_record' => $active_record, 'options' => $html_vars, 'item_num' => $key );
						$content = ( !$is_static ) ?  call_user_func_array( array( $active_controller, "getHtml" ), array( $html_cmd, $content_vars ) ) : call_user_func_array( $active_controller . '::getHtml', array( $html_cmd, $content_vars ) );
						
						$html .= '
						<td valign="top">
							' . $content['html'] . '
						</td>
						';
						
						}
						
						$html .= '
					</tr>
					';	
								
					}		
				}
				else
				{
					$html .= '
					<tr>
						<td class="center" colspan="2">
							' . $empty_message . '
						</td>
					</tr>
					';
				}
				
				$html .= '
				</table>
				';
				
				$return = array( 'html' => $html );
				break;
				
			case "display-list":
				
				//list data
				$records = $vars['records'];
				$html_cmd = $vars['html_cmd'];
				$empty_message = $vars['empty_message'];
				$active_controller = $vars['active_controller'];
				$active_user = new User( Authentication::getLoginUserId() );
				
				//optional vars
				$id = ( array_key_exists( "id", $vars ) ) ? $vars['id'] : '';
				$html_vars = ( array_key_exists( 'html_vars', $vars ) ) ? $vars['html_vars'] : FALSE;
				$extra_style = ( array_key_exists( "extra_style", $vars ) ) ? $vars['extra_style'] : '';
				$extra_classes = ( array_key_exists( "extra_classes", $vars ) ) ? $vars['extra_classes'] : '';
				$is_static = ( array_key_exists( 'is_static', $vars ) && $vars['is_static'] === TRUE ) ? TRUE : FALSE;
				$show_item_ids = ( array_key_exists( "show_item_ids", $vars ) && $vars['show_item_ids'] === TRUE ) ? TRUE : FALSE;
				
				$html = '
				<ul ' . $extra_classes . ' ' . $extra_style . ' ' . $id . '>
				';
				
				if( count( $records ) > 0 )
				{
					foreach( $records as $i => $active_record )
					{
						$item_id = ( $show_item_ids === TRUE ) ? 'id="' . $active_record->$vars['record_pk'] . '"' : '';
						
						$html .= '
					<li ' . $item_id . '>			
					';	
						$content_vars = array( 'active_record' => $active_record, 'options' => $html_vars );
						$content = ( !$is_static ) ?  call_user_func_array( array( $active_controller, "getHtml" ), array( $html_cmd, $content_vars ) ) : call_user_func_array( $active_controller . '::getHtml', array( $html_cmd, $content_vars ) );
						
						$html .= '
						' . $content['html'] . '
					</li>
					';
						
					}					
				}
				else
				{
					$html .= '
					<li class="center">
						' . $empty_message . '
					</li>
					';
				}
				
				$html .= '
				</ul>
				';
				
				$return = array( 'html' => $html );
				break;
				
			case "secondary-nav":
				
				$common = new Common();
				$extra_classes = ( array_key_exists( 'extra_classes', $vars ) ) ? $vars['extra_classes'] : "";
				$selected_value = ( array_key_exists( 'selected_value', $vars ) ) ? $vars['selected_value'] : "";
				 
				$html = '
				<ul class="side_bar_nav ' . $extra_classes . '">
				';
				
				foreach( $vars['options'] as $option => $display_option )
				{	
					$selected = ( $selected_value == $option ) ? TRUE : FALSE;
					$selected_class = ( $selected ) ? 'selected' : '';
					
					$html .= '
					<li class="' . $selected_class . '">
						<a href="' . $common->makeLink(  array( 'v' => $vars['active_controller_name'], 'sub' => $option ) ) . '" class="color_brown ' . $selected_class . '" >
							' . $display_option . '
						</a>
					</li>
					';
				}
				
				$html .= '
				</ul>
				';
				
				$return = array( 'html' => $html );
				break;
				
			case "get-admin-list-item-classes":
				$return = array( 'html' => "item_container padder_10 rounded_corners bg_color_light_tan border_dark_tan" );
				break;
				
			case "get-admin-item-buttons":
				
				$html = 
				Common::getHtml( "get-button-round", array(
					'id' => "list_item",
					'process' => "mod",
					'pk_name' => "item_id",
					'pk_value' => $vars['item_id'],
					'button_value' => "m",
					'inner_div_style' => 'style="padding-top:2px;padding-left:1px;"',
					'link_style' => 'style="float:right;"' ) ) . '
				
			' . Common::getHtml( "get-button-round", array(
					'id' => "list_item",
					'process' => "delete",
					'pk_name' => "item_id",
					'pk_value' => $vars['item_id'],
					'button_value' => "x",
					'inner_div_style' => 'style="padding-top:2px;padding-left:1px;"',
					'link_style' => 'style="float:right;"' ) ) . '
				
				<div class="clear"></div>
				';
				
				$return = array( 'html' => $html );
				break;
				
			case "get-admin-item-add-button":
				$html = 
				Common::getHtml( "get-button-round", array(
					'id' => "list_item",
					'process' => "add",
					'pk_name' => "item_id",
					'pk_value' => "0",
					'button_value' => "+",
					'inner_div_style' => 'style="padding-top:2px;padding-left:1px;"',
					'link_style' => 'style="float:right;"' ) );
				
				$return = array( 'html' => $html );
				break;
				
			case "get-item-no-edit":
				$return = array( 'html' => '
					<div class="font_no padder" style="float:right;">
						Cannot Edit
					</div>
					'
				 );
				break;
				
			case "construction-message":
				$return = array( 'html' => '
					<div class="construction_container box_shadow bg_dark padder_10">
						<div class="construction_inner bg_white center">
							<div class="construction_text center">
								Under Construction...
							</div>
						</div>
					</div>
					'
				);
				break;
				
			default:
				throw new exception( "Error: Invalid HTML command." );
				break;
		}
		
		return $return;
		
	}//getHtml()
	
	public static function isMobile()
	{
		$mobiles = "up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom";
		return ( preg_match( '/(' . $mobiles . ')/i', strtolower( $_SERVER['HTTP_USER_AGENT'] ) ) ) ? TRUE : FALSE;
		
	}//isMobile()
	
	public function truncateString( $title )
	{
		return ( strlen( $title ) > 48 ) ? substr( $title, 0, 46 ) . "..." : $title; 
	}//truncateString()
	
	public function controllerFileExists( $controller_file_name )
	{
		return file_exists( $this->compileControllerLocationBasePath() . $controller_file_name );
		
	}//controllerFileExists()
	
	public function compileControllerLocationBasePath()
	{
		$paths = $this->getPathInfo();
		$env_paths = $paths[ $this->m_env ];
		
		return $env_paths['absolute'] . $env_paths['classes'] . "/cjh_controllers/";
		
	}//compileControllerLocationBasePath()
	
	function uploadFile( $post, $files )
	{
		$return = 0;
		$file_handler = new FileHandler( $files['file_to_upload'] );
		$file_type = new FileType( $post['file_type_id'] );
		
		$paths = $this->getPathInfo();
		$file_path = $paths[$this->m_env]['absolute'] . "/" . $paths[$this->m_env]['web'] . $file_type->m_directory;
		$file_name = File::getUniqueFileName( $file_handler->m_file_name );
		
		$upload_result = $file_handler->uploadFile( $file_path, $file_name );
		
		if( $upload_result )
		{
			//make new file
			$input = array(
				'file_name' => $file_name,
				'relative_path' => $file_type->m_directory,
				'upload_timestamp' => strtotime( "now" ),
				'file_type_id' => $file_type->m_file_type_id
			);
			
			$file = new File( 0 );
			$file_add_result = $file->add( $input );
			$return = $file->m_file_id;
		}
		
		return $return;
		
	}//uploadFile()
	
	/**
	 * Validates the current view.
	 * Returns the name of the view.
	 * @since	20100323, hafner
	 * @return	string
	 * @param	string		$view		view from the url
	 */
	public static function validateView( $get )
	{
		$common = new Common();	
	
		$sql = "
		SELECT count(*)
		FROM common_Views
		WHERE LOWER( TRIM( controller_name ) ) = '" . strtolower( trim( $get['v'] ) ) . "'
		AND parent_view_id = 0";
		
		$result = $common->m_db->query( $sql, __FILE__, __LINE__ );
		$row = $common->m_db->fetchRow( $result );
		
		return ( $row[0] == 1 ) ? ucfirst( strtolower( $get['v'] ) ) : "Index";
		
	}//validateView()
	
	public static function getPortfolioEntries()
	{
		$return = array(
		
			array( 
				'img' => 'bts', 
				'client' => "Bottom Time Scuba", 
				'type' => "Business", 
				'link' => FALSE, 
				'features' => array( "Cross Browser Compliant", "Custom Framework", "Built in CMS" ), 
				'desc' => "This is my first site. It was a fun little project for a local scuba shop. It was all done in procedural PHP. I added a custom CMS for the client. ",
				'desc_clean' => "This is my first site. It was a fun little project for a local scuba shop. It was all done in procedural PHP. I added a custom CMS for the client. ",
				'featured' => FALSE 
			),
			
			/*
			array( 
				'img' => 'mdp', 
				'client' => "Madness Entertainment", 
				'type' => "Portfolio", 
				'link' => FALSE, 
				'features' => array( "Youtube API Integration", "Custom Framework", "Built in CMS" ),
				'desc' => "This project was for a friend\'s production studio. It integrates with Google\'s YouTube API, so they can showcase their videos via their youTube account.<br/><br/>The client is in the process of switching hosts. It will be online soon.",
				'desc_clean' => "This project was for a friend\'s production studio. It integrates with Google\'s YouTube API, so they can showcase their videos via their youTube account.The client is in the process of switching hosts. It will be online soon.",
				'featured' => FALSE 
			),
			*/
			
			array( 
				'img' => 'pbr', 
				'client' => "Rebekah Hill Photography", 
				'type' => "Portfolio", 
				'link' => FALSE, 
				'features' => array( "Google Photo API Integration", "Custom Framework", "Built in CMS" ),
				'desc' => "This is a photography site made for my friend. It integrates with Google\'s Picasa API and allows content management from Google\'s Picasa service.<div class=\"padder_5_top\">It is still under development.</div>",
				'desc_clean' => "It is a photography site made for my friend. It integrates with Google\'s Picasa API and allows content management from Google\'s Picasa service. It is still under development.",
				'featured' => FALSE 
			),
			
			array( 
				'img' => 'sbc', 
				'client' => "Simple Bicycle Co.", 
				'type' => "Business", 
				'link' => 'http://simplebicycleco.com', 
				'features' => array( "Cross Browser Compliant", "Custom Framework", "Built in CMS" ),
				'desc' => "This site is for a custom frame maker in Washington. It was built on my framework and customized to give my client complete control of the site\'s content.",
				'desc_clean' => "This site is for a custom frame maker in Washington. It was built on my framework and customized to give my client complete control of the site\'s content.",
				'featured' => FALSE 
			),
			
			array( 
				'img' => 'cah', 
				'client' => "Cole and Heather", 
				'type' => "Event", 
				'link' => 'http://coleandheather.colehafner.com', 
				'features' => array( "RSVP Tracking System", "Cross Browser Compliant", "Google Maps Integration" ),
				'desc' => "This is a personal project for my upcoming wedding. It was built on my framework and has a RSVP guest system built in. It also integrates with Google Maps API for easy directions to the wedding.",
				'desc_clean' => "This is a personal project for my upcoming wedding. It was built on my framework and has a RSVP guest system built in. It also integrates with Google Maps API for easy directions to the wedding.",
				'featured' => TRUE 
			),
			
			array( 
				'img' => 'hfn', 
				'client' => "Halfnerd Framework", 
				'type' => "Personal", 
				'link' => "https://github.com/coleHafner/halfnerdcms", 
				'features' => array( "Cross Browser Compliant", "Custom Framework", "Built in Permissions System" ),
				'desc' => "This is the UI for my custom PHP framework. It provides an administration interface for developers and clients alike.<div class=\"padder_5_top\">I have plans to release this framework under the GLP license. It will be soon be available.</div>",
				'desc_clean' => "This is the UI for my custom PHP framework. It provides an administration interface for developers and clients alike. I have plans to release this framework under the GLP license. It will be soon be available.",
				'featured' => TRUE
			),
			
			array( 
				'img' => 'nfv', 
				'client' => "Nifty Photo Viewer", 
				'type' => "Portfolio", 
				'link' => 'http://niftyphotoviewer.colehafner.com', 
				'features' => array( "Google Picasa API Integration", "Cross Browser Compliant", "No server side scripting required!" ),
				'desc' => "This is a fun a little project that utilizes Google's Picasa API. The search feature searches all public photos on Google's Picasa photo service. You can also view a user's album and album sets.",
				'desc_clean' => "This is a fun a little project that utilizes Google's Picasa API. The search feature searches all public photos on Google's Picasa photo service. You can also view a user's album and album sets.",
				'featured' => TRUE
			)
		);
		
		return array_reverse( $return );
		
	}//getPortfolioEntries()
	
	public static function constructionEnvironments()
	{
		return array();
		
	}//constructionEnvironments()
	
	/**
	 * Allows access to this classes member variables.
	 * Returns the requested member variable if not in exceptions array.
	 * @return 	mixed
	 */
	public function __get( $var_name )
	{
		$exclusions = array();
		
		if( !in_array( $var_name, $exclusions ) ) {
			$return = $this->$var_name;
		} else {
			throw new Exception( "Error: Access to member variable '" . $var_name . "' denied." );
		}
		
		return $return;
	}//get()
	
}//class Common
?>
