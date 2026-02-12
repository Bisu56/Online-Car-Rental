<?php
session_start();
require_once("includes/config.php"); // Update path as necessary

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Server-side validation
    if (empty($email) || empty($password)) {
        echo "<script>alert('Please fill in all fields.');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
    } else {
        $sql = "SELECT EmailId, Password, FullName FROM tblusers WHERE EmailId = :email";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() == 1) {
            $user = $query->fetch(PDO::FETCH_OBJ);
            
            if (password_verify($password, $user->Password)) {
                $_SESSION['login'] = htmlspecialchars($user->EmailId);
                $_SESSION['fname'] = htmlspecialchars($user->FullName);

                echo "<script>location.reload();</script>";
            } else {
                echo "<script>alert('Incorrect password.');</script>";
            }
        } else {
            echo "<script>alert('No user found with this email.');</script>";
        }
    }
}
?>


<div class="modal fade" id="loginform">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h3 class="modal-title">Login</h3>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <form method="post" novalidate>
          <div class="form-group">
            <input type="email" class="form-control" name="email" placeholder="Email address*" required>
          </div>
          <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password*" required>
          </div>
          <div class="form-group checkbox">
            <label><input type="checkbox" id="remember"> Remember me</label>
          </div>
          <div class="form-group">
            <input type="submit" name="login" value="Login" class="btn btn-primary btn-block">
          </div>
        </form>
      </div>

      <div class="modal-footer text-center">
        <p>Don't have an account? <a href="#signupform" data-toggle="modal" data-dismiss="modal">Signup Here</a></p>
        <p><a href="#forgotpassword" data-toggle="modal" data-dismiss="modal">Forgot Password?</a></p>
      </div>

    </div>
  </div>
</div>
