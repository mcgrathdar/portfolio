<?php $post = get_post($_POST['id']);

	get_template_part('templates/content-single', get_post_type());
	
?>
  
