<?php 
	/*
		TODO:
			Make about us editable in back end
			
	
		$db = "media_felix_archive";
			$host = "localhost";
			$user = "media_felix";
			$pass = "B7LxxnrX5etRBf53";
			$cid_archive = mysql_connect($host,$user,$pass, true) or die(mysql_error());
			mysql_select_db($db,$cid_archive) or die(mysql_error());
			
			$year = 2011;
			$sql = "SELECT PubDate,i.IssueNo,FileName FROM Issues AS i INNER JOIN Files AS f ON (i.IssueNo=f.IssueNo AND i.PubNo=f.PubNo) WHERE YEAR(PubDate)='$year' ORDER BY PubDate DESC LIMIT 1";
			$rsc = mysql_query($sql,$cid_archive);
			list($PubDate,$IssueNo,$FileName) = mysql_fetch_array($rsc);
			$date = date("l dS F",strtotime($PubDate));
			$thumb = substr($FileName,8,(strlen($FileName)-11)).'png';
		?>
		
		<h4>This week's paper</h4>
		
		<a href="archive/issue/<?php echo $IssueNo; ?>" class="thumbLink">
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
		</div>
	
	*/

?>


<div class="grid_8 pull_4 featCont layout1">
	<?php 
	
		/*$pg = 'frontpage';
		$sql = "SELECT top_slider_1,top_slider_2,top_slider_3,top_slider_4,top_sidebar_1,top_sidebar_2,top_sidebar_3,top_sidebar_4,top_sidebar_5 FROM `category` WHERE cat='$pg'";
		$top_articles = mysql_fetch_array(mysql_query($sql,$cid));
		list($b1,$b2,$b3,$b4,$c1,$c2,$c3,$c4,$c5) = $top_articles;*/
		
		$other_featured = mysql_fetch_row(mysql_query("SELECT `1`,`2`,`3`,`4` FROM `top_2col`",$cid));
		list($a1,$a2,$a3,$a4) = $other_featured;
		
		// Section a
		$sql = "SELECT `1`,`2`,`3`,`4`,`5`,`6`,`7`,`8` FROM `frontpage` WHERE layout='1' AND section='a'";
		$sectionA = mysql_fetch_array(mysql_query($sql,$cid));
		list($A0,$A1,$A2,$A3,$A4,$A5,$A6,$A7,$A8) = $sectionA;
		// Section b
		$sql = "SELECT `1`,`2`,`3`,`4`,`5`,`6`,`7` FROM `frontpage` WHERE layout='1' AND section='b'";
		$sectionB = mysql_fetch_array(mysql_query($sql,$cid));
		list($B0,$B1,$B2,$B3,$B4,$B5) = $sectionB;
	?>
	<!-- Top story -->
	<div class="grid_8 alpha topstory">
		<?php // Initialise top story ($A1) 
			$article = $A1;
		?>
		<div class="border <?php echo get_article_category_cat($article);?>">
			<h2><a href="<?php echo article_url($article); ?>"><?php echo get_article_title($article);?></a></h2>
			<?php $num_comments = get_article_comments($article); ?>
			<div class="subHeader">
				<p><?php //echo get_article_teaser($article); ?></p>
				<p><?php echo get_article_preview_trunc($article, 50); ?></p>
				<div id="storyMeta" class="<?php if(!$num_comments) echo 'extra'; ?>">
					<ul class="metaList">
						<?php if($num_comments) { ?>
							<li id="comments"><a href="<?php echo article_url($article);?>#commentHeader"><?php echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
						<?php } ?>
						<li id="category"><a href="<?php echo get_article_category_cat($article);?>/" class="<?php echo get_article_category_cat($article);?>"><?php echo get_article_category($article);?></a></li>
					</ul>
				</div>
			</div>
			<div id="topStoryPic">
				<a href="<?php echo article_url($article);?>">
					<img id="topStoryPhoto" alt="<?php echo get_img_title(get_img_id($article,1));?>" src="../inc/timthumb.php?src=../<?php echo get_img_uri(get_img_id($article, 1));?>&h=220px&w=340px&zc=1" height="220px" width="340px">
				</a>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<!-- End of top story -->
				
	<!-- In this issue -->
	<div class="grid_2 push_6 alpha omega thisIssue">
		<h5>In this Issue</h5>
		<div class="thisIssueCont top">
			<a href="<?php echo article_url($B1);?>"><img alt="<?php echo get_img_title(get_img_id($B1,1));?>" src="../inc/timthumb.php?src=../<?php echo get_img_uri(get_img_id($B1,1));?>&w=140px&h=140px&zc=1&a=t" width="140px" height="140px" class="captify" rel="caption2"/><br class="c"/></a>
			<div class="caption1"><a href="<?php echo article_url($B1);?>"><?php echo get_short_article_title($B1);?></a></div>
			<div id="caption2"><a href="<?php echo article_url($B1);?>"><?php echo get_short_article_desc($B1); ?></a></div>
		</div>
		<div class="thisIssueCont">
			<a href="<?php echo article_url($B2);?>"><img alt="<?php echo get_img_title(get_img_id($B2,1));?>" src="../inc/timthumb.php?src=../<?php echo get_img_uri(get_img_id($B2,1));?>&w=140px&h=140px&zc=1&a=t" width="140px" height="140px" class="captify" rel="caption2"/><br class="c"/></a>
			<div class="caption1"><a href="<?php echo article_url($B2);?>"><?php echo get_short_article_title($B2);?></a></div>
			<div id="caption2"><a href="<?php echo article_url($B2);?>"><?php echo get_short_article_desc($B2); ?></a></div>
		</div>
		<div class="thisIssueCont">
			<a href="<?php echo article_url($B3);?>"><img alt="<?php echo get_img_title(get_img_id($B3,1));?>" src="../inc/timthumb.php?src=../<?php echo get_img_uri(get_img_id($B3,1));?>&w=140px&h=140px&zc=1&a=t" width="140px" height="140px" class="captify" rel="caption2"/><br class="c"/></a>
			<div class="caption1"><a href="<?php echo article_url($B3);?>"><?php echo get_short_article_title($B3);?></a></div>
			<div id="caption2"><a href="<?php echo article_url($B3);?>"><?php echo get_short_article_desc($B3); ?></a></div>
		</div>
		<div class="thisIssueCont">
			<a href="<?php echo article_url($B4);?>"><img alt="<?php echo get_img_title(get_img_id($B4,1));?>" src="../inc/timthumb.php?src=../<?php echo get_img_uri(get_img_id($B4,1));?>&w=140px&h=140px&zc=1&a=t" width="140px" height="140px" class="captify" rel="caption2"/><br class="c"/></a>
			<div class="caption1"><a href="<?php echo article_url($B4);?>"><?php echo get_short_article_title($B4);?></a></div>
			<div id="caption2"><a href="<?php echo article_url($B4);?>"><?php echo get_short_article_desc($B4); ?></a></div>
		</div>
		<div class="thisIssueCont">
			<a href="<?php echo article_url($B5);?>"><img alt="<?php echo get_img_title(get_img_id($B5,1));?>" src="../inc/timthumb.php?src=../<?php echo get_img_uri(get_img_id($B5,1));?>&w=140px&h=140px&zc=1&a=t" width="140px" height="140px" class="captify" rel="caption2"/><br class="c"/></a>
			<div class="caption1"><a href="<?php echo article_url($B5);?>"><?php echo get_short_article_title($B5);?></a></div>
			<div id="caption2"><a href="<?php echo article_url($B5);?>"><?php echo get_short_article_desc($B5); ?></a></div>
		</div>
	</div>
	<!-- End of in this issue -->
				
	<?php $article = $A2; ?>
	<div class="grid_6 pull_2 omega alpha featBox <?php echo get_article_category_cat($article);?>">
		<h3><a href="<?php echo article_url($article);?>"><?php echo get_article_title($article);?></a></h3>
		<?php $num_comments = get_article_comments($article); ?>
		<div class="subHeader">
			<p><?php echo get_article_preview_trunc($article, 20); ?></p>
			<div id="storyMeta" class="<?php if(!$num_comments) echo 'extra'; ?>">
				<ul class="metaList">	
					<?php if($num_comments) { ?>
						<li id="comments"><a href="<?php echo article_url($article);?>#commentHeader"><?php echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
					<?php } ?>
					<li id="category"><a href="<?php echo get_article_category_cat($article);?>/" class="<?php echo get_article_category_cat($article);?>"><?php echo get_article_category($article);?></a></li>
				</ul>
			</div>
		</div>
		<div id="secondStoryPic">
			<a href="<?php echo article_url($article);?>">
				<img id="secondStoryPhoto" alt="<?php echo get_img_title(get_img_id($article,1));?>" src="../inc/timthumb.php?src=../<?php echo get_img_uri(get_img_id($article, 1));?>&h=160px&w=220px&zc=1&a=t" width="220px" height="160px">
			</a>
		</div>
	</div>	

	<?php $article = $A3; ?>
	<div class="grid_6 pull_2 omega alpha featBox <?php echo get_article_category_cat($article);?>" id="last">
		<h3><a href="<?php echo article_url($article);?>"><?php echo get_article_title($article);?></a></h3>
		<?php $num_comments = get_article_comments($article); ?>
		<div class="subHeader">
			<p><?php echo get_article_preview_trunc($article, 20); ?></p>
			<div id="storyMeta" class="<?php if(!$num_comments) echo 'extra'; ?>">
				<ul class="metaList">
					<?php if($num_comments) { ?>
						<li id="comments"><a href="<?php echo article_url($article);?>#commentHeader"><?php echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
					<?php } ?>
					<li id="category"><a href="<?php echo get_article_category_cat($article);?>/" class="<?php echo get_article_category_cat($article);?>"><?php echo get_article_category($article);?></a></li>
				</ul> 
			</div>
		</div>
		<div id="secondStoryPic">
			<a href="<?php echo article_url($article);?>">
				<img id="secondStoryPhoto" alt="<?php echo get_img_title(get_img_id($article,1));?>" src="../inc/timthumb.php?src=../<?php echo get_img_uri(get_img_id($article, 1));?>&h=160px;&w=220px&zc=1&a=t" width="220px" height="160px" >
			</a>
		</div>
	</div>	
	
	<div class="grid_6 pull_2 alpha omega featBox bottom">
		<!-- Category -->
		<div class="grid_3 alpha header <?php echo get_article_category_cat($A4);?>">
			<a href="<?php echo get_article_category_cat($A4);?>/" class="cat <?php echo get_article_category_cat($A4);?>"><?php echo get_article_category($A4);?></a>
			<h4><a href="<?php echo article_url($A4);?>"><?php echo get_article_title($A4);?></a></h4>
		</div>
		<div class="grid_3 omega header <?php echo get_article_category_cat($A5);?>">
			<a href="<?php echo get_article_category_cat($A5);?>/" class="cat <?php echo get_article_category_cat($A5);?>"><?php echo get_article_category($A5);?></a>
			<h4><a href="<?php echo article_url($A5);?>"><?php echo get_article_title($A5);?></a></h4>
		</div>
		<div class="clear"></div>
		
		<!-- Pictures -->
		<div id="thirdStoryPic" class="grid_3 alpha">
			<a href="<?php echo article_url($A4);?>"><img id="thirdStoryPhoto" alt="<?php echo get_img_title(get_img_id($A4,1));?>" src="../inc/timthumb.php?src=../<?php echo get_img_uri(get_img_id($A4, 1));?>&w=210px&h=130px&zc=1&a=t" width="210px" height="130px"></a>
		</div>
		<div id="thirdStoryPic" class="grid_3 omega">
			<a href="<?php echo article_url($A5);?>"><img id="thirdStoryPhoto" alt="<?php echo get_img_title(get_img_id($A5,1));?>" src="../inc/timthumb.php?src=../<?php echo get_img_uri(get_img_id($A5, 1));?>&w=210px&h=130px&zc=1&a=t" width="210px" height="130px"></a>
		</div>
		<div class="clear"></div>
		
		<!-- Teaser -->
		<p class="grid_3 alpha"><?php echo get_article_preview_trunc($A4, 25); ?></p>
		<p class="grid_3 omega"><?php echo get_article_preview_trunc($A5, 25); ?></p>
		<div class="clear"></div>
		
		<!-- Story Meta -->
		<?php $num_comments = get_article_comments($A4);?>
		<div id="storyMeta" class="grid_3 alpha <?php if(!$num_comments) echo 'extra';?>">
			<ul class="metaList">
				<?php if($num_comments) { ?>
					<li id="comments"><a href="<?php echo article_url($A4);?>#commentHeader"><?php echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
				<?php } ?>
			</ul>
		</div>
		<?php $num_comments = get_article_comments($A5); ?>
		<div id="storyMeta" class="grid_3 omega <?php if(!$num_comments) echo 'extra';?>"> 
			<ul class="metaList">
				<?php if($num_comments){ ?>
					<li id="comments"><a href="<?php echo article_url($A5);?>#commentHeader"><?php echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
				<?php } ?>
			</ul>
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="grid_6 pull_2 alpha omega newsList">
		<ul>
			<?php $article = $A6; ?>
			<li class="<?php echo get_article_category_cat($article);?>">
				<h4><a href="<?php echo article_url($article);?>" id="title"><?php echo get_article_title($article);?></a> <a href="<?php echo get_article_category_cat($article);?>/" class="<?php echo get_article_category_cat($article);?>"><span id="category"><?php echo get_article_category($article);?></a></span></h4>
				<p><?php echo get_article_preview_trunc($article, 15);?></p>
			</li>
			
			<?php $article = $A7; ?>
			<li class="<?php echo get_article_category_cat($article);?>">
				<h4><a href="<?php echo article_url($article);?>" id="title"><?php echo get_article_title($article);?></a> <a href="<?php echo get_article_category_cat($article);?>/" class="<?php echo get_article_category_cat($article);?>"><span id="category"><?php echo get_article_category($article);?></a></span></h4>
				<p><?php echo get_article_preview_trunc($article, 15);?></p>
			</li>
			
			<?php $article = $A8; ?>
			<li class="<?php echo get_article_category_cat($article);?>">
				<h4><a href="<?php echo article_url($article);?>" id="title"><?php echo get_article_title($article);?></a> <a href="<?php echo get_article_category_cat($article);?>/" class="<?php echo get_article_category_cat($article);?>"><span id="category"><?php echo get_article_category($article);?></a></span></h4>
				<p><?php echo get_article_preview_trunc($article, 15);?></p>
			</li>
		</ul>
	</div>
	<div class="grid_8 alpha omega" id="featuredarticles">
		<h3>Featured Articles</h3>
		<?php $article = 791; 
			// Featured articles
			$sql = "SELECT `1`,`2`,`3` FROM `frontpage` WHERE layout='1' AND section='featured'";
			$featured = mysql_fetch_array(mysql_query($sql,$cid));
			list($F0,$F1,$F2,$F3) = $featured;
		?>
		<a href="<?php echo article_url($F1); ?>">
			<div id="imgcont">
				<img alt="<?php echo get_img_title(get_img_id($F1,1));?>" src="../inc/timthumb.php?src=../<?php echo get_img_uri(get_img_id($F1, 1));?>&w=290px&zc=1" width="290px">
			</div>
			<h4><?php echo get_article_title($F1);?></h4>
		</a>
		<br/><span><?php echo get_article_teaser($F1); ?></span>
		<ul>
			<li>
				Other Articles:
			</li>
			<li>
				<a href="<?php echo article_url($F2); ?>"><?php echo get_article_title($F2);?></a>
			</li>
			<li>
				<a href="<?php echo article_url($F3); ?>"><?php echo get_article_title($F3);?></a>
			</li>
		</ul>
	</div>
	
	<div class="grid_4 alpha commentBox">
		<div class="border">
			<h4>Editorial</h4>
				<?php 
				
					$sql = "SELECT * FROM `article` WHERE author='felix' AND category='2' AND text1 IS NOT NULL ORDER BY date DESC LIMIT 1";
					$result = mysql_query($sql);
					$row = mysql_fetch_array($result);
				?>
				<h3><a href="<?php echo article_url($row['id']); ?>"><?php echo get_article_title($row['id']);?></a></h3>
				<p><?php echo trunc_text(clean_content2(get_article_text($row['id'])), 245); ?> ...</p>
				<span><a href="<?php echo article_url($row['id']);?>" title="Read more" id="readmorelink">Read more</a></span>
		</div>
	</div>
	
	<div class="grid_4 omega">
		<div class="twitterbox">
			<h4>Twitter</h4>
			<div id="twitheader">
				<a href="http://twitter.com/feliximperial" title="Felix Imperial"><img src="/img/felixtwitter.jpg" width="50px" id="felixTwitterlogo"/></a>
				<h5>Felix Imperial</h5>
				<p><a href="http://twitter.com/feliximperial" target="_blank" title="Felix Twitter account">@feliximperial</a> - South Kensington</p>
				<div class="clear"></div>
			</div>
			<ul id="felixtwitterlist">
				<li>Loading....</li>
			</ul>
		</div>
		
		<div id="weather">
			<h4>Weather <span>in South Kensington</span></h4>
		<?php
			$requestAddress = "http://www.google.com/ig/api?weather=SW72BB&hl=en";
			// Downloads weather data based on location - I used my zip code.
			$xml_str = file_get_contents($requestAddress,0);
			// Parses XML 
			$xml = new SimplexmlElement($xml_str);

			foreach($xml->weather as $item) { ?>
				<!-- Current conditions -->	
				<div id="current">
					<img src="http://www.google.com<?php echo $item->current_conditions->icon['data'];?>" title="<?php echo $item->current_conditions->condition['data'];?>"/>
					<p><b>Current</b></p>
					<p id="temp"><?php echo $item->current_conditions->temp_c['data'];?>&#176;C</p>
				</div>
			
			<?php	
				foreach($item->forecast_conditions as $new) { ?>
					<div class="weatherIcon">
						<img src="http://www.google.com<?php echo $new->icon['data']; ?>" title="<?php echo $new->condition['data'];?>"/><br/>
						<p><?php echo $new->day_of_week['data'];?></p>
					<?php
						$low = intval(($new->low['data'] - 32) / 1.8);
						$high = intval(($new->high['data'] - 32) / 1.8);
					?>
						<p id="temp"><?php echo $high;?>&#176;C | <?php echo $low; ?>&#176;C</p>
					</div>
			<?php }
			}
		?>
			<div class="clear"></div>
		</div>
		
		<div id="felixinfo">
			<h3>About Us</h3>
			<p>Felix is the award winning student newspaper of Imperial College London since 1949. Bringing you the best of news and commentary every week.</p>
			<p>If you would like to get involved or ask us a question then feel free to <a href="contact/">contact us</a></p>
		</div>
	</div>
	
</div>