<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.11/angular.min.js"></script>
<div ng-app ng-init="
	texts=<?php echo htmlspecialchars(json_encode($obj['texts']));?>;
	languages=<?php echo htmlspecialchars(json_encode($obj['languages']));?>;
	prefixUrl='<?php echo htmlspecialchars(url(array('controller' => 'translationManager', 'view' => 'editText')));?>';
">
<table class="table table-hover table-bordered table-condensed table-striped">
  <caption>Optional table caption.</caption>
      <thead>
        <tr>
          <th>Key</th>
		  <th ng-repeat="language in languages">{{ language }}</th>
        </tr>
      </thead>
      <tbody>
        <tr ng-repeat="(key, textlg) in texts">
          <th class="clickableCell" scope="row" href="{{ prefixUrl }}&key={{ key }}">{{ key }}</th>
          <td class="clickableCell" ng-repeat="language in languages" href="{{ prefixUrl }}&key={{ key }}&lg={{ language }}">
			{{ textlg[language].textStatus || 'Error' }}
	      </td>
        </tr>
      </tbody>
</table>

<script>
jQuery(document).ready(function($) {
      $(".clickableCell").click(function() {
            window.document.location = $(this).attr("href");
      });
});
</script>