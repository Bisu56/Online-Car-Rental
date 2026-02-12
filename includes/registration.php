<?php
session_start();
require_once("includes/config.php"); // Adjust path as needed

if (isset($_POST['signup'])) {
    // Clean and validate inputs
    $fname = trim($_POST['fullname']);
    $email = trim($_POST['emailid']);
    $mobile = trim($_POST['mobileno']);
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    // Server-side validation
    if (
        empty($fname) || !preg_match("/^[a-zA-Z ]+$/", $fname) ||
        empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        empty($mobile) || !preg_match("/^\d{10}$/", $mobile) ||
        empty($password) || strlen($password) < 6 ||
        $password !== $confirmpassword
    ) {
        echo "<script>alert('Invalid input. Please check all fields carefully.');</script>";
    } else {
        // Check if email already exists
        $checkEmail = $dbh->prepare("SELECT id FROM tblusers WHERE EmailId = :email");
        $checkEmail->bindParam(':email', $email, PDO::PARAM_STR);
        $checkEmail->execute();

        if ($checkEmail->rowCount() > 0) {
            echo "<script>alert('Email already registered. Try another.');</script>";
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO tblusers (FullName, EmailId, ContactNo, Password) 
                    VALUES (:fname, :email, :mobile, :password)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':fname', $fname, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
            $query->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $query->execute();

            $lastInsertId = $dbh->lastInsertId();
            if ($lastInsertId) {
                echo "<script>alert('Registration successful. Now you can login');</script>";
            } else {
                echo "<script>alert('Something went wrong. Please try again');</script>";
            }
        }
    }
}
?>



<script>
function checkAvailability() {
  $("#loaderIcon").show();
  jQuery.ajax({
    url: "check_availability.php",
    data: 'emailid=' + $("#emailid").val(),
    type: "POST",
    success: function (data) {
      $("#user-availability-status").html(data);
      $("#loaderIcon").hide();
    },
    error: function () {
      $("#user-availability-status").html("<span style='color:red;'>Error checking email.</span>");
      $("#loaderIcon").hide();
    }
  });
}

function valid() {
  const pw = document.signup.password.value;
  const cpw = document.signup.confirmpassword.value;
  if (pw !== cpw) {
    alert("Password and Confirm Password do not match!");
    return false;
  }
  return true;
}
</script>

<!-- SIGNUP MODAL -->
<div class="modal fade" id="signupform">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h3 class="modal-title">Sign Up</h3>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <form method="post" name="signup" onSubmit="return valid();">
          <div class="form-group">
            <input type="text" class="form-control" name="fullname" placeholder="Full Name" pattern="[A-Za-z ]+" title="Only letters and spaces allowed" required>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name="mobileno" placeholder="Mobile Number" pattern="\d{10}" title="Enter 10-digit mobile number" required>
          </div>
          <div class="form-group">
            <input type="email" class="form-control" name="emailid" id="emailid" onBlur="checkAvailability()" placeholder="Email Address" required>
            <span id="user-availability-status" style="font-size:12px;"></span>
          </div>
          <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password (min 6 characters)" minlength="6" required>
          </div>
          <div class="form-group">
            <input type="password" class="form-control" name="confirmpassword" placeholder="Confirm Password" required>
          </div>
          <div class="form-group checkbox">
            <input type="checkbox" id="terms_agree" required>
            <label for="terms_agree">I Agree with <a href="#">Terms and Conditions</a></label>
          </div>
          <div class="form-group">
            <input type="submit" value="Sign Up" name="signup" class="btn btn-primary btn-block">
          </div>
        </form>
      </div>

      <div class="modal-footer text-center">
        <p>Already got an account? <a href="#loginform" data-toggle="modal" data-dismiss="modal">Login Here</a></p>
      </div>

    </div>
  </div>
</div>
