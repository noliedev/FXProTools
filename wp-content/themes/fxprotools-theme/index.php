<?php get_header(); ?>

<div class="wrapper wrapper-content animated fadeInLeft">
	
	<?php
		dd( AuthorizeNetException::testAuthentication() );
	?>

</div>

<?php get_footer(); ?>