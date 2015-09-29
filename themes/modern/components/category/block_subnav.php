        <dl class="sub-nav">
          <dt>Also in <?php echo $category->getLabel(); ?>:</dt>
          <?php foreach($category->getChildren() as $child): ?>
          <dd><a href="<?php echo $child->getURL(); ?>"><?php echo $child->getLabel(); ?></a></dd>
          <?php endforeach; ?>
        </dl>