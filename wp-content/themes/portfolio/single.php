<?php $post = get_post($_POST['id']);

	if (in_category('wireframes')) { get_template_part('templates/content-single', get_post_type());
	}
	if (in_category('website')) { get_template_part('templates/content-website', get_post_type());
	}
	if (in_category('motion')) { get_template_part('templates/content-motion', get_post_type());
	}
	

?>
  
