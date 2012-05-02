<?php
$timing->log('issue archive page');

$header = array(
    'title' => 'Issue Archive - '.'Felix Online'
);

$theme->resources->addCSS(array('archive.less'));

$theme->render('header', $header);
?>
<!-- Archive wrapper -->
<div class="container_12 archive">
    <!-- Search -->
    <div id="archivesearchbar" class="grid_12">
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
                    <a href="<?php echo STANDARD_URL; ?>issuearchive/decade/<?php echo $decade['begin']; ?>"><?php echo $decade['begin']; ?>-<?php echo $decade['final']; ?></a>
                <?php } else { ?>
                    <a href="<?php echo STANDARD_URL; ?>issuearchive/year/<?php echo $decade['final']; ?>"><?php echo $decade['final']; ?></a>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>
    
    <h3 class="grid_12">Years</h3>
    <ul class="tabsyear">
        <?php 
            if(!$currentdecade['begin']) {
                $currentdecade['begin'] = $currentdecade['final']; 
            }
            for($i = $currentdecade['begin']; $i <= $currentdecade['final']; $i++) { ?>
            <li <?php if($i == $year) echo 'class="current"'; ?>>
                <a href="<?php STANDARD_URL; ?>issuearchive/year/<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php } ?>
    </ul>
    
    <div class="issuecont clearfix">
        <?php 
            if(!empty($issues)) {
                $i = 1;
                foreach($issues as $issue) { ?>
                    <a href="<?php echo $issue->getDownloadURL(); ?>" class="thumbLink">
                        <div class="thumb grid_2">
                            <div class="issue">
                                <?php echo $issue->getIssueNo(); ?>
                            </div>
                            <img src="<?php echo $issue->getThumbnailURL();?>" alt="<?php echo $issue->getIssueNo();?>"/>
                            <div class="date">
                                <?php echo date("l jS F",$issue->getPubDate()); ?>
                            </div>
                        </div>
                        </a>
                    <?php 
                    if($i%6 == 0) echo '<div class="clear"></div>';
                    $i++;
                }
            } else 
                echo '<p class="grid_12">No issues this year.</p>';
            ?>
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
