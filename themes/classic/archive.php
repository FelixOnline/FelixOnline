<?php
$timing->log('issue archive page');

$header = array(
    'title' => 'Issue Archive - '.'Felix Online'
);

$theme->render('header', $header);
?>
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
    
    <h3 class="grid_12">Decades</h3>
        <ul class="tabs">
        <?php foreach($decades as $key => $decade) { ?>  
            <li <?php if($decade['selected']) echo 'class="current"'; ?> >
                <?php if($decade['begin']) { ?>
                    <a href="<?php echo STANDARD_URL; ?>decade/<?php echo $decade['begin']; ?>">
                        <?php echo $decade['begin']; ?>-<?php echo $decade['final']; ?>
                    </a>
                <?php } else { ?>
                    <a href="<?php echo STANDARD_URL; ?>year/<?php echo $decade['final']; ?>">
                        <?php echo $decade['final']; ?>
                    </a>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>
    
    <h3 class="grid_12">Years</h3>
    <ul class="tabsyear">
        <?php for($i = $currentdecade['begin']; $i <= $currentdecade['final']; $i++) { ?>
            <li <?php if($i == $year) echo 'class="current"'; ?>>
                <a href="<?php STANDARD_URL; ?>year/<?php echo $i; ?>">
                    <?php echo $i; ?>
                </a>
            </li>
        <?php } ?>
    </ul>
    
    <div class="issuecont">
    <?php
    /*
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
     */
    ?>
    <div class="clear"></div>
    </div>
    
    <?php if ($year == 2011) { ?>
    <div class="grid_12">
        <h2>The Felix Daily 2011</h2>
    </div>
    
    <?php
    /*
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
     */
    ?>
    <div class="clear"></div>
    
    <?php } ?>
</div>
<!-- End of archive wrapper -->
<?php $timing->log('end of issue archive');?>
<?php $theme->render('footer'); ?>
