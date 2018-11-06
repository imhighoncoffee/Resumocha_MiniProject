<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$email = $pass =$name = $dob = $phone = $sex = "";
$email_err = $pass_err = $name_err = $dob_err = $phone_err = $sex_err = "";
$res_err="";
$type_of_alert="";
$mode="";
// Processing form data when form is submitted
if(isset($_POST["optradio"])&&isset($_POST["email"])&&(isset($_POST["pass"]))&&(isset($_POST["name"]))&&(isset($_POST["dob"]))&&(isset($_POST["phone"])) && !empty($_POST["email"]) || !empty($_POST["pass"])|| !empty($_POST["name"])|| !empty($_POST["phone"])|| !empty($_POST["dob"])){

    
    // Validate email
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter a email.";
    } elseif(!filter_var($input_email, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/")))){
        $email_err = "Please enter a valid email.";
    } else{
        $email = $input_email;
    }
    
    // Validate pass
    $input_pass = trim($_POST["pass"]);
    if(empty($input_pass)){
        $pass_err = "Please enter an pass.";     
    } else{
        $pass = $input_pass;
    }

    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";     
    } else{
        $name = $input_name;
    }

    // Validate dob
    $input_dob = trim($_POST["dob"]);
    if(empty($input_dob)){
        $dob_err = "Please enter a date.";     
    } else{
        $dob = $input_dob;
    }

    // Validate phone
    $input_phone = trim($_POST["phone"]);
    if(empty($input_phone)){
        $phone_err = "Please enter a phone number.";
    } elseif(!filter_var($input_phone, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9]*$/")))){
        $phone_err = "Please enter a valid phone number.";
    } else{
        $phone = $input_phone;
    }
    
    // Check input errors before inserting in database
    if(empty($email_err) && empty($pass_err) && empty($name_err) && empty($dob_err) && empty($phone_err)){

        $sql = "insert into login_data values(?,?,?)";
        $sql2 = "insert into users values(?,?,?,?,?)";
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $_POST['email'],$_POST['pass'],$user);
            $user = "user";
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt2 = $mysqli->prepare($sql2)){
                    // Bind variables to the prepared statement as parameters
                    $stmt2->bind_param("sssss", $_POST['email'],$_POST['name'],$_POST['phone'],$_POST['dob'],$_POST['sex']);
                    // Attempt to execute the prepared statement
                    if($stmt2->execute()){
                        header("Location:init.php");
                        exit();
                    } else{
                        echo "Something went wrong. Please try again later.";
                    }
                }
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
        else {
            $error = $mysqli->errno . ' ' . $mysqli->error;
            echo $error; // 1054 Unknown column 'foo' in 'field list'
        }
         
        // Close statement
        $stmt->close(); $stmt2->close();
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
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

<!-- Bootstrap Date-Picker Plugin -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
</head>
<body>
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
                            <span class="help-block"><?php echo $email_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($pass_err)) ? 'has-error' : ''; ?>">
                            <label>Password</label>
                            <input type="text" name="pass" class="form-control" value="">
                            <span class="help-block"><?php echo $pass_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" value="">
                            <span class="help-block"><?php echo $phone_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($dob_err)) ? 'has-error' : ''; ?>">
                            <label>Date of Birth</label>
                            <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                            <input name="dob" type="text" class="form-control">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                            
                        </div>
                                <span class="help-block"><?php echo $dob_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($sex_err)) ? 'has-error' : ''; ?>">
                            <label>Sex</label><br>
                            <label class="radio-inline"><input type="radio" name="optradio" value="male">Male</label>
<label class="radio-inline"><input type="radio" name="optradio" value="female">Female</label>
<label class="radio-inline"><input type="radio" name="optradio" value="other">Other</label>
                        </div>
                        <input type="submit" class="btn btn-success" value="Login">
                        <a href="init.php" class="btn btn-danger">Cancel</a>
                        <div class="form-group <?php echo (!empty($res_err)) ? 'has-error' : ''; ?>">
                        <br>
                    </form>
                </div>
                
            </div>        
        </div>
    </div>
</body>
</html>