    <br/>
    <br/>
    <div class="page-container" id="usersCtrlDiv" ng-controller="usersListCtrl">
        <div class="container">
            <form class="form-horizontal" method="POST" action="<?php echo url(array('controller' => 'user', 'action' => 'retreivePassword')) ?>">
                <fieldset>
                    <!-- Form Name -->
                    <legend><?php t(':RESET_YOUR_PASSWORD')?></legend>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="email"><?php t(':EMAIL') ?></label>  
                        <div class="col-md-6">
                            <input id="email" name="email" placeholder="<?php t(':PLACEHOLDER_EMAIL')?>" class="form-control input-md" required="" type="text">
                            <span class="help-block"><?php t(':ENTER_EMAIL_USED_TO_REGISTER') ?></span>  
                        </div>
                    </div>

                    <!-- Button (Double) -->
                    <div class="form-group">
                        <div class="col-md-10 text-right">
                            <button id="sendId" name="sendId" class="btn btn-primary"><?php t(':SEND') ?></button>
                            <a href="javascript:history.back()" class="btn btn-default"><?php t(':CANCEL') ?></a>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>