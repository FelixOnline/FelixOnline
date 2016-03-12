							<a href="<?php echo $topic->getUrl(); ?>">
								<div class="article-topic" style="background-image: url('<?php echo $topic->getImage()->getUrl(); ?>');">
									<div class="article-topic-info">
										<h2><?php echo $topic->getName(); ?></h2>
										<?php
											try {
										?>
											<span class="topic-start topic-date"><?php echo date('F Y', $topic->getStartDate()); ?></span> <span class="glyphicons glyphicons-chevron-right"></span> <span class="topic-start topic-date"><?php echo date('F Y', $topic->getEndDate()); ?></span>
										<?php
											} catch (\Exception $e) { }
										?>
									</div>
								</div>
							</a>