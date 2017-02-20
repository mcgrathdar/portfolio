  
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
					<div class="employment">
						<h2>Employment</h2>
						<div class="about-divider-container-one">
							<div class="about-divider"></div>
						</div><br />
						<p>
							Clickworks<br />
							Connectors Marketplace<br />
							Output Studios
						</p>
						<br /><br />
						<h2>Education</h2>
						<div class="about-divider-container-two">
							<div class="about-divider"></div>
						</div>
						<br />
						<p>
							MSc Creative Digital Media<br />
							BA Fine Art
						</p>
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
		
		<div class="work-section">
			<div class="col-md-12 col-xl-12 col-xs-12 work-row" id="work">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<?php if ( has_post_thumbnail() ) : ?>

						<div class="col-xl-3 col-md-3 col-sm-6 col-xs-12 work-container" id="<?php the_title(); ?>">
							<a ic-post-to="<?php the_permalink(); ?>"  ic-target='#contentarea'>
								<div class="overlay-title">
									<h2><?php the_title(); ?></h2>
								 	<div class="work-title-overlay-divider"></div> 
								</div>
								<div class="overlay"></div>			
								<img src="<?php the_post_thumbnail_url('full'); ?>" />
							</a>
						</div>		
					
					<?php endif; ?>	
				<?php endwhile; endif; ?>	
 			</div>
 			<div class="work-title">
				<h5>Work</h5>
			</div>
			
			<!-- <div class="pointer"></div>	 -->
			
			<div id="work-detail">
				<div class="close-header">
					<div class="col-md-12 col-xl-12 col-xs-12">
						<img id="close" style="background-image: url('<?= get_template_directory_uri();?>/dist/images/close.png');"/>
					</div>
				</div>
				<div id="contentarea"></div>
			</div> 
		</div>
	</div>
</body>
  

