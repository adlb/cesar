<script language="javascript" src="js/md5.js"></script>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <form id="loginForm" class="form-signin" method="POST" action="<?php echo url(array('controller' => 'user', 'action' => 'login'))?>" enctype="x-www-form-urlencoded" style="margin:50px;0px;">
                    <input type="hidden" name="callback" value="<?php disp($obj, 'callback') ?>" />
                    <input type="hidden" name="currentPage" value="login">
                    <input type="hidden" id="nbTimes" />
                    <h2 class="form-signin-heading"><?php t(':PLEASE_SIGN_IN')?></h2>
                    <input id="email" onchange="loginForm.EmailUpdated()" type="email" name="email" class="form-control" placeholder="<?php t(':PLACEHOLDER_EMAIL')?>" required autofocus value="<?php disp($obj, 'email') ?>" />
                    <label id="RegisterLabel" style="display: none;"><small><?php t(':EMAIL_UNKOWN_CREATE_AN_ACCOUNT') ?></small></label>
                    <input type="password" id="passwordnothashed" name="passwordnothashed" class="form-control" placeholder="<?php t(':PLACEHOLDER_PASSWORD')?>" required />
                    <input id="password" type="hidden" name="password" />
                    <input id="Type" type="hidden" name="type" />
                    <div id="Register" class="text-right" style="display: none;">
                        <input type="password" id="passwordnothashed2" type="password2" name="passwordnothashed2" class="form-control" placeholder="<?php t(':PLACEHOLDER_PASSWORD2')?>" />
                        <input class="btn btn-primary pull-right" onClick="loginForm.Submit(); return false;" type="submit" name="submitbtn1" value="<?php t(':REGISTER') ?>" />
                    </div>
                    <div id="Login" class="text-right">
                        <input class="btn btn-primary text-right" onClick="loginForm.Submit(); return false;" type="submit" name="submitbtn2" value="<?php t(':LOGIN') ?>" />
                        <br/>
                        <small><a href="?controller=user&view=lostPassword"><?php t(':LOST_PASSWORD') ?></a></small>   
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    
    <script language="javascript">
<!--
var loginForm = function(url, type) {

    var getTimesUrl = url;
    
    var init = function(url) {
        getTimesUrl = url;
    };
    
    var switchToRegister = function() {
        document.getElementById("Register").style.display="";
        document.getElementById("RegisterLabel").style.display="";
        document.getElementById("Login").style.display="none";
        document.getElementById("Type").value = "register";
    };
    
    var switchToLogin = function() {
        document.getElementById("Register").style.display="none";
        document.getElementById("RegisterLabel").style.display="none";
        document.getElementById("Login").style.display="";
        document.getElementById("Type").value = "login";
    };
    
    var getTimes = function(success, error) {
        email = document.getElementById("email").value;
        $.ajax({
            type: "GET",
            url: getTimesUrl,
            data: "email=" + email,
            datatype: "json",
            success: function(result) {
                success(result.nb);
            },
            error: error
        });
    }
    
    var updatePasswordForRegister = function(passwordnothashed1, passwordnothashed2, done) {
        if (passwordnothashed1 != passwordnothashed2) {
            $("#password").val('mismatch');
            done();
            return;
        }
        if (passwordnothashed1.length<6) {
            $("#password").val('tooshort');
            done();
            return;
        }
        getTimes(
            function(nb) {
                str = email.toLowerCase()+"*"+passwordnothashed1;
                for(var i=0; i<nb; i++)
                    str = MD5(str);
                $("#password").val(str);
                done();
            },
            function() {
                $("#password").val('');
                done();
            }
        );
    };
    
    var updatePassword = function(passwordnothashed, done) {
        if (passwordnothashed=='')
            done();
        getTimes(
            function(nb) {
                str = email.toLowerCase()+"*"+passwordnothashed1;
                for(var i=0; i<nb; i++)
                    str = MD5(str);
                $("#password").val(str);
                done();
            },
            function() {
                $("#password").val('');
                done();
            }
        );
    }
    
    var submitForm = function(ctype) {
        $("#passwordnothashed").val("");
        $("#passwordnothashed2").val("");
        $("#type").val(ctype);
        $("#loginForm").submit();
    };
    
    var submit = function() {
        ctype = document.getElementById("Type").value;
        passwordnothashed1 = document.getElementById("passwordnothashed").value;
        passwordnothashed2 = document.getElementById("passwordnothashed2").value;
        if (ctype == 'register')
            updatePasswordForRegister(passwordnothashed1, passwordnothashed2, function() {submitForm(ctype);});
        else
            updatePassword(passwordnothashed1, function() {submitForm(ctype);});
    };
    
    var emailUpdated = function() {
        getTimes(
            function(nb) {
                if (nb == 1000)
                    switchToRegister();
                else
                    switchToLogin();
            },
            function() {
                switchToRegister();
            }
        );
    }
    
    if (type == 'register') {
        switchToRegister();
    } else {
        switchToLogin();
    }
    
    return {
        SwitchToRegister: switchToRegister,
        Submit: submit,
        EmailUpdated: emailUpdated
    };
}('<?php echo $obj['GetTimesUrl'] ?>', '<?php echo $obj['type'] ?>');
// -->
</script>
