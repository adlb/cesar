<div ng-repeat="message in messages track by $index" class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>{{message.level}}</strong> {{message.text}}
</div>