<?
/**
 * A class to handle a File record.
 * @since	20100618, hafner
 */

require_once( "cjh_base/Common.php" );
require_once( "cjh/Skill.php" );

class Portfolio
{
	/**
	 * Instance of the Common class.
	 * @var	Common
	 */
	protected $m_common;
	
	/**
	 * PK of the Record.
	 * @var	int
	 */
	protected $m_portfolio_id;
	
	/**
	 * Id of the big img FK = common_Files->file_id
	 * @var	int
	 */
	protected $m_img_big;
	
	/**
	 * Id of the small img FK = common_Files->file_id
	 * @var	int
	 */
	protected $m_img_small;
	
	/**
	 * Id of the title.
	 * @var	string
	 */
	protected $m_title;
	
	/**
	 * Description for this portfolio piece
	 * @var	string
	 */
	protected $m_description;
	
	/**
	 * URL of the current portfolio site.
	 * @var	string
	 */
	protected $m_url;
	
	/**
	 * Timestamp the portfolio piece was added.
	 * @var	string
	 */
	protected $m_timestamp;
	
	/**
	 * Active flag.
	 * @var	boolean
	 */
	protected $m_active;
	
	/**
	 * Array of Skill objects.
	 * @var	array
	 */
	protected $m_skills;
	
	/**
	 * Array of linked objects.
	 * @var	array
	 */
	protected $m_linked_objects;
	
	/**
	 * Constructs the object.
	 * @since	20100618, hafner
	 * @return	State
	 * @param	int				$portfolio_id			id of the current record
	 */
	public function __construct( $portfolio_id, $objects = FALSE )
	{
		$this->m_common = new Common();
		$this->m_form = new FormHandler( 1 );
		$this->m_portfolio_id = ( is_numeric( $portfolio_id ) && $portfolio_id > 0 ) ? $portfolio_id : 0;
		$this->setMemberVars( $objects );
		
	}//constructor
	
	/**
	 * Sets the member variables for this class.
	 * Returns TRUE, always.
	 * @since	20100618, hafner
	 * @return	boolean
	 */
	public function setMemberVars( $objects )
	{
		//get member vars
		$sql = "
		SELECT 
			portfolio_id,
			img_big,
			img_small,
			title,
			description,
			url,
			timestamp,
			active
		FROM 
			cjh_Portfolio
		WHERE 
			portfolio_id = " . $this->m_portfolio_id;
		
		$result = $this->m_common->m_db->query( $sql, __FILE__, __LINE__ );
		$row = ( $this->m_common->m_db->numRows( $result ) > 0 ) ? $this->m_common->m_db->fetchAssoc( $result ) : array();
		
		//set member vars
		$this->m_portfolio_id = $row['portfolio_id'];
		$this->m_img_big = $row['user_id'];
		$this->m_img_small = $row['section_id'];
		$this->m_title = stripslashes( $row['title'] );
		$this->m_description = stripslashes( $row['description'] );
		$this->m_url = stripslashes( $row['url'] );
		$this->m_timestamp = ( $this->m_portfolio_id > 0 ) ? $this->m_common->convertTimestamp( $row['timestamp'], TRUE ) : "";
		$this->m_active = $this->m_common->m_db->fixBoolean( $row['active'] );
		$this->m_linked_objects = ( $objects ) ? $this->setLinkedObjects() : array();
		$this->m_skills = $this->getSkills(); 
		
		return TRUE;
		
	}//setMemberVars()
	
	/**
	* Get an array of data suitable to use in modify
	* @since 	20100618, hafner
	* @return 	array
	* @param 	boolean 		$fix_clob		whether or not to file member variables of CLOB type
	*/
	public function getDataArray( $fix_clob = TRUE ) 
	{
		return array(
			'portfolio_id' => $this->m_portfolio_id,
			'img_big' => $this->m_img_big,
			'img_small' => $this->m_img_small,
			'title' => $this->m_title,
			'description' => $this->m_description,
			'url' => $this->m_url,
			'timestamp' => $this->m_timestamp,
			'active' => $this->m_active,
		);
		
	}//getDataArray()
	
