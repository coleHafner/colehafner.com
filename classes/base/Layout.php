<?
/**
 * A class to handle the layout common to every page on the site.
 * @since	20100425, hafner
 */

require_once( 'base/Common.php' );
require_once( 'base/View.php' );
require_once( 'controllers/Index.php' );

class Layout
{
	/**
	 * Instance of the Common class.
	 * @var	Common
	 */
	protected $m_common;
	
	/**
	 * Name of the current view.
	 * @var	int
	 */
	protected $m_active_controller_name;
	
	/**
	 * Constructs the Layout object.
	 * @return Layout
	 * @since	20100307, hafner
	 * @mod		20100502, hafner
	 * @param	string			$view			name of the controller	
	 */
	public function __construct( $get )
	{
		$this->m_common = new Common();
		$this->m_active_controller_name = ucfirst( strtolower( $get['v'] ) );
		
	}//Layout()
	
	/**
	 * Gets the details for this page. 
	 * @return	array
	 * @since	20100307, hafner
	 * @mod		20100307, hafner
	 */
	public function getPageDetails()
	{
		$v = new View(0);
		return $v->getAllRecords( FALSE );
		
	}//getPageDetails()
	
	/**
	 * Outputs the 'head' section of the HTML document.
	 * @return	string
	 * @since	20100323, hafner
	 * @mod		20100323, hafner
	 */
	public function getHtmlHeadSection()
	{
		$paths = $this->m_common->getPathInfo();
		$file_paths = $paths[$this->m_common->m_env];
		
		$paths = $this->m_common->getPathInfo();
		$file_paths = $paths[$this->m_common->m_env];
		
		$sql = "SELECT alias FROM common_Views WHERE LOWER( controller_name ) = '" . strtolower( $this->m_active_controller_name ) . "'";
		$result = $this->m_common->m_db->query( $sql, __FILE__, __LINE__ );
		$row = $this->m_common->m_db->fetchRow( $result );
		$alias = $row[0];
		
		$sql = "SELECT value FROM common_Settings WHERE LOWER( title ) = 'site-name'";
		$result = $this->m_common->m_db->query( $sql, __FILE__, __LINE__ );
		$row = $this->m_common->m_db->fetchRow( $result );
		$site = $row[0];
		
		return '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" >
		
		<head>
		
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
			
			<title>' . $alias . ' - ' . $site . '</title>
			
			<link rel="stylesheet" href="' . $file_paths['css_ex'] . '/960_grid.css" type="text/css" />
			<link rel="stylesheet" href="' . $file_paths['css_ex'] . '/jquery-ui-1.8.1.custom.css" type="text/css" />
			<link rel="stylesheet" href="' . $file_paths['css'] . '/common.css" type="text/css" />
			
			<script type="text/javascript" src="' . $file_paths['js_ex'] . '/jquery-1.4.2.js"></script>
			<script type="text/javascript" src="' . $file_paths['js_ex'] . '/jquery-ui-1.8.1.custom.min.js"></script>
			<script type="text/javascript" src="' . $file_paths['js_ex'] . '/jquery.hotkeys.js"></script>
			<script type="text/javascript" src="' . $file_paths['js'] . '/jquery.common.js"></script>
			<script type="text/javascript" src="' . $file_paths['js_nerd'] . '/jquery.halfnerd.mail.js"></script>
			
		</head>
		';
		
	}//getHtmlHeadSection()
	
	/**
	 * Outputs the section directly above the unique content for each page.
	 * @return	string
	 * @since	20100323, hafner
	 * @mod		20100323, hafner
	 */
	public function getHtmlBodySection( $login_string )
	{  
		$login = ( strlen( $login_string ) > 0 ) ? $login_string : '';
		$paths = $this->m_common->getPathInfo();
		$nav_items = Index::getNavItems();
		
		if( in_array( $this->m_common->m_env, Common::constructionEnvironments() ) )
		{
			$header_class = '';
			$header_content = '';
			$header_stripe = '';
		}
		else
		{
			$header_class = 'class="header_section bg_dark"';
			$header_stripe = '<div class="logo_stripe"></div>';
			
			$header_content = '
						<div class="logo_container logo_box_shadow">
							<div class="overlay">
								<div class="logo_ne"></div>
								<div class="logo_se"></div>
							</div>
						</div>
						
						<div class="nav_container">
							<table class="nav_table">
								<tr>
							';
		
			foreach( $nav_items as $i => $nav )
			{
				$selected = ( $nav['cmd'] == "about" ) ? 'nav_selector_active' : '';
				
				$header_content .= '
									<td class="' . $nav['cmd'] . '" process="' . $nav['cmd'] . '" slide_num="' . ( $i + 1 ) . '">
										<div class="nav_selector ' . $selected . '" id="nav_selector_' . $nav['cmd'] . '"></div>
										<div class="nav_selector_hover" id="nav_selector_hover_' . $nav['cmd'] . '"></div>
										<a href="#' . $nav['cmd'] . '" class="overlay">&nbsp;</a>
									</td>
									';
			}
			
			$header_content .= '
								</tr>
							</table>
						</div>
						';
			
		}//if site is not under construction
		
		
		$return .= '
		<body>
		
		<!--wrapper-->
		<div class="page" id="page">
		
			<!--spacer section-->
			<div class="spacer_section">&nbsp;</div>
			<!--/spacer section-->
			
			<!--header section-->
			<div ' . $header_class . '>
				<div class="container_12">
					<div class="grid_12 header_grid">
						' . $header_content . '
					</div>
					<div class="clear"></div>
				</div>
				' . $header_stripe . '
			</div>
			<!--/header section-->
			
			<!--content section-->
			<div class="content_section">
				<div class="container_12">
				';
				
		return $return;
		
	}//getHtmlBodySection()
	
