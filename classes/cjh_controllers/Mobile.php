<?
/**
 * Controls the home page content.
 * @since	20100425, halfNerd
 */

require_once( "cjh_base/Controller.php" );
require_once( "cjh_base/Article.php" );
require_once( "cjh_base/File.php" );

class Mobile extends Controller{
	
	/**
	 * Constructs the Index controller object.
	 * @return 	Index
	 * @param	array			$controller_vars		array of variables for current layout.				
	 */
	public function __construct( $controller_vars )
	{
		parent::setControllerVars( $controller_vars );
		
		$this->m_valid_views = array(
			'index' => "Home ( Mobile )"
		);
		
	}//constructor
	
	/**
	 * @see classes/base/Controller#setContent()
	 */
	public function setContent() 
	{
		$this->m_controller_vars['sub'] = $this->validateCurrentView();
		$slides = array( 'main', 'content' );
		
		$content = '
			
			<input type="hidden" id="current_slide" value="1" />
			<input type="hidden" id="max_slides" value="' . count( $slides ) . '" />
		
			<!--slide canvas-->
			<div id="slide_canvas">
			';
			
		foreach( $slides as $slide_num => $slide_name )
		{
			$slide_content = self::getHtml( $slide_name, array( 'slide_num' => $slide_num ) );
			$slide_num += 1;
			
			$content .= '
			
				<!--slide ' . $slide_num . ' start-->
				<div class="slide" id="slide_' . $slide_num . '">
				
					<!--padder-->
					<div class="padder_15" id="slide_content_' . $slide_num . '"> 	
						' . $slide_content['html'] . '
					</div>
					<!--padder-->
					
				</div>
				<!--slide ' . $slide_num . ' end-->
				';
			
		}//loop through slides

		$content .= '
				<div class="clear"></div>
				
			</div>
			<!--slide canvas-->
			';
			
			$this->m_content = $content;
		
	}//setContent()
	
	/**
	 * @see classes/base/Controller#getContent()
	 */
	public function getContent() 
	{
		return $this->m_content;
	}//getContent()
	