	/**
	* Save with the current values of the instance variables
	* This is a wrapper to modify() to ease some methods of coding
	* @since 	20100618, hafner
	* @return	mixed
	*/
	public function save()
	{
		$input = $this->getDataArray();
		return $this->modify( $input, FALSE );
	}//save()
	
	/**
	 * Adds a new record.
	 * Returns ( int ) Id of record if form data is valid, ( string ) form error otherwise.
	 * @since	20100618,hafner
	 * @return	mixed
	 * @param	array				$input				array of input data
	 */
	public function add( $input )
	{
		$this->checkInput( $input, TRUE );
		
		if( !$this->m_form->m_error )
		{
			//only set upload_timestamp on add
			$req_fields = array( 
				'timestamp' => strtotime( date( "Y-m-d h:i:s" ) ), 
				'img_big' => 0, 
				'img_small' => 0
			);
			
			$input['portfolio_id'] = $this->m_common->m_db->insertBlank( 'cjh_Portfolio', 'portfolio_id', $req_fields );
			
			$this->m_portfolio_id = $input['portfolio_id'];
			$return = $this->m_portfolio_id;
			$this->modify( $input, TRUE );
		}
		else
		{
			$return = $this->m_form->m_error;
		}
		
		return $return;
		
	}//add()
	
	/**
	 * Modifies a record.
	 * Returns ( int ) Id of record if form data is valid, ( string ) form error otherwise. 
	 * @since	20100618, hafner
	 * @return	mixed
	 * @param	array				$input				array of input data
	 * @param	boolean				$from_add			if we are adding a new record, from_add = TRUE, FALSE otherwise.
	 */
	public function modify( $input, $from_add )
	{
		if( !$from_add )
		{
			$this->checkInput( $input, FALSE );
		}

		if( !$this->m_form->m_error )
		{
			$sql = "
			UPDATE 
				cjh_Portfolio
			SET 
				title = '" . $this->m_common->m_db->escapeString( $input['title'] ) . "',
				description = '" .  $this->m_common->m_db->escapeString( $input['description'] ) . "',
				url = '" . $this->m_common->m_db->escapeString( $input['url'] ) . "'
			WHERE 
				portfolio_id = " . $this->m_portfolio_id;
				
			$this->m_common->m_db->query( $sql, __FILE__, __LINE__ );
			
			$return = $this->m_portfolio_id;
		}
		else
		{
			$return = $this->m_form->m_error;
		}
		
		return $return;
		
	}//modify()
	
	/**
	 * Modifies a record.
	 * Returns TRUE, always. 
	 * @since	20100618, hafner
	 * @return	mixed
	 * @param	array				$input				array of input data 
	 */
	public function delete( $deactivate = TRUE )
	{
		if( $deactivate )
		{
			$sql = "
			UPDATE cjh_Portfolio
			SET active = 0
			WHERE portfolio_id = " . $this->m_portfolio_id;
			$this->m_common->m_db->query( $sql, __FILE__, __LINE__ );
		}
		else
		{
			$sql_string = "
			DELETE FROM cjh_PortfolioToSkill
			WHERE portfolio_id = " . $this->m_portfolio_id . "
			--end-sql--
			DELETE FROM cjh_Portfolio
			WHERE portfolio_id = " . $this->m_portfolio_id;
			
			$sql_split = explode( "--end-sql--", $sql_string );
			
			foreach( $sql_split as $sql )
			{
				$this->m_common->m_db->query( $sql, __FILE__, __LINE__ );
			}	
		}
		
		return $this->m_portfolio_id;
		
	}//delete()
	
