<script language="javascript" src="js/md5.js"></script>
<script language="javascript">
<!--
  function doChallengeResponse(getTimesUrl) {
    $.ajax({
      type: 'GET',
      url: getTimesUrl,
      data: 'email=' + $("#email").val(),
      datatype: 'json',
      success: function(result) {
                str1 = $("#email").val()+"*"+$("#passwordnothashed").val();
                for(var i=0; i<result.nb; i++)
                {
                    str1 = MD5(str1);
                }
                $("#password").val(str1);
                $("#passwordnothashed").val("");
                $("#loginForm").submit();
           }
      });
  }
// -->
</script>
<style>
        body {
  #padding-top: 40px;
  #padding-bottom: 40px;
  #background-color: #eee;
}

.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}
.form-signin .checkbox {
  font-weight: normal;
}
.form-signin .form-control {
  position: relative;
  height: auto;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>

      <form id="loginForm" class="form-signin" method="POST" action="?controller=user&action=login" enctype="x-www-form-urlencoded">
        <input type="hidden" name="callback" value="<?php disp($obj, 'callback') ?>">
        <h2 class="form-signin-heading"><?php t(':PLEASE_SIGN_IN')?></h2>
        <label for="inputEmail" class="sr-only"><?php t(':EMAIL_ADDRESS')?></label>
        <input id="email" type="email" name="email" class="form-control" placeholder="<?php t(':EMAIL_ADDRESS')?>" required autofocus value="<?php disp($obj, 'email') ?>" />
        <label for="passwordnothashed" class="sr-only">Password</label>
        <input id="passwordnothashed" type="password" name="passwordnothashed" class="form-control" placeholder="Password" required />
        <input id="password" type="hidden" name="password" />
        <input class="btn btn-primary" onClick="doChallengeResponse('<?php echo $obj['GetTimesUrl'] ?>'); return false;" type="submit" name="submitbtn" value="<?php t(':SIGN_IN') ?>" />

        <div class="checkbox">
          <a href="?controller=user&view=register"><?php t(':CREATE_ACCOUNT') ?></a><br />
          <a href="?controller=user&view=lostPassword"><?php t(':LOST_PASSWORD') ?></a>
        </div>
      </form>
