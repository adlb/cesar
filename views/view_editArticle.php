<script>
    var cesar_q = cesar_q || [];
    cesar_q.push({
        id: 'articleCtrl', 
        f: function(scope) {
            scope.init(
                    <?php echo json_encode($obj['form']['type']);?>,
                    <?php echo json_encode($obj['images']);?>,
                    <?php echo json_encode($obj['form']['imageId']);?>
                );
        }
    });
</script>
    <div class="page-header">
        <div class="container">
            <h1 class="page-title pull-left"> <?php t(':ADD_ARTICLE') ?> </h1>
        </div>
    </div>
    
    <div class="page-container">
        <div class="container" id="articleCtrl" ng-controller="articleCtrl"  ng-cloak>
            <form class="form-horizontal" method="POST" action="<?php echo url(array('controller' => 'builder', 'action' => 'saveArticle'))?>" accept-charset="UTF-8">
                <div class="row">
                    <div class="col-md-7">
                        <div class="row">
                            <input type="hidden" id="id" name="id" value="<?php echo $obj['form']['id'] ?>">
                            <input type="hidden" id="language" name="language" value="<?php echo $obj['language'] ?>" />
                            <input type="hidden" id="titleKey" name="titleKey" value="<?php echo $obj['form']['titleKey'] ?>" />
                            <input type="hidden" id="textKey" name="textKey" value="<?php echo $obj['form']['textKey'] ?>" />
                            <input type="hidden" id="callback" name="callback" value="<?php echo $obj['callback'] ?>" />

                            <!-- Select Basic -->
                            <div>
                              <label class="col-md-5 control-label" for="type"><?php t(':ARTICLE_TYPE') ?></label>
                              <div class="col-md-7">
                                <select id="type" name="type" class="form-control" ng-model="type">
                                  <option value="menu"    ><?php t(':ARTICLE_MENU') ?></option>
                                  <option value="article" ><?php t(':ARTICLE_ARTICLE') ?></option>
                                  <option value="news"    ><?php t(':ARTICLE_NEWS') ?></option>
                                </select>
                              </div>
                            </div>

                            <!-- Select Basic -->
                            <div class="" ng-show="type == 'article'">
                              <label class="col-md-5 control-label" for="menuFather"><?php t(':ARTICLE_PARENT_MENU') ?></label>
                              <div class="col-md-7">
                                <select id="menuFather" name="menuFather" class="form-control">
                                  <?php foreach($obj['menuFathers'] as $k=>$v) { ?>
                                  <option value="<?php echo $k ?>" <?php echo $obj['form']['father'] == $k ? 'SELECTED' : ''; ?>><?php t($v) ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>

                            <!-- Select Basic -->
                            <div class="" ng-show="type == 'news'">
                              <label class="col-md-5 control-label" for="newsFathers"><?php t(':ARTICLE_PARENT_NEWS') ?></label>
                              <div class="col-md-7">
                                <select id="newsFather" name="newsFather" class="form-control">
                                  <?php foreach($obj['newsFathers'] as $k=>$v) { ?>
                                  <option value="<?php echo $k ?>" <?php echo $obj['form']['father'] == $k ? 'SELECTED' : ''; ?>><?php t($v) ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>

                            <!-- Text input-->
                            <div class="" ng-show="type == 'menu' || type == 'article' || type == 'news'">
                              <label class="col-md-5 control-label" for="titleTrad"><?php t(':TITLE') ?></label>
                              <div class="col-md-7">
                              <input id="title" name="titleTrad" placeholder="<?php t(':PLACEHOLDER_TITLE') ?>" class="form-control input-md" type="text" value="<?php echo $obj['form']['titleTrad'] ?>">
                              </div>
                            </div>

                            <!-- Select Image -->
                            <div class="" ng-show="type == 'article' || type == 'news'">
                              <label class="col-md-5 control-label" for="imageId"><?php t(':ARTICLE_IMAGE') ?></label>
                              <div class="col-md-7">
                                <select id="imageId" name="imageId" class="form-control" 
                                    ng-options="item as (item.id + ' - ' + item.name) for item in images track by item.id" ng-model="image">
                                </select>
                                <span class="help-block"><?php t(':ARTICLE_PLEASE_LINK_IMAGE_480x300') ?></span>
                              </div>
                            </div>

                            <!-- Text input-->
                            <div class="" ng-show="type == 'article' || type == 'news'">
                              <label class="col-md-5 control-label" for="date"><?php t(':ARTICLE_DATE') ?></label>
                              <div class="col-md-7">
                                <div class='input-group date' id='datetimepicker'>
                                    <input id="date" name="date" placeholder="<?php t(':PLACEHOLDER_DATE')?>" type='text' class="form-control" value="<?php echo $obj['form']['date'] ?>" />
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                              </div>
                            </div>

                            <div class="" ng-show="type == 'menu' || type == 'article' || type == 'news'">
                              <label class="col-md-5 control-label" for="show"><?php t(':SHOW') ?></label>
                              <div class="col-md-7">
                                <div class="checkbox">
                                  <label class="checkbox-0" for="show">
                                    <input type="hidden" name="show" value="0">
                                    <input id="show" name="show" type="checkbox" value="1" <?php echo $obj['form']['show'] == 1 ? 'checked' : '' ?> />
                                  </label>
                                </div>
                              </div>
                            </div>
                            <div class="" ng-show="type == 'article'">
                              <label class="col-md-5 control-label" for="home"><?php t(':HOME') ?></label>
                              <div class="col-md-7">
                                <div class="checkbox">
                                  <label class="checkbox-0" for="home">
                                    <input type="hidden" name="home" value="0">
                                    <input id="home" name="home" type="checkbox" value="1" <?php echo $obj['form']['home'] == 1 ? 'checked' : '' ?> />
                                  </label>
                                </div>
                              </div>
                            </div>
                            <div class="" ng-show="type == 'article'">
                              <label class="col-md-5 control-label" for="needLogin"><?php t(':NEED_LOGIN') ?></label>
                              <div class="col-md-7">
                                <div class="checkbox">
                                  <label class="checkbox-0" for="needLogin">
                                    <input type="hidden" name="needLogin" value="0">
                                    <input id="needLogin" name="needLogin" type="checkbox" value="1" <?php echo $obj['form']['needLogin'] == 1 ? 'checked' : '' ?> />
                                  </label>
                                </div>
                              </div>
                            </div>
                            <div class="" ng-show="type == 'news'">
                              <label class="col-md-5 control-label" for="alert"><?php t(':ALERT') ?></label>
                              <div class="col-md-7">
                                <div class="checkbox">
                                  <label class="checkbox-0" for="alert">
                                    <input type="hidden" name="alert" value="0">
                                    <input id="alert" name="alert" type="checkbox" value="1" <?php echo $obj['form']['alert'] == 1 ? 'checked' : '' ?> />
                                  </label>
                                </div>
                              </div>
                            </div>
                            <div class="" ng-show="type == 'news'">
                              <label class="col-md-5 control-label" for="date"><?php t(':ARTICLE_DATEALERT') ?></label>
                              <div class="col-md-7">
                                <div class='input-group date' id='datetimepickerAlert'>
                                    <input id="datealert" name="datealert" placeholder="<?php t(':PLACEHOLDER_DATEALERT')?>" type='text' class="form-control" value="<?php echo $obj['form']['datealert'] ?>" />
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 text-right">
                        <img class="img-responsive" ng-src="{{image.file || 'img/placeholder.jpg'}}" />
                    </div>

                </div> <!-- row -->
                <!-- Textarea -->
                <div class="row" ng-show="type == 'article' || type == 'news'">
                    <div class="col-md-12">
                        <div class="btn-toolbar" role="toolbar">
                            <div class="btn-group pull-right">
                                <a type="button" class="btn btn-default" aria-label="Left Align"target="_blank" href="<?php echo url(array('controller' => 'builder', 'view' => 'help', 'titleKey' => 'articleManagement'))?>">
                                    <i class="fa fa-question" aria-hidden="true"></i>
                                </a>
                                <a id="actionShow" href="#" type="button" class="btn btn-default" aria-label="Center Align">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" id="textInput">
                        <textarea class="form-control" id="textTrad" name="textTrad" rows="10"><?php echo $obj['form']['textTrad'] ?></textarea>
                    </div>
                    <div class="col-md-9" id="textShow" style="display: none;">
                        <div class="col-md-6 pull-left" style="padding-left:0;padding-bottom:3px;" ng-show="image.file">
                            <img class="img-responsive" ng-src="{{ image.file }}" />
                        </div>
                        <div class="blog single full-thumbnail">
                            <article id="textHTML"><!-- placeholder for previsualisation !--></article>
                        </div>
                    </div>
                    
                </div>
                <!-- Button (Double) -->
                <div class="form-group">
                  <div class="col-md-12 text-right">
                    <a class="btn btn-default" href="<?php echo $obj['callback'] ?>"><?php t(':CANCEL') ?></a>
                    <button id="button2id" name="button2id" class="btn btn-primary" ng-show="type == 'menu' || type == 'article' || type == 'news'"><?php t(':SAVE') ?></button>
                  </div>
                </div>
            </form>
        </div>
    </div>
