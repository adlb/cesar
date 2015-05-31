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

      <form class="form-signin" method="POST" action="?controller=user&action=login" enctype="x-www-form-urlencoded">
        <input type="hidden" name="callback" value="<?php disp($obj, 'callback') ?>">
        <h2 class="form-signin-heading"><?php t(':PLEASE_SIGN_IN')?></h2>
        <label for="inputEmail" class="sr-only"><?php t(':EMAIL_ADDRESS')?></label>
        <input type="email" name="email" class="form-control" placeholder="<?php t(':EMAIL_ADDRESS')?>" required autofocus value="<?php disp($obj, 'email') ?>">
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php t(':SIGN_IN') ?></button>
        <div class="checkbox">
          <a href="?controller=user&view=register"><?php t(':CREATE_ACCOUNT') ?></a><br />
          <a href="?controller=user&view=lostPassword"><?php t(':LOST_PASSWORD') ?></a>
        </div>
      </form>