	/**
	 * Validates the form input for creating/modifying a new File record.
	 * Returns FALSE on success, error message string otherwise.
	 * @since	20100618, hafner
	 * @return	mixed
	 * @param	array			$input			array of data
	 * @param	boolean			$is_addition	if we are adding a new record, is_addition = TRUE, FALSE otherwise.			 
	 */
	public function checkInput( $input, $is_addition )
	{
		//check missing title
		if( !array_key_exists( "title", $input ) || 
			strlen( trim( $input['title'] ) ) == 0 )
		{
			$this->m_form->m_error = "You must select a title.";
		}

		//check missing description
		if( !$this->m_form->m_error )
		{
			if( !array_key_exists( "description", $input ) || 
				strlen( trim( $input['description'] ) ) == 0 )
			{
				$this->m_form->m_error = "You must fill in the description.";
			}
		}
		
		//check missing url
		if( !$this->m_form->m_error )
		{
			if( !array_key_exists( "url", $input ) || 
				strlen( trim( $input['url'] ) ) == 0 )
			{
				$this->m_form->m_error = "You must provide a URL.";
			}
		}
		
		return $this->m_form->m_error;
		
	}//checkInput()
	
	public function setLinkedObjects()
	{
		return array( 
			'img_big' => new File( $this->m_img_big ),
			'img_small' => new File( $this->m_img_small )
		);
		
	}//setLinkedObjects()
	
	public static function getPortfolioEntries( $field, $pk )
	{
		$return = array();
		$common = new Common();
		
		$sql = "
		SELECT portfolio_id
		FROM cjh_Portfolio
		WHERE portfolio_id > 0 AND
		" . $field . " = " . $pk . "
		ORDER BY timestamp DESC";
		
		$result = $common->m_db->query( $sql, __FILE__, __LINE__ );
		
		while( $row = $common->m_db->fetchRow( $result ) )
		{
			$return[] = new Portfolio( $row[0], FALSE );
		}
		
		return $return;
		
	}//getPortfolios()
	
	/**
	* Returns HTML
	* @author	20100908, Hafner
	* @return	array
	* @param	string		$cmd		determines which HTML snippet to return
	* @param	array		$vars		array of variables for the html
	*/
	public static function getHtml( $cmd, $vars = array() )
	{
		$common = new Common();
		
		switch( strtolower( $cmd ) )
		{
			case "get-view-form":
				break;
				
			case "get-edit-form":
				
				$process = "add";
				$record = $vars['active_record'];
				
				if( $record->m_portfolio_id > 0 )
				{
					$process = "modify";
				}
				
				/*
				$form_big = self::getHtml( "get-photo-form", array( 'active_record' => $record, 'file_type' => "img_big" ) );
				$form_small = self::getHtml( "get-photo-form", array( 'active_record' => $record, 'file_type' => "img_small" ) );
				
				<tr>
						<td style="width:50%;">
							<div class="padder_10">
								<span class="title_span">
									Image Small ( 225 x 125 ): 
								</span>
								' . $form_small['html'] . '
							</div>
						</td>
						<td style="width:50%;">
							<div class="padder_10">
								<span class="title_span">
									Image Big ( 600 x 335 ): 
								</span>
								' . $form_big['html'] . '
							</div>
						</td>
					</tr>
					*/
				
				$html = '
				' . Common::getHtml( "title-bar", array( 'title' => ucfirst( $process ) . " Porfolio Entry", 'classes' => '' ) ) . '
				<table style="position:relative;width:100%;">
					<tr>
						<td colspan="2">
							<div class="padder_10">
								<span class="title_span">
									Title:
								</span>
								<input type="text" name="title" class="text_input text_extra_long" value="' . $title  . '" />
							</div>
						</td>
					</tr>
					<tr>
						<td style="width:50%;">
							<div class="padder_10">
								<span class="title_span">
									Url:
								</span>
								<input type="text" name="url" class="text_input text_extra_long" value="' . $url  . '" />
							</div>
						</td>
						<td style="width:50%;">
							<div class="padder_10">
								<span class="title_span">
									Project Type:
								</span>
								<input type="text" name="url" class="text_input text_extra_long" value="' . $url  . '" />
							</div>
						</td>
					</tr>
					
					<tr>
						<td colspan="2">
							<div class="padder_10 padder_no_top">
								<span class="title_span">
									Description: 
								</span>
								<textarea name="description" class="post_body">' . $desc .'</textarea>
							</div>
						</td>
					</tr>
				</table>
				';
				
				$return = array( 'html' => $html );
				break;
			
			case "get-photo-form":
				
				//set user
				$record = $vars['active_record'];
				$unique_file_name = File::getUniqueFileName();
				$img_src = $record->m_linked_objects[$vars['file_type']]->m_file_name;
				
				$file_type_id = $common->m_db->getIdFromTitle( "portfolio image", array(
					'table' => "common_FileTypes",
					'pk_name' => "file_type_id",
					'title_field' => "title" )
				);
				
				$html = '
				<table style="position:relative;width:100px;">
					<tr>
						<td>
							<div class="thumb_holder bg_color_white user_holder padder border_dark_grey">
								<img src="' . $img_src['html'] . '" />
							</div>
						</td>
						<td>
							<form 	method="post" 
									target="hidden_frame"
									enctype="multipart/form-data"
									id="user_image_upload_form" 
									action="/ajax/halfnerd_helper.php?task=user&process=update_photo&user_id=' . $u->m_user_id . '">
									
								<div class="padder_10">
									<input type="file" name="file_to_upload" style="width:180px;" id="user_photo_file" class="user_toggle_photo_options" active_option="file" />
								</div>
								
								<input type="hidden" name="file_type_id" id="file_type_id" value="' . $file_type_id . '" />
								<input type="hidden" name="unique_file_name" id="unique_file_name" value="' . $unique_file_name . '" />	
								
							</form>
							
							<div class="padder_10">' .	
								Common::getHtml( "get-form-buttons", array( 
									
									'left' => array( 
										'pk_name' => "user_id",
										'pk_value' => $u->m_user_id,
										'process' => "update_photo",
										'id' => "user",
										'button_value' => "Save",
										'extra_style' => 'style="width:41px;"' ),
										
									'right' => array(
										'href' => $common->makeLink( array(
										'v' => "users",
										'sub' => $u->m_username ) ),
										'button_value' => "Cancel",
										'extra_style' => 'style="width:41px;"' ),
									 
									'table_style' => 'style="position:relative;float:left;"' ) 
								) . '
							</div>	
						</td>
					</tr>
				</table>
				';
				
				$return = array( 'html' => $html );
				break;
					
			case "get-delete-form":
				break;
							
			case "render-list-item":
				break;
				
			default:
				throw new Exception( "Error: Command '" . $cmd . "' is invalid." );
				break;
		}
		
		return $return;
		
	}//getHtml()
	
