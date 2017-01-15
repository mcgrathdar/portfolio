<?php while (have_posts()) : the_post(); ?>
  
<div class="row work"> 
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
        <b>Client:</b> <?php the_field('client'); ?><br /> 
        <b>Project:</b> <?php the_field('project'); ?><br />
        <b>Role:</b> <?php the_field('role'); ?>
      </p>
    </div>

  </div>
  <div class="col-md-1 col-xl-1 col-xs-0"></div>
</div>

<?php $image = get_field('main_image'); if( !empty($image) ): ?>
<div class="row work">    
  <div class="col-md-1 col-xl-1 col-xs-0"></div>
    <div class="col-md-10 col-xl-10 col-xs-12">
        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
    </div>
  <div class="col-md-1 col-xl-1 col-xs-0"></div>
</div>
<?php endif; ?>


<?php $image_two = get_field('image_two'); if( !empty($image_two) ): ?>
<div class="row work">

  <div class="col-md-6 col-xl-6 col-xs-12 col-height">
    <img src="<?php echo $image_two['url']; ?>" alt="<?php echo $image_two['alt']; ?>" />
  </div>

  <div class="col-md-5 col-xl-5 col-xs-12 col-height">
    <div class="col-height-text"><?php the_field('image_two_text'); ?></div>
  </div>

</div>
<?php endif; ?>

<?php $image_three = get_field('image_three'); if( !empty($image_three) ): ?>
<div class="row work">
  <div class="col-md-1 col-xl-1 col-xs-0"></div> 

  <div class="col-md-5 col-xl-5 col-xs-12 col-height"> 
      <div class="col-height-text"><?php the_field('image_three_text'); ?></div>
  </div>

  <div class="col-md-6 col-xl-6 col-xs-12 col-height"> 
    <img src="<?php echo $image_three['sizes']['large']; ?>" alt="<?php echo $image_three['alt']; ?>" />
  </div>

</div>
<?php endif; ?>



<?php endwhile; ?>
