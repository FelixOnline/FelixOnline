		<?php
			if ($article->canComment($currentuser)) {
		?>
			<div class="callout secondary article-comments-say">
				Have something to say? <a href="<?php echo Utility::currentPageURL().'#commentForm';?>"><b>Post your comment now</b></a>.
			</div>
		<?php
			}
		?>

		<!-- Comments container -->
		<?php
			$comments = $article->getValidatedComments();

			if (is_array($comments)) {
				foreach($comments as $key => $comment) {
					if(!is_null($comment->getReply())) {
						continue;
					}
					$theme->render('components/article/main_comment', array('comment' => $comment));
				}
			}
		?>
		<!-- End of comments container -->
		
		<!-- Comment form -->
		<?php if ($article->canComment($currentuser)) { ?>
		<div class="new-comment" id="commentForm">
			<h4>Submit your comment</h4>
			<p>Your comment is submitted in agreement to our <a data-open="commentPolicy">commenting policy</a>, which also sets out how we moderate comments.</p>

			<?php if(isset($comment_status)): ?>
			<!-- Errors -->
			<div class="callout alert">
				<?php echo $comment_status; ?>
			</div>
			<?php endif; ?>

			<?php if($comment_validate_email): ?>
			<!-- Email Validation -->
			<div class="callout warning">
				Thank you for your comment, you now need to validate your email before your comment will show up. Check your inbox for a validation code.
				<?php $_POST = array(); /* Clear form */ ?>
			</div>
			<?php endif; ?>

			<div class="callout ajax-comment-error alert" style="display: none;">
			</div>

			<form method="post" action="<?php echo Utility::currentPageURL();?>#commentForm" class="comment-form">
				<div class="row">
					<div class="small-12 columns">
						<div class="row">
							<div class="medium-2 columns">
								<label for="name" class="inline">Your name</label>
							</div>
							<div class="medium-9 columns end">
								<input type="text" name="name" id="name" value="<?php if(isset($_POST['name'])): echo $_POST['name']; elseif($currentuser->isLoggedIn()): echo $currentuser->getName(); endif;?>">
								<p id="name-help" class="input-help">We'll pick "Anonymous" if you don't provide us with one</p>
							</div>
						</div>
						<div class="row">
							<div class="medium-2 columns">
								<label for="email" class="inline">Email address</label>
							</div>
							<div class="medium-9 columns end">
								<input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])): echo $_POST['email']; endif;?>" required>
								<p id="email-help" class="input-help">We will not show this, but we may send you a link to verify your email</p>
							</div>
						</div>
						<div class="row">
							<div class="medium-2 columns">
								<label class="inline" for="comment">Comment</label>
							</div>
							<div class="medium-9 columns end">
								<?php if($_POST['replyComment']): ?>
								<div id="commentReply">
									<span id="replyLink"><b>Replying to:</b> <?php echo $_POST['replyName']; ?> at <?php echo $_POST['replyDate']; ?></span> 
									<a href="#" id="removeReply">
										<span class="glyphicons glyphicons-circle-remove" title="Remove reply"></span>
									</a>
									<input type="hidden" id="replyComment" name="replyComment" value="<?php echo $_POST['replyComment']; ?>"/>
									<input type="hidden" id="replyDate" name="replyDate" value="<?php echo $_POST['replyDate']; ?>"/>
								</div>
								<?php endif; ?>
								<textarea name="comment" id="comment" rows="4" required><?php if(isset($_POST['comment'])) echo $_POST['comment']; ?></textarea>
							</div>
						</div>
						<input type="hidden" name="article" value="<?php echo $article->getId(); ?>">
						<div class="row">
							<div class="medium-2 columns">
								&nbsp;
							</div>
							<div class="medium-9 columns end">
								<input type="submit" class="button small radius" value="Submit comment">
							</div>
						</div>
					</div>
				</div>
			</form>

			<div class="comment-form-spin" style="display: none;">
				<p class="text-center"><img src="<?php echo STANDARD_URL; ?>/img/loading.gif" alt="Loading"></p>
			</div>

			<input type="hidden" name="new-token" id="new-token" value="<?php echo Utility::generateCSRFToken('new_comment'); ?>"/>
		</div>
		<?php } else { ?>
			<div class="callout secondary">
				<i>This article is now closed for new comments.</i>
			</div>
		<?php } ?>
		<!-- End of comment form -->