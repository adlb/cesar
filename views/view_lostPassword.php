<form class="form-horizontal" method="POST" action="<?php echo url(array('controller' => 'user', 'action' => 'retreivePassword')) ?>">
    <fieldset>
        <!-- Form Name -->
        <legend>Retreive your password</legend>

        <!-- Text input-->
        <div class="form-group">
            <label class="col-md-4 control-label" for="email">email</label>  
            <div class="col-md-6">
                <input id="email" name="email" placeholder="email" class="form-control input-md" required="" type="text">
                <span class="help-block">Enter email used to register</span>  
            </div>
        </div>

        <!-- Button (Double) -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="sendId"></label>
            <div class="col-md-8">
                <button id="sendId" name="sendId" class="btn btn-primary">Send</button>
                <button id="cancelId" name="cancelId" class="btn btn-default">Cancel</button>
            </div>
        </div>
    </fieldset>
</form>