<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$email = $pass = "";
$email_err = $pass_err = "";
$res_err = "";
$type_of_alert = "";
$mode = "";
// Processing form data when form is submitted, even checks if data available in database
if ((isset($_POST["email"])) && (isset($_POST["pass"])) && !empty($_POST["email"]) && !empty($_POST["pass"])) {

    // Validate email
    $input_email = trim($_POST["email"]);
    if (empty($input_email)) {
        $email_err = "Please enter a email.";
    } elseif (!filter_var($input_email, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/")))) {
        $email_err = "Please enter a valid email.";
    } else {
        $email = $input_email;
    }

    // Validate pass 
    $input_pass = trim($_POST["pass"]);
    if (empty($input_pass)) {
        $name_err = "Please enter a pass.";
    } else {
        $pass = $input_pass;
    }

    // Check input errors before inserting in database
    if (empty($email_err) && empty($pass_err)) {

        $sql = "select * from login_data WHERE email=?";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $_POST['email']);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $email = $row["email"];
                    $pass = $row["pass"];

                    if (($email == $input_email) && ($pass == $input_pass)) {
                        $res_err =  "Logged In.";
                        $mode = $row["account_type"];

                        if ($mode == "recruiter") {
                            header("Location:all_users.php");
                            exit();
                        } else {
                            header("Location:user_info.php?email=" . $email);
                            exit();
                        }
                    } else {
                        $res_err =  "Email or Password is invalid";
                    }
                } else if ($result->num_rows == 0) {
                    $res_err =  "You havent registered! Or Email is invalid";
                }
                //header("location: index.php");
                // exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $mysqli->close();
}
// else 
//     echo "<script> alert('Enter Credentials')</script>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resumocha</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/shards.min.css?v=3.0.0">
    <link rel="stylesheet" href="css/shards-demo.min.css?v=3.0.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<style type="text/css">
    .wrapper {
        width: 500px;
        margin: 0 auto;
    }
</style>

<body style="display: flex; align-items: center; height: -webkit-fill-available;justify-content: center; background-color:#f7f7f7">
    <!-- background="img/bg.png"  -->
    <!-- <div class="welcome d-flex justify-content-center flex-column">
        <div class="inner-wrapper mt-auto mb-auto"> -->



    <div class="card col-lg-5 col-md-7 col-sm-12 ">
        <div class="card-body">
            <div class="wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="page-header">
                                <h2>Login</h2>
                            </div>
                            <h5>Please enter your credentials.</h5><br>
                            <form action="<?php echo basename($_SERVER['REQUEST_URI']); ?>" method="post">

                                <div class="form-group col-md-8 <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                                    <div class="input-group input-group-seamless">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-user"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control" name="email" id="form1-username" placeholder="Username">
                                    </div>
                                    <span class="help-block"><?php echo $email_err; ?></span>
                                </div>


                                <div class="form-group col-md-8 <?php echo (!empty($pass_err)) ? 'has-error' : ''; ?>">
                                    <!-- <label>Password</label> -->
                                    <div class="input-group input-group-seamless">
                                        <input type="password" class="form-control" id="form2-password" placeholder="Password" name="pass">
                                        <span class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fa fa-lock"></i>
                                            </span>
                                        </span>
                                    </div>
                                    <span class="help-block"><?php echo $pass_err; ?></span>
                                </div>

                                <!-- <a href="index.html" class="btn btn-success" >Register</a> -->
                                <div class="col-md-5">
                                    <input type="submit" class="btn btn-primary btn-lg btn-pill" value="Login">
                                    <!-- Trigger the modal with a button -->
                                    <button type="button" class="btn btn-primary btn-pill btn-lg btn-success" data-toggle="modal" data-target="#myModal">
                                        Register
                                    </button>
                                    <!-- <button type="button" class="btn btn-success btn-lg btn-pill" data-toggle="modal" data-target="#myModal">Register</button> -->

                                    <!-- Modal -->
                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog " role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 style="font-size:18px" class="modal-title" id="exampleModalLabel">Register As</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body" style="display: flex; justify-content :space-around; padding-left:100px;padding-right:100px;">
                                                    <button onclick="location.href='RecruiterSignup.php'" type="button" class="btn btn-primary btn-lg btn-pill" data-toggle="modal" data-target="#myModal" style="padding-left:30px;padding-right:30px;">Recruiter</button>
                                                    <button onclick="location.href='UserSignUp.php'" type="button" class="btn btn-secondary btn-lg btn-pill" data-toggle="modal" style="padding-left:40px;padding-right:40px;" data-target="#myModal"> User </button>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default btn-pill" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-8 <?php echo (!empty($res_err)) ? 'has-error' : ''; ?>">
                                    <br>
                                    <?php if ($res_err == "Logged In.") {
                                        echo '<div class= "alert alert-success">
                        Logged In.</div>';
                                    } else if (($res_err == "Email or Password is invalid")) {
                                        echo '<div class= "alert alert-danger">
                            Email or Password is invalid.</div>';
                                    } else if (($res_err == "You havent registered! Or Email is invalid")) {
                                        echo '<div class= "alert alert-danger">
                            You havent registered! Or Email is invalid.</div>';
                                    }
                                    ?>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>