            <li class="has-dropdown<?php if($check instanceof \FelixOnline\Core\User && $check->getUser() == $currentuser->getUser()): echo ' active'; endif; ?>">
              <a href="<?php echo STANDARD_URL.'user/'.$currentuser->getUser(); ?>">
                <span class="glyphicons glyphicons-parents"></span>
                <span>
                  <span class="icon-text-pad show-for-small-only"><?php echo $currentuser->getName(); ?></span>
                </span>
              </a>
              <ul class="dropdown">
                <li class="show-for-medium-up">
                  <a href="<?php echo STANDARD_URL.'user/'.$currentuser->getUser(); ?>">
                    <span class="glyphicons glyphicons-parents"></span>
                    <span>
                      <span class="icon-text-pad"><?php echo $currentuser->getName(); ?></span>
                    </span>
                  </a>
                </li>
                <li class="show-for-medium-up divider"></li>
                <?php if($currentuser->getRoles() != null): ?>
                <li>
                  <a href="<?php echo \FelixOnline\Core\Settings::get('app_admin'); ?>">
                    <span class="glyphicons glyphicons-cogwheels"></span>
                    <span>
                      <span class="icon-text-pad">Administration</span>
                    </span>
                  </a>
                </li>
              <?php endif; ?>
                <li>
                  <a href="<?php echo STANDARD_URL.'logout?goto='.Utility::currentPageURL(); ?>">
                    <span class="glyphicons glyphicons-log-in right-pad"></span>
                    <span>
                      <span class="icon-text-pad">Log out</span>
                    </span>
                  </a>
                </li>
              </ul>
            </li>