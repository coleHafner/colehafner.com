<?
/**
 * Controls the home page content.
 * @since	20100425, halfNerd
 */

require_once( "base/Controller.php" );
require_once( "base/Article.php" );
require_once( "base/File.php" );

class Index extends Controller{
	
	/**
	 * Constructs the Index controller object.
	 * @return 	Index
	 * @param	array			$controller_vars		array of variables for current layout.				
	 */
	public function __construct( $controller_vars )
	{
		parent::setControllerVars( $controller_vars );
		
		$this->m_valid_views = array(
			'slider' => "Home"
		);
		
	}//constructor
	
	/**
	 * @see classes/base/Controller#setContent()
	 */
	public function setContent() 
	{
		$this->m_controller_vars['sub'] = $this->validateCurrentView();
		
		
		if( in_array( $this->m_common->m_env, Common::constructionEnvironments() ) )
		{
			$content = Common::getHtml( "construction-message", array() ); 
		}
		else
		{
			$content = $this->getHtml( $this->m_controller_vars['sub'], array() );
		}
		
		//grab home article
		$this->m_content = '
		<div class="grid_12">
			' . $content['html'] . '
		</div>
		<div class="clear"></div>
		';
		
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
			case "slider":
				
				$p_entries = $this->getPortfolioEntries();
				$slides = self::getNavItems();
				
				$html = '
				<div class="widget_holder">
					
					<div class="widget_content_holder">
						<div id="slide_holder">
						';
			
				foreach( $slides as $i => $slide )
				{
					$slide_num = $i + 1;
					$content = $this->getHtml( $slide['cmd'], array() );
					
					$html .= '
							<div class="slide">
								<div class="padder_10">
									' . $content['html'] . '
								</div>
							</div>
							';
				}
					
				$html .= '
						</div>
					</div>
					
					<div class="clear"></div>
				</div>
				
				<div class="port_controls" style="display:none;">
					
					<!--
					<div class="port_control_grid">
						<a href="#" class="overlay show_slide_p" slide_num="1"></a>
					</div>
					-->
					
					<div class="port_control_left">
						<a href="#" class="overlay show_slide_p" direction="back"></a>
					</div>
					
					<div class="port_control_right">
						<a href="#" class="overlay show_slide_p" direction="forward"></a>
					</div>
					
					<div class="clear"></div>
				</div>
				
				
				<input type="hidden" id="max_slides_v" value="' . count( $slides ) . '" />
				<input type="hidden" id="current_slide_v" value="1" />
				<input type="hidden" id="current_slide_v_name" value="' . $slides[0]['cmd'] . '" />
				
				<input type="hidden" id="max_slides_h" value="' . ( count( $p_entries ) + 1 ) . '" />
				<input type="hidden" id="current_slide_h" value="1" />
				
				';
				
				//slide key
				foreach( $slides as $i => $slide )
				{
					$slide_num = $i + 1;
					
					$html .= '
				<input type="hidden" id="slide_v_key_' . $slide_num . '" name="' . $slide['cmd'] . '" />
				';
				}
				
				$return = array( 'html' => $html );
				break;
				
			case 'about':
				
				$return = array( 'html' => '
					<div class="grid_12">
						<div class="featured_container box_shadow bg_white">
							<div class="padder_10">
							
								<div class="featured_nav_container">
									<div class="featured_nav">
									
										<div class="item bg_dark">
											<div class="bg_tan featured_thumb_tiny"></div>
											<div class="featured_selector featured_selector_active"></div>
											<a href="#" class="overlay" feature_num="1"></a>
										</div>
										
										<div class="item spacer"></div>
										
										<div class="item bg_dark">
											<div class="bg_tan featured_thumb_tiny"></div>
											<div class="featured_selector featured_selector_inactive"></div>
											<a href="#" class="overlay" feature_num="2"></a>
										</div>
										
										<div class="item spacer"></div>
											
										<div class="item bg_dark">
											<div class="bg_tan featured_thumb_tiny"></div>
											<div class="featured_selector featured_selector_inactive"></div>
											<a href="#" class="overlay" feature_num="3"></a>
										</div>
										
									</div>
								</div>
								
								<div class="featured_photo_container">
									<div class="padder_10_left">
										<div class="featured_photo_bg bg_dark">
											<div class="featured_photo" id="photo_1">photo 1</div>
											<div class="featured_photo hidden" id="photo_2">photo 2</div>
											<div class="featured_photo hidden" id="photo_3">photo 3</div>
										</div>
									</div>
								</div>
								
								<div class="featured_blurb_container">
									<div class="padder_10_left">
										<div class="featured_blurb_bg">
											<div class="featured_blurb" id="blurb_1">blurb 1</div>
											<div class="featured_blurb hidden" id="blurb_2">blurb 2</div>
											<div class="featured_blurb hidden" id="blurb_3">blurb 3</div>
										</div>
									</div>
								</div>
								
								<div class="clear"></div>
								
							</div>
						</div>
					</div>
					<div class="clear"></div>
				
					<div class="grid_12">
						
						<div class="about_skills">
							<div class="padder_15_top">
								<div class="padder_10_bottom">
									<img src="/images/header_skills.png" />
								</div>
								<img src="/images/about_skills.png" />
							</div>
						</div>
						
						<div class="about_me">
							<div class="padder_15_top">
								<div class="padder_10_bottom">
									<img src="/images/header_about.png" />
								</div>
								<div class="padder_10_bottom">
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper sem id eros 
									semper vel tristique elit feugiat. Suspendisse potenti. Aliquam erat volutpat. Ut et sapien 
									id ipsum posuere hendrerit. Quisque vel sem eros. Nulla ac est quis massa gravida mollis. 
									Vestibulum eu urna vitae sem aliquet ultrices. Pellentesque ut libero in arcu consectetur 
									tristique. Ut at venenatis ipsum. 
								</div>
								<div>
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper sem id eros 
									semper vel tristique elit feugiat. Suspendisse potenti. Aliquam erat volutpat. Ut et sapien 
									id ipsum posuere hendrerit. Quisque vel sem eros. Nulla ac est quis massa gravida mollis. 
									Vestibulum eu urna vitae sem aliquet ultrices. Pellentesque ut libero in arcu consectetur 
									tristique. Ut at venenatis ipsum. 
								</div>
							</div>
						</div>
						
						<div class="clear"></div>
						
					</div>
					
					<div class="clear"></div>
					'
				);
				break;
				
			case 'portfolio':
				
				$slide_num = 2;
				$sites = $this->getPortfolioEntries();
				
				$grid_vars = array( 
					'records' => $sites,
					'records_per_row' => 3,
					'html_cmd' => "portfolio-grid-item",
					'active_controller' => $this,
					'is_static' => FALSE,
					'extra_classes' => 'class="port_grid padder_15_top"'
				);
				
				$port_grid = $this->m_common->getHtml( "display-grid", $grid_vars );
				
				$html = '
				<div id="p_holder">
					<table class="blog_slide_table">
						<tr>
							<td>
								<div class="p_slide" id="p_slide_1">
									<div class="port_spacer">
										<div class="padder_10_bottom">
											<img src="/images/header_work.png" />
										</div>
										
										<div>
											Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper sem id eros semper vel tristique elit feugiat.
										</div>
									</div>
									
									<div class="port_container bg_dark box_shadow">
										<div class="padder_10">
											<div class="port_inner bg_white">
												' . $port_grid['html'] . '
											</div>
										</div>
									</div>
									
								</div>
							</td>
						';
				
				foreach( $sites as $i => $site )
				{
					$html .= '	<td>
								<div class="p_slide" id="p_slide_' . ( $i + 1 ) . '">
									<div class="port_spacer">&nbsp;</div>
									<div class="port_container bg_dark box_shadow">
										<div class="padder_10">
											<div class="port_inner bg_white">
												<div class="padder_10">
													<div class="port_title bg_red box_shadow">
														<div class="padder_10 port_title_text">
															' . $site['client'] . '
														</div>
														<div class="logo_ne"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</td>';
				}
				
				$html .= '
						</tr>
					</table>
				</div>
				
				<!--
				<div class="p_controls">
					<div class="padder_5" style="position:relative;width:140px;">
						<div style="position:relative;float:left;">
							<a href="#" class="show_slide_p" direction="back">&lt;&lt; Prev</a>
						</div>
						<div style="position:relative;float:right;">		
							<a href="#" class="show_slide_p" direction="forward">Next &gt;&gt;</a>
						</div>
						<div class="clear"></div>
					</div>
				</div>
				-->
				';
				
				$return = array( 'html' => $html );
				break;
				
			case "portfolio-grid-item":
				$html = '
				<div class="port_grid_item_container bg_tan box_shadow">
					&nbsp;
					<a href="#" class="overlay bg_white opacity_50 show_slide_p port_magnify" slide_num="' . ( $vars['item_num'] + 1 ) . '" style="display:none;"></a>
				</div>
				';
				
				$return = array( 'html' => $html );
				break;
				
			case 'lab':
				$return = array(
					'html' => '
					<div class="font_title slide_header">
						Labs
					</div>'
				);
				break;
				
			case 'contact':
				$return = array(
					'html' => '
					<div class="font_title slide_header">
						Contact Me
					</div>'
				);
				break;
				
			case 'blog':
				
				$blog_entries = array( 
					array( 'title' => 'Welcome to Blogville. Population me.', 'content' => "this is test content" ),
					array( 'title' => 'Blog1.', 'content' => "this is test content1" ),
					array( 'title' => 'Blog2.', 'content' => "this is test content2" ),
					array( 'title' => 'Blog3.', 'content' => "this is test content3" ) 
				);
				
				$html = '
				<div id="blog_holder">
					<table class="blog_slide_table">
						<tr>
						';
				
				foreach( $blog_entries as $i => $blog )
				{
					$slide_num = $i + 1;
					
					$html .= '
							<td>
								<div class="blog_slide" id="blog_slide_' . $slide_num . '">
									<div class="padder_5 font_title" id="blog_title_' . $slide_num . '">
										' . $blog['title'] . '
									</div>
									<div class="padder_5" id="blog_content_' . $slide_num . '">
										' . $blog['content'] . '
									</div>
								</div>
							</td>
					';
				}
				
				$html .= '
						</tr>
					</table>
				</div>
				
				<div class="blog_controls">
					<div class="padder_5" style="position:relative;padding-top:1px;padding-left:1px;">
						<table class="blog_nav">
							<tr>
								<td>
									<div class="rounded_corners border_solid_grey blog_search_holder">
										<div style="position:relative;float:left;">
											<input type="text" id="blog_search_term" style="margin-right:5px;" class="color_blue"/>
										</div>
										<div id="blog_search_button">
											Search
										</div>
										<div class="clear"></div>
									</div>
								</td>
								';
				
				foreach( $blog_entries as $i => $blog )
				{
					$slide_num = $i + 1;
					$title = ( $slide_num == 1 ) ? "Home" : $slide_num;
					$selected = ( $slide_num == 1 ) ? 'selected_blog' : '';
					
					$html .= '
								<td>
									<a id="blog_control_' . $slide_num . '" class="show_slide_blog rounded_corners border_solid_grey ' . $selected . '" href="#" slide_num="' . $slide_num . '">' . $title . '</a>
								</td>
							';
				}
				
				$html .= '
							</tr>
						</table>
						
						<!--
						<div class="blog_controls_tab padder_5_top center">
							<a href="#" class="font_small">X</a>
						</div>
						-->
						
					</div>
				</div>
				';
				
				$return = array( 'html' => $html );
				break;
				
			default:
				throw new Exception( "Error: Invalid HTML command." );
				break;
		}
		
