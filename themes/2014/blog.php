<?php
$timing->log('blog');
$header = array(
	'title' => $blog->getName().' - Felix Online'
); 

$theme->render('components/header', $header);
?>
<!-- Blog wrapper -->
<div class="container_12">
	<!-- Sidebar -->
	<div class="sidebar grid_4 push_8">
		Sidebar
		<?php 
			/*
			$theme->setSidebar(array(
				'fbActivity', 
				//'mediaBox',
				'mostPopular'
			));
			$theme->renderSidebar();
			*/
		?>
	</div>
	<!-- End of sidebar -->

	<!-- Blog container -->
	<div class="grid_8 pull_4 <?php echo $blog->getSlug(); ?>">
		<h2><?php echo $blog->getName(); ?></h2>
		<div class="content">
			<?php foreach($blog->getPosts() as $post) { ?>
				<div class="blog-post" id="post<?php echo $post->getId(); ?>">
					<p><?php echo $post->getContent(); ?></p>
					<p>By: <?php echo $post->getAuthor()->getName(); ?></p>
				</div>
			<?php } ?>
		</div>
	</div>
	<!-- End of blog container -->
</div>
<!-- End of blog -->
<?php $timing->log('end of blog');?>
<?php $theme->render('components/footer'); ?>
