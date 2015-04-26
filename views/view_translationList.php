<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.11/angular.min.js"></script>
<div ng-init="
    articles=<?php echo htmlspecialchars(json_encode($obj['articles']));?>;
    languages=<?php echo htmlspecialchars(json_encode($obj['languages']));?>;
    prefixUrl='<?php echo htmlspecialchars(url(array('controller' => 'translationManager', 'view' => 'editArticleTrad')));?>';
    prefixUrlArticle='<?php echo htmlspecialchars(url(array('controller' => 'builder', 'view' => 'editArticle')));?>';
">
<table class="table table-hover table-bordered table-condensed table-striped">
  <caption>Optional table caption.</caption>
      <thead>
        <tr>
          <th>ArticleId</th>
          <th>ArticleTitle</th>
          <th ng-repeat="language in languages">{{ language.name }} {{ language.active ? '(active)' : '' }}</th>
        </tr>
      </thead>
      <tbody>
        <tr ng-repeat="article in articles">
          <th class="clickableCell" scope="row" href="{{ prefixUrl }}&id={{ article.id }}">{{ article.id }}</th>
          <td><a ng-href="{{ prefixUrlArticle }}&id={{ article.id }}" ng-show="article.link">{{ article.titleTrad }}</a>
              <span ng-hide="article.link">{{ article.titleTrad }}</span></td>
          <td class="clickableCell" ng-repeat="language in languages" href="{{ prefixUrl }}&titleKey={{ article.titleKey }}&textKey={{ article.textKey }}&lg={{ language.name }}">
            {{ article.StatusPerLanguage[language.name] || 'Error' }}
          </td>
        </tr>
      </tbody>
</table>

<script>
</script>