	/**
	 * Closes the main HTML tags.
	 * @return	string
	 * @since	20100323, hafner
	 * @mod		20100323, hafner
	 */
	public function getHtmlFooterSection()
	{
		if( in_array( $this->m_common->m_env, Common::constructionEnvironments() ) )
		{
			$footer_content = '';
		}
		else 
		{
			$footer_content = '
			<div style="position:relative;float:left;">
				<table>
					<tr>
						<td>
							<div class="padder_10_right">
								<a href="http://facebook.com/colehafner" target="_blank">
									<img src="/images/icon_facebook_bw.gif" />
								</a>
							</div>
						</td>
						
						<td>
							<div class="padder_10_right">
								<a href="http://forrst.me/colehafner" target="_blank">
									<img src="/images/icon_forrst_bw.gif" />
								</a>
							</div>
						</td>
						
						<td>
							<div class="padder_10_right">
								<a href="http://www.linkedin.com/in/colehafner" target="_blank">
									<img src="/images/icon_linkedIn_bw.gif" />
								</a>
							</div>
						</td>
						
						<td>
							<div class="padder_10_right">
								<a href="http://twitter.com/#!/colehafner" target="_blank">
									<img src="/images/icon_twitter_bw.gif" />
								</a>
							</div>
						</td>
						
						<td>
							<div class="padder_10_right">
								&copy;&nbsp;2011 Cole Hafner
							</div>
						</td>
						
					</tr>
				</table>
			</div>
			
			<div style="position:relative;float:right;">
				<table>
					<tr>
						
						<td align="right">
							<div class="padder_10_right">
								Shortcuts:
							</div>
						</td>
						
						<td>
							<div class="padder_10_right">
								<div class="footer_shortcut bg_dark padder_3_top padder_5_right padder_3_bottom padder_5_left color_white center">
									&uarr;
								</div>
							</div>
						</td>
						
						<td>
							<div class="padder_10_right">
								<div class="footer_shortcut bg_dark padder_3_top padder_5_right padder_3_bottom padder_5_left color_white center">
									&rarr;
								</div>
							</div>
						</td>
						
						<td>
							<div class="padder_10_right">
								<div class="footer_shortcut bg_dark padder_3_top padder_5_right padder_3_bottom padder_5_left color_white center">
									&darr;
								</div>
							</div>
						</td>
						
						<td>
							<div class="padder_10_right">
								<div class="footer_shortcut bg_dark padder_3_top padder_5_right padder_3_bottom padder_5_left color_white center">
									&larr;
								</div>
							</div>
						</td>
						
						<td>
							<div class="padder_10_right">
								<div class="footer_shortcut bg_dark padder_3_top padder_5_right padder_3_bottom padder_5_left color_white center">
									Shift
								</div>
							</div>
						</td>
						
					</tr>
			</div>
			
			<div class="clear"></div>
			';
		}
		
		
		$return = '
				
				</div>
			</div>
			<!--/content section-->
			
			<!--footer section-->
			<div class="footer_section bg_tan">
				<div class="container_12">
					<div class="grid_12">
						' . $footer_content . '
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<!--/footer section-->
			
		</div>
		<!--page wrapper-->
		
		<iframe class="input text_input" style="height:200px;width:600px;margin:20px auto 20px auto;display:none;" id="hidden_frame" name="hidden_frame" ></iframe>
		';
		
		return $return;
		
	}//getHtmlFooterSection()
	
	public function getClosingTags()
	{
		return '
		</body>
		
		</html>
		';
	}//getClosingTags()
	
	/**
	* Get a member variable's value
	* @return	mixed
	* @param	string		$var_name		Variable name to get
	* @since 	20100403, hafner
	* @mod		20100403, hafner
	*/
	public function __get( $var_name )
	{
		$exclusions = array( 'm_common' );
		
		if( !in_array( $var_name, $exclusions ) ) 
		{
			$return = $this->$var_name;
		}
		
		return $return;
		
	}//__get()
	
}//class Layout
?>