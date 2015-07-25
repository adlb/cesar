<script language="javascript" src="js/md5.js"></script>
<script language="javascript">
<!--
    function emailUpdated(getTimesUrl) {
        var email = document.getElementById("email").value.toLowerCase();
        $.ajax({
            type: "GET",
            url: getTimesUrl,
            data: "email=" + email,
            datatype: "json",
            success: function(result) {
                document.getElementById("nbTimes").value = result.nb;
            
                if (result.nb == 1000) {
                    document.getElementById("Register").style.display="";
                    document.getElementById("RegisterLabel").style.display="";
                    document.getElementById("Login").style.display="none";
                } else {
                    document.getElementById("Register").style.display="none";
                    document.getElementById("RegisterLabel").style.display="none";
                    document.getElementById("Login").style.display="";
                }
            }
        });
    }
    
    function doChallengeResponse(getTimesUrl) {
        var email = document.getElementById("email").value.toLowerCase();
        $.ajax({
            type: "GET",
            url: getTimesUrl,
            data: "email=" + email,
            datatype: "json",
            success: function(result) {
                str1 = email+"*"+document.getElementById("passwordnothashed").value;
                
                if (document.getElementById("passwordnothashed2").type=="text") {
                    if (document.getElementById("passwordnothashed2").value != document.getElementById("passwordnothashed").value)
                        return;
                }
                
                for(var i=0; i<result.nb; i++)
                {
                    str1 = MD5(str1);
                }
                
                $("#password").val(str1);
                $("#passwordnothashed").val("");
                $("#passwordnothashed2").val("");
                
                $("#loginForm").submit();
            }
        });
    }
// -->
</script>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <form id="loginForm" class="form-signin" method="POST" action="?controller=user&action=login" enctype="x-www-form-urlencoded" style="margin:50px;0px;">
                    
                    <input type="hidden" name="callback" value="<?php disp($obj, 'callback') ?>" />
                    <input type="hidden" id="nbTimes" />
                    <h2 class="form-signin-heading"><?php t(':PLEASE_SIGN_IN')?></h2>
                    <input id="email" onchange="emailUpdated('<?php echo $obj['GetTimesUrl'] ?>')" type="email" name="email" class="form-control" placeholder="<?php t(':PLACEHOLDER_EMAIL')?>" required autofocus value="<?php disp($obj, 'email') ?>" />
                    <label id="RegisterLabel" style="display: none;"><small><?php t(':EMAIL_UNKOWN_CREATE_AN_ACCOUNT') ?></small></label>
                    <input type="password" id="passwordnothashed" type="password2" name="passwordnothashed" class="form-control" placeholder="<?php t(':PLACEHOLDER_PASSWORD')?>" required />
                    <input id="password" type="hidden" name="password" />
                    <div id="Register" style="display: none;">
                        <input type="password" id="passwordnothashed2" type="password2" name="passwordnothashed2" class="form-control" placeholder="<?php t(':PLACEHOLDER_PASSWORD2')?>" required />
                        <input class="btn btn-primary pull-right" onClick="doChallengeResponse('<?php echo $obj['GetTimesUrl'] ?>'); return false;" type="submit" name="submitbtn" value="<?php t(':REGISTER') ?>" />
                    </div>
                    <div id="Login" class="text-right">
                        <input class="btn btn-primary text-right" onClick="doChallengeResponse('<?php echo $obj['GetTimesUrl'] ?>'); return false;" type="submit" name="submitbtn" value="<?php t(':LOGIN') ?>" />
                        <br/>
                        <small><a href="?controller=user&view=lostPassword"><?php t(':LOST_PASSWORD') ?></a></small>   
                    </div>
                </form>
            </div>
        </div>
    </div>
    