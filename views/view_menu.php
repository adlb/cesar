<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <a class="navbar-brand" href="?controller=site">Brand</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <?php if ($obj['user']['role'] == 'Administrator') { ?>
      <ul class="nav navbar-nav draggableMenuItem" data-moveHandler="<?php echo url(array('controller' => 'builder', 'action' => 'moveEntry')) ?>">
      <?php } else { ?>
      <ul class="nav navbar-nav">
      <?php } ?>
        <?php foreach($obj['menu'] as $menu) { ?>
                <?php displayPartial('site', 'subMenu', $menu); ?>
        <?php } ?>
        <?php if ($obj['user']['role'] == 'Administrator') { ?> 
            <li><a href="<?php echo url(array('controller' => 'builder', 'view' => 'editArticle')) ?>">&nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;</a></li>
        <?php } ?>
      </ul>
	  <form class="navbar-form navbar-right" role="search">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
      <ul class="nav navbar-nav navbar-right">
        <?php if ($obj['user']) { ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php disp($obj['user'], 'email') ?> <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#">Mon Profil</a></li>
            <li><a href="?controller=user&action=logout">Logout</a></li>
          </ul>
        </li>
        <?php } else { ?>
        <li><a href="?controller=user&view=login">Login</a></li>
        <?php } ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><?php t(':LANG_'.strtoupper($obj['language'])); ?><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
			<?php foreach($obj['languages'] as $lg) { ?>
				<li><a href="../<?php echo $lg ;?>/"><?php t(':LANG_'.strtoupper($lg)); ?></a></li>
			<?php } ?>
    	  </ul>
        </li>
		<?php if ($obj['user']['role'] == 'Administrator') { ?> 
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Administration<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="<?php echo url(array('controller' => 'builder', 'view' => 'config')) ?>"><?php t(':GLOBAL_SETUP')?></a></li>
            <li><a href="<?php echo url(array('controller' => 'user', 'view' => 'userList')) ?>"><?php t(':USER_MANAGEMENT')?></a></li>
            <li><a href="<?php echo url(array('controller' => 'translationManager', 'view' => 'translationList')) ?>"><?php t(':TRANSLATION_MANAGEMENT')?></a></li>
            <li><a href="#"><?php t(':MAILING')?></a></li>
            <li><a href="<?php echo url(array('controller' => 'medias', 'view' => 'medias')) ?>"><?php t(':MEDIA_MANAGEMENT')?></a></li>
            <li><a href="#"><?php t(':EVENTS')?></a></li>
          </ul>
        </li>
		<?php } ?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<script>
jQuery(function($) {
    var panelList = $('.draggableMenuItem');

    panelList.sortable({
        // Only make the .panel-heading child elements support dragging.
        // Omit this to make then entire <li>...</li> draggable.
        //handle: '.panel-heading', 
        update: function() {
            var url = $(this).data('movehandler');
            var data = $(this).sortable('serialize');

            // POST to server using $.post or $.ajax
            $.ajax({
                data: data,
                type: 'POST',
                url: url
            });
        }
    });
});
</script>