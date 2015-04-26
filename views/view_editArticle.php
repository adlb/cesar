
<form class="form-horizontal" method="POST" action="<?php echo url(array('controller' => 'builder', 'action' => 'saveArticle'))?>">
<fieldset ng-init="type='<?php echo $obj['form']['type']?>'" ng-cloak>

<input type="hidden" id="id" name="id" value="<?php echo $obj['form']['id'] ?>">
<input type="hidden" id="language" name="language" value="<?php echo $obj['language'] ?>" />
<input type="hidden" id="titleKey" name="titleKey" value="<?php echo $obj['form']['titleKey'] ?>" />
<input type="hidden" id="textKey" name="textKey" value="<?php echo $obj['form']['textKey'] ?>" />

<!-- Form Name -->
<legend>Create new element</legend>

<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="type">Type</label>
  <div class="col-md-6">
    <select id="type" name="type" class="form-control" ng-model="type">
      <option value="menu"    ><?php t(':TYPE_MENU') ?></option>
      <option value="article" ><?php t(':TYPE_ARTICLE') ?></option>
      <option value="news"    ><?php t(':TYPE_NEWS') ?></option>
    </select>
  </div>
</div>

<!-- Select Basic -->
<div class="form-group" ng-show="type == 'article'">
  <label class="col-md-4 control-label" for="menuFather">MenuParent</label>
  <div class="col-md-6">
    <select id="menuFather" name="menuFather" class="form-control">
      <?php foreach($obj['menuFathers'] as $k=>$v) { ?>
      <option value="<?php echo $k ?>" <?php echo $obj['form']['father'] == $k ? 'SELECTED' : ''; ?>><?php t($v) ?></option>
      <?php } ?>
    </select>
  </div>
</div>

<!-- Select Basic -->
<div class="form-group" ng-show="type == 'news'">
  <label class="col-md-4 control-label" for="newsFathers">NewsParent</label>
  <div class="col-md-6">
    <select id="newsFather" name="newsFather" class="form-control">
      <?php foreach($obj['newsFathers'] as $k=>$v) { ?>
      <option value="<?php echo $k ?>" <?php echo $obj['form']['father'] == $k ? 'SELECTED' : ''; ?>><?php t($v) ?></option>
      <?php } ?>
    </select>
  </div>
</div>

<!-- Text input-->
<div class="form-group" ng-show="type == 'menu' || type == 'article' || type == 'news'">
  <label class="col-md-4 control-label" for="titleTrad">Title</label>
  <div class="col-md-6">
  <input id="title" name="titleTrad" placeholder="placeholder" class="form-control input-md" type="text" value="<?php echo $obj['form']['titleTrad'] ?>">
  </div>
</div>

<!-- Text input-->
<div class="form-group" ng-show="type == 'article' || type == 'news'">
  <label class="col-md-4 control-label" for="date">Date</label>
  <div class="col-md-3">
    <div class='input-group date' id='datetimepicker'>
        <input id="date" name="date" placeholder="placeholder" type='text' class="form-control" />
        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
  </div>
</div>

<div class="form-group" ng-show="type == 'menu' || type == 'article' || type == 'news'">
  <label class="col-md-4 control-label" for="show"><?php t(':SHOW') ?></label>
  <div class="col-md-1">
    <div class="checkbox">
      <label class="checkbox-0" for="show">
        <input type="hidden" name="show" value="0">
        <input id="show" name="show" type="checkbox" value="1" <?php echo $obj['form']['show'] == 1 ? 'checked' : '' ?> />
      </label>
    </div>
  </div>
</div>
<div class="form-group" ng-show="type == 'article'">
  <label class="col-md-4 control-label" for="home"><?php t(':HOME') ?></label>
  <div class="col-md-1">
    <div class="checkbox">
      <label class="checkbox-0" for="home">
        <input type="hidden" name="home" value="0">
        <input id="home" name="home" type="checkbox" value="1" <?php echo $obj['form']['home'] == 1 ? 'checked' : '' ?> />
      </label>
    </div>
  </div>
</div>
<div class="form-group" ng-show="type == 'news'">
  <label class="col-md-4 control-label" for="alert"><?php t(':ALERT') ?></label>
  <div class="col-md-1">
    <div class="checkbox">
      <label class="checkbox-0" for="alert">
        <input type="hidden" name="alert" value="0">
        <input id="alert" name="alert" type="checkbox" value="1" <?php echo $obj['form']['alert'] == 1 ? 'checked' : '' ?> />
      </label>
    </div>
  </div>
</div>
<div class="form-group" ng-show="type == 'news'">
  <label class="col-md-4 control-label" for="date">DateAlert</label>
  <div class="col-md-3">
    <div class='input-group date' id='datetimepickerAlert'>
        <input id="datealert" name="datealert" placeholder="placeholder" type='text' class="form-control" />
        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
  </div>
</div>

<!-- Textarea -->
<div class="form-group" ng-show="type == 'article' || type == 'news'">
  <!--<label class="col-md-4 control-label" for="text">Text</label>-->
  <div class="col-md-12">
    <a id="viewHelp"  target="_blank" href="<?php echo url(array('controller' => 'builder', 'view' => 'help'))?>">
        <span class="glyphicon glyphicon-cog pull-right"></span>
    </a>
    <a id="actionShow" href="#"><span class="glyphicon glyphicon-inbox pull-right"></span></a>
  </div>

  <div class="col-md-12">
    <textarea class="form-control" id="textTrad" name="textTrad" rows="10"><?php echo $obj['form']['textTrad'] ?></textarea>
    <div id="textHTML" width="500px" class="cx_article" style="display: none;"><!-- placeholder for previsualisation !--></div>
  </div>
</div>
<!-- Button (Double) -->
<div class="form-group">
  <div class="col-md-2 pull-right">
    <a class="btn btn-default" href="<?php echo url(array('controller' => 'site', 'view' => '')) ?>">Cancel</a>
    <button id="button2id" name="button2id" class="btn btn-primary" ng-show="type == 'menu' || type == 'article' || type == 'news'">Validate</button>
  </div>
</div>
</fieldset>
</form>
<script>
$(function () {
	$('#datetimepicker').datetimepicker({
		locale: '<?php echo $obj['language'] ?>',
		format: 'DD/MM/YYYY',
		defaultDate: "<?php echo $obj['form']['date'] ?>"
	});
	$('#datetimepickerAlert').datetimepicker({
		locale: '<?php echo $obj['language'] ?>',
		format: 'DD/MM/YYYY',
		defaultDate: "<?php echo $obj['form']['datealert'] ?>"
	});
});
</script>
