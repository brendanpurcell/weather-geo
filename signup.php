<?php
require "header.php";
?>
<!--some java script for client-side vetting of information-->
<script>
</script>

  <main>
    <div class="wrapper-main">
      <section class="section-default">
        <div style="width: 500px; margin: auto">
          <br>
          <br>
          <br>
          <h1>Register New User</h1>
          <?php
          //print out error in login/signup
          if (isset($_GET['error'])) {
            //what specific error
            if ($_GET['error'] == "emptyfields") {
              echo '<p style="color: red" class="signuperror">Fill in all fields!</p>';
            }
            else if ($_GET['error'] == "invaliduidmail") {
              echo '<p style="color: red" class="signuperror">Invalid username and password</p>';
            }
            else if ($_GET['error'] == "invaliduid") {
              echo '<p style="color: red" class="signuperror">Invalid username!</p>';
            }
            else if ($_GET['error'] == "invalidmail") {
              echo '<p style="color: red" class="signuperror">Invalid e-mail!</p>';
            }
            else if ($_GET['error'] == "passwordcheck") {
              echo '<p style="color: red" class="signuperror">Password do not match!</p>';
            }
            else if ($_GET['error'] == "usertaken") {
              echo '<p style="color: red" class="signuperror">Username is already taken!</p>';
            }
          }
          else if (isset($_GET['signup']) && $_GET['signup'] == "success") {
            //successful login
            //refer to signup.inc.php. when successfully registered, it will redirect to index.php, with session on below
          }
          ?>
          <!--refer to boostrap for class css-->
          <form class="form-signup" action="includes/signup.inc.php" method="post">
            <div class="form-group">
            <input type="text" name="uid" placeholder="Username" class="form-control">
            </div>
            <div class="form-group">
            <input type="text" name="mail" placeholder="E-mail" class="form-control">
            </div>
            <div class="form-group">
            <input type="password" name="pwd" placeholder="Password" class="form-control">
            </div>
            <div class="form-group">
            <input type="password" name="pwd-repeat" placeholder="Password (again)" class="form-control">
            </div>
            <div class="form-group">
            <button type="submit" name="signup-submit" class="btn btn-success">Register</button>
            </div>
          </form>
        </div>
      </section>
    </div>
</main>

<?php
  require "footer.php"
?>