	public function getSkills()
	{
		$return = array();
		$sql = "SELECT skill_id FROM cjh_PortfolioToSkill WHERE portfolio_id = " . $this->m_portfolio_id . " ORDER BY title ASC";
		$result = $this->m_common->m_db->query( $sql, __FILE__, __LINE__ );
		
		while( $row = $this->m_common->m_db->fetchRow( $result ) )
		{
			$return[] = new Skill( $row[0] );
		}
		
		return $return;
		
	}//getSkills()
	
	/**
	* Get a member variable's value
	* @author	Version 20100618, hafner
	* @return	mixed
	* @param	string		$var_name		Variable name to get
	*/
	public function __get( $var_name )
	{
		$exclusions = array();

		if( !in_array( $var_name, $exclusions ) )
		{
			return $this->$var_name;
		}
		else
		{
			throw new exception( "Error: Access to member variable '" . $var_name . "' for class '" . get_class( $this ) . "' is denied" );
		}
	}//__get()
	
	/**
	* Set a member variable's value
	* @since	20100618, hafner
	* @return	mixed
	* @param	string		$var_name		Variable name to set
	* @param	string		$var_value		Value to set
	*/
	public function __set( $var_name, $var_value )
	{
		$exclusions = array( 'm_portfolio_id' );

		if( !in_array( $var_name, $exclusions ) )
		{
			$this->$var_name = $var_value;
			return TRUE;
		}
		else
		{
			throw new exception( "Error: Access to member variable '" . $var_name . "' for class '" . get_class( $this ) . "' is denied" );
		}
	}//__set()
	
}//class Portfolio
?>
