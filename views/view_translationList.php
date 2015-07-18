    <div class="page-header">
        <div class="container">
            <h1 class="page-title pull-left"> <?php t(':ARTICLE_MANAGEMENT') ?> </h1>
        </div>
    </div>
    <div class="page-container" ng-init="
        articles=<?php echo htmlspecialchars(json_encode($obj['articles'], JSON_NUMERIC_CHECK));?>;
        languages=<?php echo htmlspecialchars(json_encode($obj['languages']));?>;
        prefixUrl='<?php echo htmlspecialchars(url(array('controller' => 'translationManager', 'view' => 'editArticleTrad', 'callback' => 
            url(array('controller' => 'translationManager', 'view' => 'translationList'))
        )));?>';
        prefixUrlArticle='<?php echo htmlspecialchars(url(array('controller' => 'builder', 'view' => 'editArticle', 'callback' => 
            url(array('controller' => 'translationManager', 'view' => 'translationList'))
        )));?>';
        predicate='id';
    ">
        <div class="container">
            <table class="table table-hover table-bordered table-condensed table-striped">
                <caption>
                    <?php t(':ARTICLES_LIST') ?>
                    <ul class="list-inline pull-right">
                        <li>
                            <input type="text" placeholder="<?php t(':SEARCH')?>" ng-model="search" />
                        </li>
                    </ul>
                </caption>
                <thead>
                    <tr>
                      <th ng-click="predicate=='id' ? reverse = !reverse : predicate='id';"><?php t(':ARTICLE_ID') ?></th>
                      <th ng-click="predicate=='status' ? reverse = !reverse : predicate='status';"><?php t(':STATUS') ?></th>
                      <th ng-click="predicate=='titleTrad' ? reverse = !reverse : predicate='titleTrad';"><?php t(':TITLE') ?></th>
                      <th ng-click="predicate=='translatedType' ? reverse = !reverse : predicate='translatedType';"><?php t(':ARTICLE_TYPE') ?></th>
                      <th ng-repeat="language in languages"
                          ng-click="$parent.predicate=='StatusPerLanguage.' + language.name ? $parent.reverse = !$parent.reverse : $parent.predicate='StatusPerLanguage.' + language.name;"
                      >{{ language.name }} {{ language.active ? '(<?php t(':ACTIVE') ?>)' : '' }}</th>
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
                    </tr>
                </tbody>
            </table>
            <hr />
        </div>
    </div>