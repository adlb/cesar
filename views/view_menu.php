<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <a class="navbar-brand" href="?controller=site"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <?php if ($obj['user']['role'] == 'Administrator') { ?>
      <ul class="nav navbar-nav draggableMenuItem" data-moveHandler="<?php echo url(array('controller' => 'builder', 'action' => 'moveEntry')) ?>">
      <?php } else { ?>
      <ul class="nav navbar-nav">
      <?php } ?>
        <?php foreach($obj['menu'] as $menu) { ?>
                <?php renderPartial('subMenu', $menu); ?>
        <?php } ?>
        <li><a href="?controller=donation&view=donate"><?php t(':DONATE') ?></a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <?php if ($obj['user']) { ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php disp($obj['user'], 'email') ?> <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="<?php echo url(array('controller' => 'user', 'view' => 'editUser', 'id' => $obj['user']['id'])) ?>">Mon Profil</a></li>
            <li><a href="<?php echo url(array('controller' => 'user', 'action' => 'logout')) ?>">Logout</a></li>
          </ul>
        </li>
        <?php } else { ?>
        <li><a href="?controller=user&view=login">Login</a></li>
        <?php } ?>

        <?php if (count($obj['languages'])>1) { ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><?php t(':LANG_'.strtoupper($obj['language'])); ?><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php foreach($obj['languages'] as $lg) { ?>
                <li><a  class="<?php echo $lg['active'] ? "" : "cx_adminonly" ?>"
                        href="../<?php echo $lg['name'] ;?>/">
                    <?php t(':LANG_'.strtoupper($lg['name'])); ?>
                    </a>
                </li>
            <?php } ?>
          </ul>
        </li>
        <?php } ?>

        <?php if ($obj['user']['role'] == 'Administrator') { ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Administration<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="<?php echo url(array('controller' => 'builder', 'view' => 'editArticle')) ?>"><?php t(':ADD_ARTICLE')?></a></li>
            <li><a href="<?php echo url(array('controller' => 'builder', 'view' => 'config')) ?>"><?php t(':GLOBAL_SETUP')?></a></li>
            <li><a href="<?php echo url(array('controller' => 'user', 'view' => 'userList')) ?>"><?php t(':USER_MANAGEMENT')?></a></li>
            <li><a href="<?php echo url(array('controller' => 'translationManager', 'view' => 'translationList')) ?>"><?php t(':ARTICLE_MANAGEMENT')?></a></li>
            <li><a href="<?php echo url(array('controller' => 'medias', 'view' => 'medias')) ?>"><?php t(':MEDIA_MANAGEMENT')?></a></li>
          </ul>
        </li>
        <?php } ?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>