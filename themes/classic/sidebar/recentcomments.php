<div class="recentComments">
    <h3>Recent Comments</h3>
    <ul>
        <?php 
            $sql = "SELECT * FROM (
                SELECT comment.article,
                    comment.id,
                    comment.user,
                    user.name,
                    comment.comment,
                    UNIX_TIMESTAMP(comment.timestamp) AS timestamp 
                FROM `comment` LEFT JOIN `user` ON (comment.user=user.user) 
                WHERE active=1 
                UNION SELECT comment_ext.article,
                    comment_ext.id,
                    comment_ext.name,
                    comment_ext.comment,
                    'ext',
                    UNIX_TIMESTAMP(comment_ext.timestamp) AS timestamp 
                FROM `comment_ext` 
                WHERE active=1 
                AND pending=0
            ) AS t 
            ORDER BY timestamp DESC LIMIT ".RECENT_COMMENTS;
            $recent_comments = $db->get_results($sql);
            foreach($recent_comments as $key => $object) {
                var_dump($object);
        ?>
            <li <?php //if($i == $commentlimit) echo 'class="last"';?>>
                <p id="article">
                    On <a href="<?php echo article_url($row['article']); ?>"><?php echo get_article_title($row['article']);?></a>
                </p>
                <p id="comment">
                    <span id="endcomment">
                        <?php echo trim_text(html_entity_decode(nl2br($row['comment'])), 120);?>
                    </span>
                </p>
                <p id="commentinfo">
                    <a href="<?php echo article_url($row['article']); ?>#comment<?php echo $row['id'];?>" title="Go to comment">
                        <?php echo getRelativeTime($row['timestamp']);?>
                    </a> 
                    <span id="commenter">
                        <?php if ($commenter = $row['name']) {  // Check if commenter has a name
                            if ($row['user'] == 'extuser0' || $row['user'] == 'extuser1' || $row['user'] == 'extuser2') { // If commenter has name but is not registered then just output commenter name
                                echo $commenter;
                        } else { // If commenter has name and is registered then provide link to user?> 
                            <a href="user/<?php echo $row['user'];?>/"><?php echo $commenter; ?></a>
                        <?php }
                        } else { // If commenter has no name then just state anonymous
                            echo 'Anonymous';
                        } ?>
                    </span>
                </p>
            </li>
        <?php } ?>
    </ul>
</div>
