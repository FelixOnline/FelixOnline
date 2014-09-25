<?php
$timing->log('blog');
$header = array(
	'title' => $blog->getName().' - Felix Online'
); 

$theme->render('components/header', $header);
?>
<!-- Blog wrapper -->
<!-- Page wrapper -->
	<div class="row felix-pad-top">
		<div class="medium-8 columns">
			<h1><?php echo $blog->getName(); ?></h1>
			<div class="content">
				<?php foreach($blog->getPosts() as $post) { ?>
					<div class="blog-post" id="post<?php echo $post->getId(); ?>">
						<p><?php echo $post->getContent(); ?></p>
						<p>By: <?php echo $post->getAuthor()->getName(); ?></p>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="medium-4 columns">

		</div>
	<!-- End of page container -->
	</div>
<!-- End of page -->

<?php $timing->log('end of blog');?>
<?php $theme->render('components/footer'); ?>
