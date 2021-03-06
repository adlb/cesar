                
                <!-- TOPBAR -->
                <a href="<?php echo url(array('controller' => 'site', 'view' => 'home')) ?>" class="navbar-brand navbar-logo pull-left">
                    <img src="templates/Repute/theme/assets/img/logo.png" alt="<?php disp($obj,'title'); ?>">
                </a>
                
                <div class="topbar">
                    <div id="top-nav">
                        <ul class="list-inline top-nav">
                            <?php if (count($obj['languages'])>1) { ?>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" role="button">
                                    <img src="templates/Repute/theme/assets/img/flags/<?php echo $obj['language'] ;?>.png" alt="<?php echo $obj['language'] ;?>">
                                    <?php t(':LANG_'.strtoupper($obj['language'])); ?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right country-selector" role="menu">
                                    <?php foreach($obj['languages'] as $lg) { ?>
                                    <li>
                                        <a class="<?php echo $lg['active'] ? "" : "cx_adminonly" ?>"
                                           href="../<?php echo $lg['name'] ;?>/">
                                        <img src="templates/Repute/theme/assets/img/flags/<?php echo $lg['name'] ;?>.png" alt="<?php echo $lg['name'] ;?>"> <?php t(':LANG_'.strtoupper($lg['name'])); ?> </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                            <?php if ($obj['user']) { ?>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" role="button">
                                    <?php disp($obj['user'], 'email') ?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="<?php echo url(array('controller' => 'user', 'view' => 'profil', 'id' => $obj['user']['id'])) ?>"><?php t(':MY_PROFIL') ?></a></li>
                                    <li><a href="<?php echo url(array('controller' => 'user', 'action' => 'logout')) ?>"><?php t(':LOGOUT') ?></a></li>
                                </ul>
                            </li>
                            <?php } else { ?>
                            <li>
                                <a href="?controller=user&view=login"><?php t(':LOGIN') ?></a>
                            </li>
                            <?php } ?>
                            <?php if ($obj['user']['role'] == 'Administrator') { ?>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" role="button">
                                    <?php t(':ADMINISTRATION') ?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="<?php echo url(array('controller' => 'builder', 'view' => 'editArticle')) ?>"><?php t(':ADD_ARTICLE')?></a></li>
                                    <li><a href="<?php echo url(array('controller' => 'builder', 'view' => 'config')) ?>"><?php t(':GLOBAL_SETUP')?></a></li>
                                    <li><a href="<?php echo url(array('controller' => 'user', 'view' => 'userList')) ?>"><?php t(':USER_MANAGEMENT')?></a></li>
                                    <li><a href="<?php echo url(array('controller' => 'donation', 'view' => 'donationList')) ?>"><?php t(':DONATION_MANAGEMENT')?></a></li>
                                    <li><a href="<?php echo url(array('controller' => 'translationManager', 'view' => 'translationList')) ?>"><?php t(':ARTICLE_MANAGEMENT')?></a></li>
                                    <li><a href="<?php echo url(array('controller' => 'medias', 'view' => 'medias')) ?>"><?php t(':MEDIA_MANAGEMENT')?></a></li>
                                </ul>
                            </li>
                            <?php if ($obj['user']['role'] == 'Translator') { ?>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" role="button">
                                    <?php t(':ADMINISTRATION') ?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="<?php echo url(array('controller' => 'translationManager', 'view' => 'translationList')) ?>"><?php t(':ARTICLE_MANAGEMENT')?></a></li>
                                </ul>
                            </li>
                            <?php } ?>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" role="button">
                                    <?php t(':HELP') ?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="<?php echo url(array('controller' => 'site', 'view' => 'help', 'titleKey' => 'articleWriting')) ?>"><?php t(':ARTICLE_WRITE')?></a></li>
                                    <li><a href="<?php echo url(array('controller' => 'site', 'view' => 'help', 'titleKey' => 'homePageSetup')) ?>"><?php t(':HOMEPAGE_SETUP')?></a></li>
                                    <li><a href="<?php echo url(array('controller' => 'site', 'view' => 'help', 'titleKey' => 'globalSetup')) ?>"><?php t(':GLOBAL_SETUP')?></a></li>
                                    <li><a href="<?php echo url(array('controller' => 'site', 'view' => 'help', 'titleKey' => 'userManagement')) ?>"><?php t(':USER_MANAGEMENT')?></a></li>
                                    <li><a href="<?php echo url(array('controller' => 'site', 'view' => 'help', 'titleKey' => 'donationManagement')) ?>"><?php t(':DONATION_MANAGEMENT')?></a></li>
                                    <li><a href="<?php echo url(array('controller' => 'site', 'view' => 'help', 'titleKey' => 'articleManagement')) ?>"><?php t(':ARTICLE_MANAGEMENT')?></a></li>
                                    <li><a href="<?php echo url(array('controller' => 'site', 'view' => 'help', 'titleKey' => 'mediaManagement')) ?>"><?php t(':MEDIA_MANAGEMENT')?></a></li>
                                </ul>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
				<!-- END TOPBAR -->
				
                <div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav">
						<span class="sr-only">Toggle Navigation</span>
						<i class="fa fa-bars"></i>
					</button>
				</div>

				<!-- MAIN NAVIGATION -->
                <div id="main-nav" class="navbar-collapse collapse" style="min-height:30px;">
					<?php if ($obj['user']['role'] == 'Administrator') { ?>
                    <ul class="nav navbar-nav navbar-right draggableMenuItem" data-moveHandler="<?php echo url(array('controller' => 'builder', 'action' => 'moveEntry')) ?>">
                    <?php } else { ?>
                    <ul class="nav navbar-nav navbar-right">
                    <?php } ?>
                        <?php foreach($obj['menu'] as $menu) { ?>
                            <?php renderPartial('subMenu', $menu); ?>
                        <?php } ?>
                    </ul>
                </div>