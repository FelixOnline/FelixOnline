<!-- Article wrapper -->
<?php 
	if($_GET['subpage'] == 'act1') {
		$header = 'Act I';
	} else if ($_GET['subpage'] == 'act2') {
		$header = 'Act II';
	} else if ($_GET['subpage'] == 'act3') {
		$header = 'Act III';
	} else if ($_GET['subpage'] == '' ) {
	
	} else {	
		$nopage = true;
	}
?>
	<div class="container_12 phoenixpage">
	<?php if(!$nopage) { ?>		
		<div class="grid_12">
			<h1><a href="/phoenix/">Phoenix</a></h1>
			<div id="paperinfo">
				<p>The annual arts publication of Imperial College London</p>
				<p><a href="/archive/IC_2011/2011_Phoenix.pdf">Click here for PDF version</a></p>
			</div>
		</div>
		
		<?php
			if(isset($_GET['subpage'])) { ?>
				<div class="grid_12 acts single" id="<?php echo $_GET['subpage']; ?>">
					<h2 class="cover"><?php echo $header; ?></h2>
					<ul>
					<?php
						// Get all articles in act1
						global $cid;
				
						// Get tag id from tag [Act1]
						$sql = "SELECT id FROM topic WHERE name='".$_GET['subpage']."'";
						if(mysql_num_rows(mysql_query($sql,$cid))) {
							$tagid = mysql_result(mysql_query($sql,$cid),0);
				
							$sql = "SELECT * FROM `article` AS a INNER JOIN `article_topic` AS t ON (a.id=t.article_id) WHERE t.topic_id='$tagid' ORDER BY date DESC";
							$rsc = mysql_query($sql);
							while ($row = mysql_fetch_array($rsc)) {
					?>
							<li><a href="<?php echo article_url($row['id']);?>"><?php echo $row['title'];?></a></li>
					<?php } } ?>
					</ul>
				</div>
		<?php } else {
		?>
		
		<div class="grid_12" id="phoenixinfo">
			<div id="editorial">
				<p>In 1887 a humble undergraduate, studying at what would later become Imperial College London, founded an arts publication called the Science Schools Journal. That student’s name was H. G. Wells, and he was to become one of the most celebrated writers of the twentieth century.</p>
				<p>Today, 65 years after Wells’ death, his creation lives on, though transformed beyond all recognition. The rather lacklustre title was soon abandoned in favour of something snappier, and for more than 60 years The Phoenix served as the premier student publication on campus, until the establishment of Felix in 1949.</p>
				<p>In all those years, though, one thing never changed: every single piece published in Phoenix belongs to a college member. The incredible popularity and longevity of the publication are a testament to the sheer volume of artistic activity at Imperial.</p>
				<p>A huge number of clubs and societies, covering all forms of art, are to be found at Imperial. Lunchtime and evening concerts run constantly. Nearly every week, it seems, the Blyth gallery is invaded by some intriguing new exhibition. We truly are blessed by the range of events going on around College.</p>
				<p>With all this in mind, we had high expectations when we began to receive submissions. We were not disappointed. The quantity of moving, truly beautiful work received was staggering, and in fact humbling. This publication would not have been possible without the great talents and efforts of  all those who submitted their work.</p>
				<p>A great many of the contributions relate to the idea of freedom. Allowing this common link to guide the process of production, we have divided the publication into three distinct acts. Each act explores the concept of freedom from a different perspective.</p>
				<p>We hope you enjoy this year’s edition of Phoenix.</p>
			</div>
			
			<div id="coverpage">
				<a href="/archive/IC_2011/2011_Phoenix.pdf" title="Phoenix Issue">
					<img src="../inc/timthumb.php?src=../img/phoenix/cover.jpg&h=350x&zc=1&a=t"/>
				</a>
			</div>
		</div>
		
		<div class="grid_4 acts" id="act1">
			<h2 class="cover"><a href="/phoenix/act1">Act I</a></h2>
			<ul>
			<?php
				// Get all articles in act1
				global $cid;
				
				// Get tag id from tag [Act1]
				$sql = "SELECT id FROM topic WHERE name='act1'";
				if(mysql_num_rows(mysql_query($sql,$cid))) {
					$tagid = mysql_result(mysql_query($sql,$cid),0);
				
					$sql = "SELECT * FROM `article` AS a INNER JOIN `article_topic` AS t ON (a.id=t.article_id) WHERE t.topic_id='$tagid' ORDER BY date DESC";
					$rsc = mysql_query($sql);
					while ($row = mysql_fetch_array($rsc)) {
			?>
				<li><a href="<?php echo article_url($row['id']);?>"><?php echo $row['title'];?></a></li>
			<?php } } ?>
			</ul>
		</div>
		
		<div class="grid_4 acts" id="act2">
			<h2 class="cover"><a href="/phoenix/act2">Act II</a></h2>
			<ul>
			<?php
				// Get all articles in act1
				global $cid;
				
				// Get tag id from tag [Act1]
				$sql = "SELECT id FROM topic WHERE name='act2'";
				if(mysql_num_rows(mysql_query($sql,$cid))) {
					$tagid = mysql_result(mysql_query($sql,$cid),0);
				
					$sql = "SELECT * FROM `article` AS a INNER JOIN `article_topic` AS t ON (a.id=t.article_id) WHERE t.topic_id='$tagid' ORDER BY date DESC";
					$rsc = mysql_query($sql);
					while ($row = mysql_fetch_array($rsc)) {
			?>
				<li><a href="<?php echo article_url($row['id']);?>"><?php echo $row['title'];?></a></li>
			<?php }} ?>
			</ul>
		</div>
		
		<div class="grid_4 acts" id="act3">
			<h2 class="cover"><a href="/phoenix/act3">Act III</a></h2>
			<ul>
			<?php
				// Get all articles in act1
				global $cid;
				
				// Get tag id from tag [Act1]
				$sql = "SELECT id FROM topic WHERE name='act3'";
				if(mysql_num_rows(mysql_query($sql,$cid))) {
					$tagid = mysql_result(mysql_query($sql,$cid),0);
				
					$sql = "SELECT * FROM `article` AS a INNER JOIN `article_topic` AS t ON (a.id=t.article_id) WHERE t.topic_id='$tagid' ORDER BY date DESC";
					$rsc = mysql_query($sql);
					while ($row = mysql_fetch_array($rsc)) {
			?>
				<li><a href="<?php echo article_url($row['id']);?>"><?php echo $row['title'];?></a></li>
			<?php } } ?>
			</ul>
		</div>
		
		<?php } ?>
	<?php } else { 
			include_once('404cont.php');
		}
	?>
	</div>
	<!-- End of article wrapper -->