		return $return;
		
	}//getHtml()
	
	public function getPortfolioEntries()
	{
		return array(
		
			array( 
				'img' => 'bts', 
				'client' => "Bottom Time Scuba", 
				'type' => "Business", 
				'link' => 'http://bottomtimescuba.org', 
				'skills' => "HTML, CSS, MYSQL, PHP", 
				'desc' => "This is my first site. It was a fun little project for a local scuba shop. It was all done in procedural PHP. I added a custom CMS for the client. " 
			),
			
			array( 
				'img' => 'mdp', 
				'client' => "Madness Entertainment", 
				'type' => "Portfolio", 
				'link' => 'http://madnessentertainment.com', 
				'skills' => "HTML, CSS, MYSQL, PHP, jQuery", 
				'desc' => "This project was for a friend\'s production studio. It integrates with Google\'s YouTube API, so they can showcase their videos via their youTube account." 
			),
			
			array( 
				'img' => 'pbr', 
				'client' => "Rebekah Hill Photography", 
				'type' => "Portfolio", 
				'link' => 'http://pbr.halfnerddesigns.com', 
				'skills' => "HTML, CSS, MYSQL, PHP, jQuery", 
				'desc' => "This site is still in production. It was made for my photographer friend and integrates with Google\'s Picasa API so the client can manage their photos via her Picasa account." 
			),
			
			array( 
				'img' => 'sbc', 
				'client' => "Simple Bicycle Co.", 
				'type' => "Business", 
				'link' => 'http://simplebicycleco.com', 
				'skills' => "HTML, CSS, MYSQL, PHP, jQuery", 
				'desc' => "This site is for a custom frame maker in Washington. It was built on my framework and customized to give my client complete control of the site\'s content." 
			),
			
			array( 
				'img' => 'cah', 
				'client' => "Cole and Heather", 
				'type' => "Event", 
				'link' => 'http://coleandheather.com', 
				'skills' => "HTML, CSS, MYSQL, PHP, JS", 
				'desc' => "This is a personal project for my upcoming wedding. It was built on my framework and has a RSVP guest system built in. It also integrates with Google Maps API for easy directions to the wedding." 
			)
		);
		
	}//getPortfolioEntries()
	
	public static function getNavItems()
	{
		return array(
			array( 'cmd' => "about" ),
			array( 'cmd' => "portfolio" ),
			array( 'cmd' => "contact" )
		);
		
	}//getNavItems()
		
}//class Index
?>
