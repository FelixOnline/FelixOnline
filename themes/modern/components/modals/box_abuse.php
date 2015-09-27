			<div id="abuseModal" class="reveal-modal medium info-box" data-reveal>
				<h1>Report comment</h1>
				<p id="abuseModalBlurb">Would you like to report this comment as being abusive or containing inappropriate content? Felix will investigate all reported comments at the soonest possible opportunity. You will not receive a reply from Felix.</p>
				<p id="abuseModalBlurbResult" style="display: none;"></p>
				<p id="abuseModalBlurbWait" style="display: none;">Please wait...</p>
				<div id="abuseModalButtons" class="text-right">
					<div id="bad-comment-id" style="display: none;"></div>
					<a href="<?php echo Utility::currentPageURL(); ?>#commentHeader" class="button radius confirmAbusive">Yes</a>
					<a href="<?php echo Utility::currentPageURL(); ?>#commentHeader" class="button radius closeAbusive">No</a>
				</div>
				<div id="abuseModalButtonsResult" class="text-right" style="display: none;">
					<a href="<?php echo Utility::currentPageURL(); ?>#commentHeader" class="button radius closeAbusive">Close</a>
				</div>
			</div>