<script type="text/javascript">
    var cesar_q = cesar_q || [];
    cesar_q.push({
        id: 'messagesCtrlDiv', 
        f: function(scope) {
            scope.push(
                <?php echo json_encode($obj, JSON_NUMERIC_CHECK);?>
            );}
    });
</script>

<div class="page-container" id="mediaCtrlDiv" ng-controller="mediaCtrl" ng-cloak>
    <div class="container">    
        <div class="row">
            <div class="col-lg-12" id="messagesCtrlDiv" ng-controller="messagesCtrl" deferred-cloak>
                <div ng-class="'alert alert-' + message.level" role="alert" ng-repeat="message in messages track by message.id">
                    <button type="button" class="close" aria-label="Close" ng-click="remove(message)"><span aria-hidden="true">&times;</span></button>
                    <strong>{{ message.strongText }}</strong> {{ message.text }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>