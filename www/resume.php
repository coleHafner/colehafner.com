<?php

$personal = array(
	'name' => 'COLE HAFNER',
	'phone' => '(503) 551-7496',
	'email' => 'colehafner@gmail.com',
	'portfolio_site' => 'http://colehafner.com'
);

$data = array(
	'professional_summary' => array(
		'title' => 'Web Applications Developer',
		'items' => array(
			'Expert skills in a multitude of web technologies including PHP, HTML, CSS, Javascript, and jQuery.',
			'Extremely organized with a great work ethic and the ability to behave in a professional manner and stay calm under tight project deadlines.',
			'Experience with MVC methods and popular frameworks including Fuel and Wordpress.',
			'Highly motivated with excellent verbal and written communication skills.',
			'Familiar with many database technologies including MySQL, Postgres, and Oracle.',
			'Well versed in object oriented programming.'
		)
	),
	
	'experience' => array(
		array( 
			'header' => 'Steelhead Advertising', 
			'title' => 'Web Applications Developer', 
			'duration' => '07/2008 - present', 
			'desc' => 'I am responsible for designing projects from the ground up. I work with a small team of developers to determine the project\'s every aspect from the database schema to the interface design and everything between.',
			'bullets' => array( 
				'Responsible for designing web applications from the ground up.', 
				'Create and maintain complex relational databases.', 
				'Build clean and intuitive user experiences.',
				'Optimize and enhance existing web applications.',
				'Work with a small team of developers and designers to meet strict deadlines.'
			)
		),
		
		array( 
			'header' => 'Cole Hafner Freelance Web Designs', 
			'title' => 'Developer, Designer, Database Architect, Account Executive', 
			'duration' => '06/2008 - present', 
			'desc' => ' This is my \'on the side\' freelance operation. I am responsible for talking to my clients, getting a feel for their vision, and managing their expectations. I design the UI comps, present them, and, after an agreement is reached, I get to work. All my projects have been built on Half Nerd CMS ( my custom PHP framework ). I always design my interfaces with simplicity in mind. Often times, I build in a custom CMS so my clients can keep the site up to date long after I\'ve left the project. ',
			'bullets' => array(
				'Responsible for managing client\'s expectations.',
				'All projects are built on my own custom framework and include a specialized content management system.',
				'Design custom user interfaces based on each client\'s requirements.'
			)
		),
		
		array( 
			'header' => 'Blackstone Audiobooks', 
			'title' => 'Tech Department Intern', 
			'duration' => '04/2008 - 06/2008', 
			'desc' => ' I helped develop a web application solution to track Blackstone\'s hardware and software inventory. I also helped the system administrator maintain their local network.',	 
			'bullets' => array(
				'Helped the system administrator upgrade and maintain Blackstone\'s local network.',
				'Developed a web application to track Blackstone\'s hardware inventory.',
				
			)
		)
	),
	
	'education' => array(
		array(
			'header' => 'Southern Oregon University',
			'title' => 'Bachelor\'s Degree in Computer Science',
			'desc' => 'Focus on the CS multimedia track with a minor in business administration.',
			'duration' => '09/2004 - 06/2008'
		)
	),
	
	'honors_and_awards' => array(
		array( 
			'header' => 'Iron Web Master Website Design Contest Finalist.',
			'desc' => 'In my senior year of college, I entered a web design contest. The contest had five rounds, each with a different theme. Each contestant had
			a short amount of time in which to build a website matching the current round\'s theme. It was a great learning experience and a lot of fun.',
			'duration' => '03/2008'
		)
	),
	
	'community_service' => array(
		array( 
			'header' => 'YMCA Soccer Coach',
			'desc' => 'My wife and I co-coached a group of Kindergarten-aged children. As a coach, I helped teach the fundamentals of teamwork and good sportsmanship. 
			It was a very rewarding experience.',
			'duration' => '06/2006 - 06/2007'
		)
	)
);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>

<head>

<title>Cole Hafner - Resume</title>

<style type="text/css">

body{
	margin: 0;
	background-color: #999;
	font-family: arial;
	font-size: 11px;
	line-height: 14px;
}

.body{
	
}

.clear{ clear:both; }

.content_desc {
	position: relative;	
	padding-top: 3px;
}

.content_inner {
	position: relative;
	margin: 50px;
	border: 1px solid #ccc;
}

.content_padder {
	position: relative;
	padding: 10px;
}

.content_padder_slim {
	position: relative;
	padding: 5px 10px 5px 10px;
}

