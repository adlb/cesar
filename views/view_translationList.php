    <div class="page-header">
        <div class="container">
            <h1 class="page-title pull-left"> <?php t(':MODIFY_ARTICLES') ?> </h1>
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
                    <?php t(':LIST_ARTICLES') ?>
                    <ul class="list-inline pull-right">
                        <li>
                            <input type="text" placeholder="<?php t(':SEARCH')?>" ng-model="search" />
                        </li>
                        <li>
                            <a class="pull-right" href="<?php echo url(array('controller' => 'builder', 'view' => 'editArticle')) ?>"><span class="glyphicon glyphicon-plus"></span></a>
                        </li>
                    </ul>
                </caption>
                <thead>
                    <tr>
                      <th ng-click="predicate=='id' ? reverse = !reverse : predicate='id';">ArticleId</th>
                      <th ng-click="predicate=='status' ? reverse = !reverse : predicate='status';">Status</th>
                      <th ng-click="predicate=='titleTrad' ? reverse = !reverse : predicate='titleTrad';">ArticleTitle</th>
                      <th ng-click="predicate=='type' ? reverse = !reverse : predicate='type';">ArticleType</th>
                      <th ng-repeat="language in languages"
                          ng-click="$parent.predicate=='StatusPerLanguage.' + language.name ? $parent.reverse = !$parent.reverse : $parent.predicate='StatusPerLanguage.' + language.name;"
                      >{{ language.name }} {{ language.active ? '(active)' : '' }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="article in articles | filter:search | orderBy:predicate:reverse">
                      <th class="clickableCell" scope="row" href="{{ prefixUrl }}&id={{ article.id }}">{{ article.id }}</th>
                      <th class="clickableCell" scope="row" href="{{ prefixUrl }}&id={{ article.id }}">{{ article.status }}</th>
                      <td><a ng-href="{{ prefixUrlArticle }}&id={{ article.id }}" ng-show="article.link">{{ article.titleTrad }}</a>
                          <span ng-hide="article.link">{{ article.titleTrad }}</span></td>
                      <td>{{ article.type }}</span></td>
                      <td class="clickableCell" ng-repeat="language in languages" href="{{ prefixUrl }}&titleKey={{ article.titleKey }}&textKey={{ article.textKey }}&lg={{ language.name }}">
                        {{ article.StatusPerLanguage[language.name] || 'Error' }}
                      </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>