<div id="iscience">
    <a href="http://dougal.union.ic.ac.uk/media/iscience/"><img src="img/iscience.png"/></a>
	<?php
        // Cache this TODO
        $doc = new DOMDocument();
        $doc->load('http://dougal.union.ic.ac.uk/media/iscience/feed/');
        $arrFeeds = array();
        foreach ($doc->getElementsByTagName('item') as $node) {
            $itemRSS = array ( 
                'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
                'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
                'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue
            );
            array_push($arrFeeds, $itemRSS);
        }
	?>
	<ul>
    <?php for($i=0; $i<3; $i++) { ?>
        <li <?php if($i==2) echo 'class="last"';?>>
            <h5>
                <a href="<?php echo $arrFeeds[$i]['link'];?>">
                    <?php echo $arrFeeds[$i]['title'];?>
                </a>
            </h5>
            <p>
                <?php echo $arrFeeds[$i]['desc'];?>
            </p>
            <p>
                <span>
                    <?php echo getRelativeTime(strtotime($arrFeeds[$i]['date']));?>
                </span>
            </p>
        </li>
    <?php } ?>
    </ul>
</div>