.content_item {
	position: relative;
	margin-bottom: 10px;
}

.content_wrapper{
	position: relative;
	margin-top: 30px;
	margin-right: auto;
	margin-bottom: 30px;
	margin-left: auto;
	width: 800px;
	border: 1px solid #000;
	background-color: #FFF;
	-moz-box-shadow: rgba( 0, 0, 0, .5 ) 5px 5px 10px;
	-webkit-box-shadow: rgba( 0, 0, 0, .5 ) 5px 5px 10px;
	box-shadow: rgba( 0, 0, 0, .5 ) 5px 5px 10px;
}

.f_left{ position: relative; float:left; }
.f_right{ position: relative; float:right; }

.font_accent{
	font-weight: bold;
}

.footer{
	
}

.header{
	position: relative;	
	text-align: left;
	line-height: 19px;
	border-bottom: 1px solid #ccc;
}

.name {
	font-size: 14px;
}

.section_header {
	position: relative;
	background-color: #ccc;
	text-align: left;
	font-size: 12px !important;
}

.section_offset {
	position: relative;
	margin-left: 30px;
}

.summary_list {
	position: relative;	
	margin: 0;
	padding: 5px 20px 5px 20px;
	list-style-type: square;
}

.summary_list li{
	padding-bottom: 10px;
}

</style>

</head>

<body>

	<div class="content_wrapper">
	
		<div class="content_inner">
			
			<div class="header">
				<div class="content_padder" id="header_inner">
				
					<span class="font_accent name">
						<?php echo $personal['name']; ?>
					</span> <br/>
					
					<?php echo $personal['phone']; ?> 
					
					<span class="font_accent">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
					
					<a href="mailto:<?php echo $personal['email']; ?>?subject=Resume">
						<?php echo $personal['email']; ?>
					</a> 
					
					<span class="font_accent">&nbsp;&nbsp;|&nbsp;&nbsp;</span> 
					
					<a href="<?php echo $personal['portfolio_site']; ?>" target="_blank">
						<?php echo $personal['portfolio_site']; ?>
					</a>
					
				</div>
			</div>
			
			<div class="body">
			
<?php
//loop through resume data
foreach( $data as $header_title => $item )
{
	$header_string = '';
	$header_split = explode( '_', $header_title );
	
	//compile header string
	foreach( $header_split as $val )
	{
		$header_string .= ucfirst( strtolower( $val ) ) . ' ';
	}
?>
				<div class="section_header">
					<div class="content_padder_slim font_accent">
						<?php echo $header_string; ?>
					</div>
				</div>
				
				<div class="section_offset">
					<div class="content_padder">
<?php
	
	if( $header_title != 'professional_summary' )
	{
		//loop through items in section
		foreach( $item as $sub_item )
		{
			$header = $sub_item['header'];
			$desc = $sub_item['desc'];
			$duration = $sub_item['duration'];
			$title = array_key_exists( 'title', $sub_item ) ? ' - ' .  $sub_item['title'] : false;
?>
						<div class="content_item">
							<div class="font_accent">
								<div class="f_left"><?php echo $header; echo ( $title != false ) ? '<span style="font-style:italic;font-weight:normal;">' .  $title . '</span>' : ''; ?></div>
								<div class="f_right"><?php echo $duration; ?></div>
								<div class="clear"></div>
							</div>
							
							<div style="content_desc">
<?php 
			if( !array_key_exists( 'bullets', $sub_item ) )
			{
				echo $desc; 
			}
			else
			{
?>
								<ul class="summary_list">
<?php
				foreach( $sub_item['bullets'] as $bullet )
				{
?>
									<li><?php echo $bullet; ?></li>
<?php					
				}
?>
								</ul>
<?php
			}
?>
							</div>
						</div>
<?php
						
		}//end inner foreach
		
	}//end if
	else
	{
?>
						<div class="font_accent"><?php echo $item['title']; ?></div>
						
						<ul class="summary_list">
<?php
		foreach( $item['items'] as $sub_item )
		{
?>
							<li><?php echo $sub_item; ?></li>
<?php	
		}
?>
						</ul>
		
<?php
	}//end else
					
?>
					</div><!--end content padder-->
					
				</div><!--end section offset-->		
<?php	
}//end main foreach
?>
				
				
			</div><!--end body-->
			
			<div class="footer">
			</div>
			
		</div><!--end content inner-->
		
	</div><!--end content wrapper-->

</body>

</html>