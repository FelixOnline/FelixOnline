	<?php 
		/* TODO:
			* Year selector 
				- not include decades with no issues
			* Page for each issue? 
				- comments, social sharing, other issues, and issuu embed
			* Random issue?
				- blast from the past
		*/
	?>
	
	
	<!-- Archive wrapper -->
	<div class="container_12 archive">
		
		<!-- Search -->
		<div id="archivesearchbar" class="grid_6">
			<h3>Search the Felix archive</h3>
			<form method="get" action="">
				<input type="text" name="aq" size="40" placeholder="Search the archive.." value="<?=stripslashes($_GET['aq'])?>" id="searchinput" />
				<input type="submit" value="Search" id="searchbuttonfwd" />
			</form>
		</div>
		<div class="clear"></div>
		
		<?php 
			require_once('inc/config_archive.inc.php'); 
			
        // Search results
		if($_GET['aq']) {
			echo '<div class="issuecont">';
			$q = mysql_escape_string($_GET['aq']);
  
			$sql = "SELECT Issues.PubNo, Issues.IssueNo, PubDate, FileName, Title, MATCH(Content) AGAINST ('$q') as Relevance FROM Files,Issues WHERE MATCH (Content) AGAINST('$q') and Issues.IssueNo=Files.IssueNo and Issues.PubNo=Files.PubNo  HAVING Relevance >= 0.0 ORDER BY Relevance DESC;";
			$rsc = mysql_query($sql);
			$maxr = 0;
			while(list($lp, $li, $ld, $lfn, $lt, $lr) = mysql_fetch_row($rsc)) {
				if($maxr == 0) $maxr = $lr; 
				$date = date("l jS F",strtotime($ld));
				$thumb = substr($FileName,8,(strlen($FileName)-11)).'png';?>
				
				<a href="/archive/<?php echo $lfn; ?>" class="thumbLink">
					<div class="thumb grid_2">
						<div class="issue">
							<?php echo $li; ?>
						</div>
						<img src="../archive/thumbs/<?=date("Y", strtotime($ld))?>_<?=sprintf("%04d", $li)?>_A.png">
						<div class="date">
							<?php echo $date; ?>
						</div>
						<div class="relevance"> Relevance: <?=sprintf("%.2f", (($lr*(min(100, $maxr*200)))/$maxr))?>%</div>
					</div>
				</a>
		
		<?php } ?>
		
		<div class="clear"></div>
		</div>
		<?php } else {
		
			if (!isset($_GET['y']) && !isset($_GET['d']))
				$year = 2013;
			else if (isset($_GET['d'])) 
				$year = $_GET['d'];
			else
				$year = $_GET['y'];
		?>
		
		<h3 class="grid_12">Decades</h3>
		<ul class="tabs">
		
		<?php 
			
			$sql = "SELECT MIN(YEAR(PubDate)) FROM Issues";
			list($start) = mysql_fetch_array(mysql_query($sql,$cid_archive));
			
			$sql = "SELECT MAX(YEAR(PubDate)) FROM Issues";
			list($end) = mysql_fetch_array(mysql_query($sql,$cid_archive));
			$p = 0;
			
            // Loop through years
			for($i = $start; $i <= $end; $i++) {
                // If not the beginning of a decade 
				if($i%10 != 0) {
					$decade[$p] = $i;
                } else {
                    $decade[$p] = $i;
                    if($i+9 < $end) { // as long as 9 years in the future isn't greater than the last year
                        $i = $i+9; // skip forward 9 years
                    } else { // otherwise go the penultimate year
                        $i = $end - 1;
                    }
				}
				$p++;
			}
			
			$last = count($decade) - 1;
			foreach($decade as $key => $value) {
				if($_GET['y'])
					$url_e = explode('year',curPageUrl());
				else if($_GET['aq'])
                    $url_e = explode('?aq',curPageUrl());
				else 
					$url_e = explode('decade',curPageUrl());
				$url = $url_e[0];
                if($decade[$key]%10 == 0 && $decade[($key+1)]%10 !=0) {
                    echo '<li'.((abs($year-$value) < 10 && $year > $value)?' class="current"':'').'><a href="'.$url.'decade/'.$value.'/">'.$value.'-'.($decade[$last]).'</a></li>'.PHP_EOL;
                } else if($decade[$key]%10 != 0 && $decade[$key] != $decade[$last]) {
                    echo '<li'.((abs($year-$decade[$key]) < 10 && $year > $value)?' class="current"':'').'><a href="'.$url.'decade/'.$decade[$key].'/">'.$decade[$key].'</a></li>'.PHP_EOL;
                } else if($value != $decade[$last]){
                    echo '<li'.((abs($year-$value) < 10 && $year > $value)?' class="current"':'').'><a href="'.$url.'decade/'.$value.'/">'.$value.'-'.($decade[$key]+9).'</a></li>'.PHP_EOL;
                }
			}
			?>
		</ul>
		
		<h3 class="grid_12">Years</h3>
			<ul class="tabsyear">
			
		<?php
			if($year%10 == 0) {
				for($year_tab = $year; $year_tab <= ($year+9); $year_tab++) {
					if($_GET['d'])
						$url_e = explode('decade',curPageUrl());
					else if($_GET['aq'])
						$url_e = explode('?aq',curPageUrl());
					else
						$url_e = explode('year',curPageUrl());
					$url = $url_e[0];
					echo '<li'.(($year==$year_tab)?' class="current"':'').'><a href="'.$url.'year/'.$year_tab.'/">'.$year_tab.'</a></li>';
				
					// break clause
					if(($year_tab) == $decade[$last]) 
						$year_tab = (($year-$year%10)+9);
				}
			} else if($year == $decade[0]){
				echo '<li class="current"><a href="'.$url.'year/'.$year.'/">'.$year.'</a></li>';
			} else {
				for($year_tab = ($year-$year%10); $year_tab <= (($year-$year%10)+9); $year_tab++) {
					if($_GET['d'])
						$url_e = explode('decade',curPageUrl());
					else if($_GET['aq'])
						$url_e = explode('?aq',curPageUrl());
					else
						$url_e = explode('year',curPageUrl());
					$url = $url_e[0];
					echo '<li'.(($year==$year_tab)?' class="current"':'').'><a href="'.$url.'year/'.$year_tab.'/">'.$year_tab.'</a></li>';
					
					// break clause
					if(($year_tab) == $decade[$last]) 
						$year_tab = (($year-$year%10)+9);
				}
			}
		?>
		</ul>
		
		<!--<div class="grid_12">
			<h2>Issue Archive - <?php echo $year; ?></h2>
		</div>-->
		
		<div class="issuecont">
		
		<?php
		$sql = "SELECT PubDate,i.IssueNo,FileName FROM Issues AS i INNER JOIN Files AS f ON (i.IssueNo=f.IssueNo AND i.PubNo=f.PubNo) WHERE YEAR(PubDate)='$year' AND i.PubNo = 1 ORDER BY PubDate ASC";
		$rsc = mysql_query($sql,$cid_archive);
		if (mysql_num_rows($rsc)) {
			$i = 1;
			while (list($PubDate,$IssueNo,$FileName) = mysql_fetch_array($rsc)) {
				$date = date("l jS F",strtotime($PubDate));
				$thumb = substr($FileName,8,(strlen($FileName)-11)).'png';
				if (substr($FileName,-5,1)=='A') {// what's this A/B business?? oh god this is awful
				?>
					<a href="/archive/<?php echo $FileName; ?>" class="thumbLink">
						<div class="thumb grid_2">
							<div class="issue">
								<?php echo $IssueNo; ?>
							</div>
							<img src="../archive/thumbs/<?php echo $thumb;?>" alt="<?php echo $thumb;?>">
							<div class="date">
								<?php echo $date; ?>
							</div>
						</div>
					</a>
				<?php 
				if($i%6 == 0) echo '<div class="clear"></div>';
				$i++;
				}
			}
		} else 
			echo '<p class="grid_12">No issues this year.</p>';
		?>
		<div class="clear"></div>
		</div>
		
		<?php if ($year == 2011) { ?>
		<div class="grid_12">
			<h2>The Felix Daily 2011</h2>
		</div>
		
		<?php
		$sql = "SELECT PubDate,i.IssueNo,FileName FROM Issues AS i INNER JOIN Files AS f ON (i.IssueNo=f.IssueNo AND i.PubNo=f.PubNo) WHERE YEAR(PubDate)='$year' AND i.PubNo = 3 ORDER BY PubDate ASC";
		$rsc = mysql_query($sql,$cid_archive);
		if (mysql_num_rows($rsc)) {
			while (list($PubDate,$IssueNo,$FileName) = mysql_fetch_array($rsc)) {
				$date = date("l jS F",strtotime($PubDate));
				$thumb = substr($FileName,8,(strlen($FileName)-11)).'png';
				?>
					<a href="/archive/<?php echo $FileName; ?>" class="thumbLink">
						<div class="thumb grid_2">
							<div class="issue">
								<?php echo $IssueNo; ?>
							</div>
							<img src="../archive/thumbs/<?php echo $thumb;?>" alt="<?php echo $thumb;?>">
							<div class="date">
								<?php echo $date; ?>
							</div>
						</div>
					</a>
				<?php 
			}
		}
		?>
		<div class="clear"></div>
		
		<?php } ?>
		
		<?php } ?>
        <div id="credits" class="grid_12">
            <p>The issue archive was made possible through kind donations from <a href="http://www.imperialcollegeunion.org/">Imperial College Union</a> and the IC Trust.</p>
        </div>
	</div>
	<!-- End of archive wrapper -->
