<?php while (have_posts()) : the_post(); ?>
  
<div class="row" style="margin-left: 0; margin-right: 0; margin-top: 50px"> 
  <div class="col-md-1 col-xl-1 col-xs-0"></div>
        
  <div class="col-md-10 col-xl-10 col-xs-12">
    <div class="col-md-8 col-xl-8 col-xs-12">
      <h2><?php the_title(); ?></h2>
      <div class="work-title-divider"></div> 
    </div>

    <div class="col-md-4 col-xl-4 col-xs-12"></div>

    <div class="col-md-8 col-xl-8 col-xs-12">
      <?php the_content(); ?>
    </div>   
      
    <div class="col-md-4 col-xl-4 col-xs-12">       
      <p>
        <b>Client:</b> You<br> 
        <b>Project:</b> This<br>
        Hello
      </p>
    </div>

  </div>
  <div class="col-md-1 col-xl-1 col-xs-0"></div>
</div>

<div class="row" style="margin-left: 0; margin-right: 0; margin-top: 50px">    
  <div class="col-md-1 col-xl-1 col-xs-0"></div>
  
  <div class="col-md-10 col-xl-10 col-xs-12">
    <img style="display: block; width: 100%" src="http://www.planwallpaper.com/static/images/canberra_hero_image_JiMVvYU.jpg"/>
    <img style="width: 100%; position: relative;" src="<?= get_template_directory_uri();?>/dist/images/macbook.svg"/>
  </div>

  <div class="col-md-1 col-xl-1 col-xs-0"></div>
</div>

<div class="row" style="margin-left: 0; margin-right: 0; margin-top: 50px">
  <div class="col-md-1 col-xl-1 col-xs-0"></div> 

  <div class="col-md-6 col-xl-6 col-xs-12"> 
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
  </div>

  <div class="col-md-1 col-xl-1 col-xs-0"></div> 
</div>


<div class="row" style="margin-left: 0; margin-right: 0; margin-top: 50px">
  <div class="col-md-1 col-xl-1 col-xs-0"></div> 

  <div class="col-md-6 col-xl-6 col-xs-12">
    <div style="position: relative; height: 290px; width: 69.1%; overflow: hidden; top: 54.5px; left: 110px; z-index: 2; background-color: black;">
      <img style="display: block; margin-left: auto; margin-right: auto; height: 100%" src="http://www.planwallpaper.com/static/images/canberra_hero_image_JiMVvYU.jpg"/>
    </div>  
    <img style="width: 100%; position: relative; top: 0px; left: 0px;" src="<?= get_template_directory_uri();?>/dist/images/macbook.svg"/>
  </div>

  <div class="col-md-1 col-xl-1 col-xs-0"></div> 
</div>

<?php endwhile; ?>
