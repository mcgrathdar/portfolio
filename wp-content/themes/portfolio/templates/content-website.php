<?php while (have_posts()) : the_post(); ?>
  
<div class="row" > 
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
        <b>Client:</b> <?php the_field('client'); ?><br> 
        <b>Project:</b> <?php the_field('project'); ?>
      </p>
    </div>

  </div>
  <div class="col-md-1 col-xl-1 col-xs-0"></div>
</div>

<?php $image = get_field('main_image');
if( !empty($image) ): ?>
<div class="row" >    
  <div class="col-md-1 col-xl-1 col-xs-0"></div>
    <div class="col-md-10 col-xl-10 col-xs-12">
        <img style="display: block; width: 100%"  src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
    </div>
  <div class="col-md-1 col-xl-1 col-xs-0"></div>
</div>
<?php endif; ?>


<div class="row" >
  <div class="col-md-1 col-xl-1 col-xs-0"></div> 

  <div class="col-md-5 col-xl-5 col-xs-12"> 
     <?php $image_two = get_field('image_two');
      if( !empty($image_two) ): ?>
        <img style="display: block; width: 100%"  src="<?php echo $image_two['url']; ?>" alt="<?php echo $image_two['alt']; ?>" />
      <?php endif; ?>
  </div>

  <div class="col-md-5 col-xl-5 col-xs-12"> 
    <div class="entry-content">
      <?php the_field('image_two_text'); ?>
    </div>
  </div>

  <div class="col-md-1 col-xl-1 col-xs-0"></div> 
</div>




<?php endwhile; ?>
