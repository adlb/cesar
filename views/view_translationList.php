<script type="text/javascript">
    var cesar_q = cesar_q || [];
    cesar_q.push({
        id: 'articlesListCtrlDiv', 
        f: function(scope) {
            scope.init(
                <?php echo json_encode($obj['articles'], JSON_NUMERIC_CHECK);?>,
                <?php echo json_encode($obj['languages']);?>,
                '<?php echo url(array('controller' => 'translationManager', 'view' => 'editArticleTrad', 'callback' => 
                        url(array('controller' => 'translationManager', 'view' => 'translationList'))
                        ));?>',
                '<?php echo url(array('controller' => 'builder', 'view' => 'editArticle', 'callback' => 
                        url(array('controller' => 'translationManager', 'view' => 'translationList'))
                        ));?>',
                '<?php echo url(array('controller' => 'builder', 'action' => 'deleteArticle'));?>'
            );}
    });
</script>
    <div class="page-header">
        <div class="container">
            <h1 class="page-title pull-left"> <?php t(':ARTICLE_MANAGEMENT') ?> </h1>
        </div>
    </div>
    <div id="articlesListCtrlDiv" class="page-container" ng-controller="articlesListCtrl" ng-cloak">
        <div class="container">
            <table class="table table-hover table-bordered table-condensed table-striped">
                <caption>
                    <?php t(':ARTICLES_LIST') ?>
                    <ul class="list-inline pull-right">
                        <li>
                            <input type="text" placeholder="<?php t(':SEARCH')?>" ng-model="search" />
                        </li>
                        <li>
                            <a class="btn-popover"
                                href="<?php echo url(array('controller' => 'donation', 'view' => 'create')) ?>"
                                data-container="body" data-toggle="popover" data-placement="top"
                                data-content="Create a new donation."
                                >
                                <span class="glyphicon glyphicon-plus"></span>
                            </a>
                        </li>
                    </ul>
                </caption>
                <thead>
                    <tr>
                      <th ng-click="predicate=='id' ? reverse = !reverse : predicate='id';saveLocal();"><?php t(':ARTICLE_ID') ?></th>
                      <th ng-click="predicate=='status' ? reverse = !reverse : predicate='status';saveLocal();"><?php t(':STATUS') ?></th>
                      <th ng-click="predicate=='titleTrad' ? reverse = !reverse : predicate='titleTrad';saveLocal();"><?php t(':TITLE') ?></th>
                      <th ng-click="predicate=='translatedType' ? reverse = !reverse : predicate='translatedType';saveLocal();"><?php t(':ARTICLE_TYPE') ?></th>
                      <th ng-repeat="language in languages"
                          ng-click="$parent.predicate=='StatusPerLanguage.' + language.name ? $parent.reverse = !$parent.reverse : $parent.predicate='StatusPerLanguage.' + language.name; saveLocal();"
                      >{{ language.name }} {{ language.active ? '(<?php t(':ACTIVE') ?>)' : '' }}</th>
                      <th class="text-center"><?php t(':ACTIONS') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="article in articles | filter:search | orderBy:predicate:reverse">
                        <th>{{ article.id }}</th>
                        <th>{{ article.status }}</th>
                        <td><a ng-href="{{ prefixUrlArticle }}&id={{ article.id }}" ng-show="article.link">
                            <i class="fa fa-home" ng-show="{{ article.home }}"></i>
                            <i class="fa fa-envelope" ng-show="{{ article.mail }}"></i>
                            {{ article.titleTrad }}</a>
                            <span ng-hide="article.link">{{ article.titleTrad }}</span></td>
                        <td>{{ article.translatedType }}</span></td>
                        <td class="clickableCell" ng-repeat="language in languages" href="{{ prefixUrl }}&titleKey={{ article.titleKey }}&textKey={{ article.textKey }}&lg={{ language.name }}">
                            {{ article.StatusPerLanguage[language.name] || '<?php t(':ERROR') ?>' }}
                        </td>
                        <td class="text-center">
                            <ul class="list-inline">
                                <li>
                                    <a ng-href="{{ prefixUrlArticle }}&id={{ article.id }}" ng-show="article.link">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <a 
                                        href="#" ng-show="article.id != 0"
                                        ng-real-click="deleteArticle(article.id);" 
                                        ng-confirm-click="<?php t(':ARE_YOU_SURE') ?>">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr />
        </div>
    </div>