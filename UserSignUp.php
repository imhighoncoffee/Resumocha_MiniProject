<?php
// Include config file
require_once "config.php";
// Define variables and initialize with empty values
$email = $pass = $name = $dob = $phone = $sex = "";
$email_err = $pass_err = $name_err = $dob_err = $phone_err = $sex_err = "";
$res_err = "";
$type_of_alert = "";
$mode = "";
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate email
    $input_email = trim($_POST["email"]);
    if (empty($input_email)) {
        $email_err = "Please enter a email.";
    } elseif (!filter_var($input_email, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" =>
    "/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/")))) {
        $email_err = "Please enter a valid email.";
    } else {
        $email = $input_email;
    }

    // Validate pass
    $input_pass = trim($_POST["pass"]);
    if (empty($input_pass)) {
        $pass_err = "Please enter an pass.";
    } elseif (!filter_var($input_pass, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/")))) {
        $pass_err = "Please enter a valid Password. Must be 8 characters long & should atleast contain 1 lowercase, 1 uppercase, 1 numeric, one special character.";
    } else {
        $pass = $input_pass;
    }
    // Validate name
    $input_name = trim($_POST["name"]);
    if (empty($input_name)) {
        $name_err = "Please enter a name.";
    } else {
        $name = $input_name;
    }
    // Validate dob
    $input_dob = trim($_POST["dob"]);
    if (empty($input_dob)) {
        $dob_err = "Please enter a date.";
    } else {
        $dob = $input_dob;
    }
    // Validate phone
    $input_phone = trim($_POST["phone"]);
    if (empty($input_phone)) {
        $phone_err = "Please enter a phone number.";
    } elseif (!filter_var($input_phone, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[0-9]{10}$/")))) {
        $phone_err = "Please enter a valid phone number.";
    } else {
        $phone = $input_phone;
    }

    // Check input errors before inserting in database
    if (empty($email_err) && empty($pass_err) && empty($name_err) && empty($dob_err) && empty($phone_err)) {
        $sql = "INSERT into login_data (email, pass, account_type) values(?,?,?)";
        $sql2 = "INSERT into users (email, name, phone, dob, sex) values(?,?,?,?,?)";
        $user = "user";
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $_POST['email'], $_POST['pass'], $user);

            if ($stmt2 = $mysqli->prepare($sql2)) {
                $stmt2->bind_param("sssss", $_POST['email'], $_POST['name'], $_POST['phone'], $_POST['dob'], $_POST['optradio']);
                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    if ($stmt2->execute()) {
                        header("location: init.php");
                        exit();
                    } else {
                        echo "Something went wrong. Please try again later.";
                    }
                }
            } else {
                echo "Something went wrong. Please try again later.";
            }
        } else {
            $error = $mysqli->errno . ' ' . $mysqli->error;
            echo $error; // 1054 Unknown column 'foo' in 'field list'
        }

        // Close statement
        // if($stmt->close()) 
        //    { $stmt2->close();}
    }

    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Resumocha</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <style type="text/css">
        .wrapper {
            width: 500px;
            margin: 0 auto;
        }
    </style>
    <!-- Bootstrap Date-Picker Plugin -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/shards.min.css?v=3.0.0">
    <link rel="stylesheet" href="css/shards-demo.min.css?v=3.0.0">
</head>

<body style="background-color:#f7f7f7; display: flex; align-items: center; height: -webkit-fill-available;justify-content: center;">
    <div class="card col-lg-6 col-md-6 col-sm-12">
        <div class="card-body">
            <div class="wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="page-header">
                                <h2>User Sign Up</h2>
                            </div>
                            <p>Please enter your Details.</p>
                            <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                                    <label>Email</label>
                                    <input type="text" name="email" class="form-control" value="">
                                    <span class="help-block"><?php echo $email_err; ?></span>
                                </div>
                                <div class="form-group <?php echo (!empty($pass_err)) ? 'has-error' : ''; ?>">
                                    <label>Password</label>
                                    <input type="password" name="pass" class="form-control" value="">
                                    <span class="help-block"><?php echo $pass_err; ?></span>
                                </div>
                                <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" value="">
                                    <span class="help-block"><?php echo $name_err; ?></span>
                                </div>
                                <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control" value="">
                                    <span class="help-block"><?php echo $phone_err; ?></span>
                                </div>
                                <div class="form-group <?php echo (!empty($dob_err)) ? 'has-error' : ''; ?>">
                                    <label>Date of Birth</label>
                                    <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                                        <input name="dob" type="text" class="form-control">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>

                                    </div>
                                    <span class="help-block"><?php echo $dob_err; ?></span>
                                </div>
                                <div class="form-group <?php echo (!empty($sex_err)) ? 'has-error' : ''; ?>">
                                    <label>Sex</label><br>
                                    <label class="radio-inline"><input type="radio" name="optradio" value="male" />Male</label>
                                    <label class="radio-inline"><input type="radio" name="optradio" value="female" />Female</label>
                                    <label class="radio-inline"><input type="radio" name="optradio" value="other" />Other</label>
                                </div>
                                <br />
                                <input type="submit" class="btn btn-primary btn-pill btn-lg" value="Sign up">
                                <a href="init.php" class="btn btn-default btn-pill btn-lg">Cancel</a>
                                <div class="form-group <?php echo (!empty($res_err)) ? 'has-error' : ''; ?>">
                                    <br>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>