  
<?php 
  // Place this at the top of your file
  if (isset($_POST['id'])) {
    $newID = $_POST['id'];  // You need to sanitize this before using in a query

    // Perform some db queries, etc here

    // Format a desired response (text, html, etc)
    $response = id;

    // This will return your formatted response to the $.post() call in jQuery 
    return print_r($response);
  }
?>

<body>
  	<div class="col-xl-12 col-md-12 col-xs-12">
		<div class="gradient">
			<div class="about-section">		
				<div class="about-landing">	
					<div class="identity">
						<img src="<?= get_template_directory_uri();?>/dist/images/logo_300.png">
						<h1>Daragh McGrath<br />
							UX Designer
						</h1>

						<div class="social-bar">
							<a href="http://twitter.com/mcgrathdar" target="_blank">
								<img class="social-button" src="<?= get_template_directory_uri();?>/dist/images/twitter.svg">
							</a>
							<a href="https://ie.linkedin.com/in/mcgrathdar" target="_blank">
								<img class="social-button" src="<?= get_template_directory_uri();?>/dist/images/linkedin.svg">
							</a>
							<a href="mailto:mcgrathdar@gmail.com" target="_blank">
								<img class="social-button" src="<?= get_template_directory_uri();?>/dist/images/mail.svg">
							</a>
						</div>
					</div>
				</div>
				<div class="about-details">
					<div class="work-study-block">
						<div class="employment">
							<h1>Work</h1>
							<div class="about-title-divider"></div>
							<br/>
							<h2>Clickworks</h2>
							<h2>Connectors Marketplace<br />
								Output studios</h2>
						</div>
						<div class="studied">
							<h1>Study</h1>
							<div class="about-title-divider"></div>
							<br />
							<h2>MSc<br /> Creative Digital Media</h2><br />
							<h2>BA<br /> Fine Art</h2>
						</div>
					</div>
					<a href="#" target="_blank">
						<div class="cv">
							<h5>CV</h5>
						</div>
					</a>
				</div>
				<div class="previous-about">
					<img src="<?= get_template_directory_uri();?>/dist/images/previousAboutButton.svg">
				</div>
				<div class="next-about">
					<img src="<?= get_template_directory_uri();?>/dist/images/nextAboutButton.svg">
				</div>
			</div>
			<div class="about-title">
				<h5>About</h5>
			</div>
		</div>
		
		<div class="work-section" id="work">
			<div class="col-md-12 col-xl-12 col-xs-12 work-row">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<?php if ( has_post_thumbnail() ) : ?>
					<a href="javascript:ajaxpage('<?php the_permalink(); ?>', 'contentarea');">
						<div class="col-xl-3 col-md-3 col-sm-6 col-xs-12 work-container" id="<?php the_title(); ?>">
							<div class="overlay">
								<h2><?php the_title(); ?></h2>
							</div>			
							<img src="<?php the_post_thumbnail_url('full'); ?>" />
						</div>		
					</a>
					<?php endif; ?>	
				<?php endwhile; endif; ?>	
 			</div>
 			<div class="work-title">
				<h5>Work</h5>
			</div>
			<!-- <div class="pointer"></div>	 -->
		</div>
			
		<div id="work-detail">
			<div class="close-header">
				<div class="col-md-12 col-xl-12 col-xs-12">
					<img id="close" style="background-image: url('<?= get_template_directory_uri();?>/dist/images/close.png');"/>
				</div>
			</div>
			<div class="row" style="margin-left: 0; margin-right: 0;" style="margin-top: 50px;">
				<div id="contentarea" style="height: auto; width: 100%;"></div>
			</div>
		</div> 
	</div>
</body>
  

