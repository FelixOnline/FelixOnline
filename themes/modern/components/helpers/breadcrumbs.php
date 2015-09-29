<?php
	if($type == 'article') {
		$origin = $origin->getCategory();
	}

	$elements = array();
	$elements[] = $origin;

	while($origin = $origin->getParent()) {
		$elements[] = $origin;
	}

	$elements = array_reverse($elements);

	if($type == 'category') {
		array_pop($elements);
	}
?>
	<ul class="breadcrumbs">
		<li><a href="<?php echo STANDARD_URL; ?>">Home</a></li>
		<?php foreach($elements as $element) { ?>
		<li><a href="<?php echo STANDARD_URL.$element->getURL(); ?>"><?php echo $element->getLabel(); ?></a></li>
		<?php } ?>
		<li class="current"></li>
	</ul>