	public function getHtml( $cmd, $vars = array() ) 
	{
		switch( strtolower( trim( $cmd ) ) )
		{
			case "main":
				
				$items = self::getSlideInfo();
				$list = $this->getHtml( "get-list-module", $items );
				
				$html = ' 
						<table>
							<tr>
								<td>
									<div class="logo_holder box_shadow">
										<img src="/images/logo_mobileTransparent.png" />
									</div>
								</td>
								<td>
									<div class="padder_10_left">
										<h1 style="color:#000000;">Cole Hafner</h1>
									</div>
								</td>
							</tr>
						</table>
						
						<h3>Click an Option</h3>
						' . $list['html'];
				
				$return = array( 'html' => $html );
				break;
				
			case "content":
				
				$html = '';
				$items = self::getSlideInfo();				
				
				foreach( $items['items'] as $i => $item )
				{
					$header = $this->getHtml( "slide-header", array( 'header_text' => $item['slide_name'] ) );
					$content = $this->getHtml( "slide-content", array( 'slide_name' => $item['slide_name'] ) );
					
					$html .= '
						<div style="display:none;" id="' . $item['slide_name'] . '">
							' . $header['html'] . '
							' . $content['html'] . '
						</div>
						';
				}
				
				$return = array( 'html' => $html );
				break;
				
			case "slide-header":
				
				$return = array( 'html' => '
							<div class="slide_header bg_blue">
								<div style="position:relative;height:100%;width:100%;">
									<span class="header_text">' . ucfirst( strtolower( $vars['header_text'] ) ) . '</span>
									<div class="slide_header_gradient"> </div>
									
									<div class="prev_arrow bg_blue next" slide_num="1" show="">
										<div style="position:relative;height:100%;width:100%;">
											<div class="slide_header_gradient"></div>
											<span class="prev_text">Home</span>
										</div>
									</div>
									
								</div>
							</div>
							'
				);
				break;
				
			case "slide-content":
				
				$module_classes = $this->getHtml( "get-css", array( 'type' => "list-module-container" ) );
				
				$html = '
							<div class="slide_header_offset">
							';
				
				switch( $vars['slide_name'] )
				{
					case "about":
						
						$ab_art = Article::getArticleFromTags( "index", "about_me_blurb" );
						
						$a_vars = array( 
							'paragraphs' => $ab_art[0]->splitBody(), 
							'open_tag' => '<div class="default_line_height" style="margin-top:10px;">',
							'close_tag' => '</div>' 
						);
						
						$p_text = Article::getHtml( "pretty-article", $a_vars );
						
						$html .= '
								<table style="margin-bottom:20px;">
									<tr>
										<td>
											<div class="logo_holder box_shadow">
												<img src="/images/logo_mobileTransparent.png" />
											</div>
										</td>
										<td>
											<div class="padder_10_left">
												<h1 style="color:#000000;">Cole Hafner</h1>
											</div>
										</td>
									</tr>
								</table>
								
								<div class="' . $module_classes['html'] . '">
									<div class="padder_10_right padder_10_bottom padder_10_left">
										' . str_replace( array( '<b>', '</b>' ), array( '', '' ), $p_text['html'] ) . '
									</div>
								</div>
								';
						break;
						
					case "portfolio":
						
						$row_vars = array(  
							'line_height' => FALSE, 
							'type' => "list-module-row",
							'item_count' => 2
						);
						
						$sites = Common::getPortfolioEntries();
						$top_vars = array_merge( $row_vars,  array( 'i' => 0 ) );
						$bottom_vars = array_merge( $row_vars,  array( 'i' => 1, 'hover_enabled' => FALSE ) );
						
						$row_classes_top = $this->getHtml( "get-css", $top_vars );
						$row_classes_bottom = $this->getHtml( "get-css", $bottom_vars );
						
						foreach( $sites as $i => $site )
						{
							$go_to = ( $site['link'] !== FALSE ) ? ' go_to="' . $site['link'] . '"' : '';
							
							$html .= '
									<div class="' . $module_classes['html'] . '" style="margin-bottom:20px;">
										<div class="' . $row_classes_top['html'] . '"' . $go_to . '>
											<div class="padder_15"  style="text-align:left;">
												<img src="/images/site_' . $site['img'] . '_mid.jpg" />
											</div>
										</div>
										<div class="' . $row_classes_bottom['html'] . '">
											<div class="padder_10">
												<div class="padder_10_bottom" style="font-weight:bold;">
													' . $site['client'] . '
												</div>
												' . $site['desc_clean'] . '
											</div>
										</div>
									</div>
									';
						}
						break;
						
					case "contact":
						break;
				}
				
				$html .= '
							</div>
							';
				
				$return = array( 'html' => $html );
				break;
				
			case "get-list-module":
				
				$items = $vars['items'];
				$count = count( $items );
				$classes = $this->getHtml( "get-css", array( 'type' => "list-module-container" ) );
				
				$html = '
						<div class="' . $classes['html'] . '">
						';
				
				foreach( $items as $i => $item )
				{
					$classes = $this->getHtml( "get-css", array( 'type' => "list-module-row", 'i' => $i, 'item_count' => $count ) );
					
					$html .= '
							<div class="' . $classes['html'] . '" slide_num="' . $item['slide_num'] . '" show="' . $item['slide_name'] . '">
								<div class="padder_10">
									<div class="left padder_10_right">
										<div class="icon border_round_slight" style="background-color:'  .$item['icon'] . '">
										</div>
									</div>
									
									<div class="left icon_text">
										' . ucfirst( strtolower( $item['slide_name'] ) ) . '
									</div>
									
									<div class="right icon_text padder_10_right">
										>
									</div>
									
									<div class="clear"></div>
								</div>
							</div>
							';
					
				}//loop through sections
				
				$html .= '
						</div>
						';
				
				$return = array( 'html' => $html ); 
				break;
				
			case "get-css":
				
				switch( $vars['type'] )
				{
					case "list-module-container":
						
						$html = "mobile_module bg_white border_grey border_round_all border_all";
						break;
						
					case "list-module-row":
						
						$i = $vars['i'];
						$num_items = ( $vars['item_count'] - 1 );
						$border_top = ( $i == 0 ) ? "border_round_top" : "";
						$border_bottom = ( $i == $num_items ) ? "border_round_bottom" : "";
						$border_width = ( $i == $num_items ) ? "" : "border_bottom border_grey";
						$common_css = ( !array_key_exists( "line_height", $vars ) ) ? "mobile_row_lh" : "";
						$common_css .= ( !array_key_exists( "hover_enabled", $vars ) ) ? " next mobile_row" : " mobile_row";
						
						$html = $common_css . " " . $border_top . " " . $border_bottom . " " . $border_width;
						break;
				}
				
				$return = array( 'html' => $html );
				break;
				
			default:
				throw new Exception( "Error: HTML command '" . $cmd . "' is invalid." );
				break;
		}
		
		return $return;
		
	}//getHtml()
	
	public static function getSlideInfo()
	{
		return array( 
			'items' => array(
				array( 'slide_num' => "2", 'slide_name' => "about", 'icon' => "#FF0000" ),
				array( 'slide_num' => "2", 'slide_name' => "portfolio", 'icon' => "#00FF00" ),
				array( 'slide_num' => "2", 'slide_name' => "contact", 'icon' => "#0000FF" ) 
			)
		);
	}//getSlideInfo()
			
}//class Mobile
?>