<?php
// Include config file
require_once "config.php";
$email = "";
$name = "";
$phone = "";
$sex = "";
$dob = "";
$resume = "";
$dp = "";
$resume_mode = "";
$dp_mode = "";
$uid = "";
$resume_src = "";
if (isset($_GET["email"])) {
    $email = $_GET["email"];
} else {
    header("Location:init.php");
    exit();
}
$sql = "select * from users WHERE email=?";

if ($stmt = $mysqli->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("s", $_GET['email']);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $name = $row["name"];
            $phone = $row["phone"];
            $sex = $row["sex"];
            $uid = $row["uid"];
            $dob = $row["dob"];
            $resume = $row["resume"] . "";
            $dp = $row["dp"] . "";
            if ($resume == "") {
                $resume_mode = "Add Resume";
            } else {
                $resume_mode = "Update Resume";
            }
            if ($dp == "") {
                $dp_mode = "Add Profile Pic";
            } else {
                $dp_mode = "Update Profile Pic";
            }
        }
    }
}

//to add the code here for yes button of the form
if (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST['meh'])) {
    $sql = "call DeleteAll(?,?)";
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("si", $email, $_uid);
        $email = $_GET["email"];
        $_uid = $uid;
        // Attempt to executethe prepared statement
        if ($stmt->execute()) {
            header("Location:init.php");
            exit();
        } else {
            echo "didnt exec";
        }
    } else {
        echo "didnt bind";
    }
}



if (isset($_FILES['image'])) {
    $errors = array();
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $tmp = explode('.', $_FILES['image']['name']);
    $file_ext = strtolower(end($tmp));

    $expensions = array("jpeg", "jpg", "png");

    if (in_array($file_ext, $expensions) === false) {
        $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
    }

    if ($file_size > 7097152) {
        $errors[] = 'File size must be less than 7 MB';
    }

    if (empty($errors) == true) {
        $img_src = "img/" . $file_name;
        if ($dp_mode == "Add Profile Pic") {
            $sql = "insert into images(uid,image_path) values(?,?)";
            if ($stmt = $mysqli->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("ss", $_uid, $src);
                $_uid = $uid;
                $src = $img_src;

                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    //Del this block after trigger

                    //below 3 lines to be added to main
                    move_uploaded_file($file_tmp, "img/" . $file_name);
                    header("Location:user_info.php?email=" . $_GET["email"]);
                    exit();
                } else {
                    echo "didnt exec";
                }
            } else {
                echo "didnt bind";
            }
        } else if ($dp_mode == "Update Profile Pic") {
            $sql = "update images set image_path=? where uid=?";
            if ($stmt = $mysqli->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("ss", $src, $_uid);
                $_uid = $uid;
                $src = $img_src;
                if ($stmt->execute()) {
                    move_uploaded_file($file_tmp, "img/" . $file_name);
                    header("Location:user_info.php?email=" . $_GET["email"]);
                    exit();
                } else {
                    echo "Update error!";
                }
            }
        } else {
            echo $dp_mode;
        }
    } else {
        echo implode($errors) . "poop";
    }
}
if (isset($_FILES['resume'])) {
    $errors = array();
    $file_name = $_FILES['resume']['name'];
    $file_size = $_FILES['resume']['size'];
    $file_tmp = $_FILES['resume']['tmp_name'];
    $file_type = $_FILES['resume']['type'];
    $tmp = explode('.', $_FILES['resume']['name']);
    $file_ext = strtolower(end($tmp));

    $expensions = array("txt", "pdf", "docx", "doc");

    if (in_array($file_ext, $expensions) === false) {
        $errors[] = "extension not allowed, please choose a pdf or doc file.";
    }

    if ($file_size > 7097152) {
        $errors[] = 'File size must be less than 7 MB';
    }

    if (empty($errors) == true) {
        $resume_src = "resume/" . $file_name;
        if ($resume_mode == "Add Resume") {
            $sql = "insert into resumes(uid,link) values(?,?)";
            if ($stmt = $mysqli->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("ss", $_uid, $src);
                $_uid = $uid;
                $src = $resume_src;


                // Attempt to execute the prepared statement
                if ($stmt->execute()) {

                    //below 3 lines to be added to main
                    move_uploaded_file($file_tmp, "resume/" . $file_name);
                    header("Location:user_info.php?email=" . $_GET["email"]);
                    exit();
                }
            }
        } else if ($resume_mode == "Update Resume") {
            $sql = "update resumes set link=? where uid=?";
            if ($stmt = $mysqli->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("ss", $src, $_uid);
                $_uid = $uid;
                $src = $resume_src;
                if ($stmt->execute()) {
                    move_uploaded_file($file_tmp, "resume/" . $file_name);
                    header("Location:user_info.php?email=" . $_GET["email"]);
                    exit();
                } else {
                    echo "Update error!";
                }
            }
        } else {
            echo $resume_mode;
        }
    } else { }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/shards.min.css?v=3.0.0">
    <link rel="stylesheet" href="css/shards-demo.min.css?v=3.0.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>

    <style type="text/css">
        .wrapper {
            width: 650px;
            margin: 0 auto;
        }

        .page-header h2 {
            margin-top: 0;
        }

        table tr td:last-child a {
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>

<body style="background-color:#f7f7f7;">
    <div style="display: flex; justify-content: flex-start; padding-left:10px">
        <a class="d-table ml-auto mr-auto" href="http://localhost/Resumocha_MiniProject/index.html">
            <i class="fa fa-sign-out fa-3x"></i>
        </a>
    </div>
    <!-- style="display: flex; align-items: center; height: -webkit-fill-available;justify-content: center; background-color:#f7f7f7" -->
    <div style="display: flex; align-items: center; height: -webkit-fill-available;justify-content: center; ">
        <div class="card col-lg-6 col-md-6 col-sm-12 ">

            <div class="card-body ">
                <div class="wrapper ">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="page-header clearfix">
                                    <h2 class="pull-left">Your Details</h2>
                                    <button type="button" class="btn btn-danger btn-lg pull-right btn-pill" data-toggle="modal" data-target="#myModalDelete"> <?php echo "Delete Account" ?></button>
                                    <button type="button" class="btn btn-success btn-lg pull-right btn-pill" data-toggle="modal" data-target="#myModalResume" style="margin-right: 15px;"> <?php echo $resume_mode ?></button>
                                    <button type="button" class="btn btn-success btn-lg pull-right btn-pill" data-toggle="modal" data-target="#myModalPic" style="margin-right: 15px;"> <?php echo $dp_mode ?></button>
                                    <div class="modal fade" id="myModalResume" role="dialog">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close btn btn-lg btn-pill" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Upload your Resume</h4>
                                                </div>
                                                <div class="modal-body" align="center">
                                                    <form action="" method="POST" enctype="multipart/form-data">
                                                        <input type="file" name="resume" class="btn btn-info btn-lg btn-pill" /><br>
                                                        <input type="submit" class="btn btn-success btn-lg btn-pill" /></form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="myModalDelete" role="dialog">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Are you really sure you want to delete your account?</h4>
                                                </div>
                                                <div class="modal-body" align="center">
                                                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                                                        <input type='text' name='meh' style="display:none" />
                                                        <input type="submit" class="btn btn-danger btn-lg btn-pill" style="margin-right: 15px;" value="Yes" />
                                                        <button type="button" class="btn btn-warning btn-lg btn-pill" data-dismiss="modal">No</button></form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="myModalPic" role="dialog">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Upload your Photo</h4>
                                                </div>
                                                <div class="modal-body" align="center">
                                                    <form action="" method="POST" enctype="multipart/form-data">
                                                        <input type="file" name="image" class="btn btn-info btn-pill" /><br>
                                                        <input type="submit" class="btn btn-success btn-pill" /></form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default btn-pill" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    // Include config file
                                    require_once "config.php";
                                    if ($dp_mode == "Add Profile Pic") {
                                        $img_src = "img/default.jpg";      //Profile pic
                                    } else {
                                        // Attempt select query execution
                                        $sql = "select * from images WHERE uid=?";

                                        if ($stmt = $mysqli->prepare($sql)) {
                                            // Bind variables to the prepared statement as parameters
                                            $stmt->bind_param("s", $_uid);
                                            $_uid = $uid;

                                            // Attempt to execute the prepared statement
                                            if ($stmt->execute()) {
                                                $result = $stmt->get_result();

                                                if ($result->num_rows == 1) {
                                                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                                                    $row = $result->fetch_array(MYSQLI_ASSOC);

                                                    // Retrieve individual field value
                                                    $img_src = $row["image_path"];
                                                    mysqli_free_result($result);
                                                } else {
                                                    echo "<p class='lead'><em>ERROR:Multiple records were found.</em></p>";
                                                }
                                            } else {
                                                echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                                            }
                                        }
                                    }
                                    if ($resume_mode == "Add Resume") {      //resume
                                        echo "";
                                    } else {
                                        // Attempt select query execution
                                        $sql = "select * from resumes WHERE uid=?";

                                        if ($stmt = $mysqli->prepare($sql)) {
                                            // Bind variables to the prepared statement as parameters
                                            $stmt->bind_param("s", $_uid);
                                            $_uid = $uid;

                                            // Attempt to execute the prepared statement
                                            if ($stmt->execute()) {
                                                $result = $stmt->get_result();

                                                if ($result->num_rows == 1) {
                                                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                                                    $row = $result->fetch_array(MYSQLI_ASSOC);

                                                    // Retrieve individual field value
                                                    $resume_src = $row["link"];
                                                    mysqli_free_result($result);
                                                } else {
                                                    echo "<p class='lead'><em>ERROR:Multiple records were found.</em></p>";
                                                }
                                            } else {
                                                echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                                            }
                                        }
                                    }
                                    ?>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <img src=<?php echo $img_src ?> width="200px"></div>
                                <div class="col-md-8">
                                    <h2><b><?php echo $name ?></b></h2><br>
                                    <h5><?php echo "Email : " . $email ?></h5>
                                    <h5><?php echo "Phone : " . $phone ?></h5>
                                    <h5><?php echo "DOB   : " . $dob ?></h5>
                                    <h5><?php echo "Gender: " . $sex ?></h5>
                                </div>
                            </div>

                            <div class="row">
                                <br>
                                <div class="col-md-4">
                                    <!-- <button type="submit" formaction="\resume-generator\index.html" class="btn btn-primary btn-block">Make Resume</button> -->
                                    <a href="resume-generator\index.html" class=" btn btn-primary btn-block btn-lg btn-pill" target="_blank">Make Resume</a>
                                </div>
                            </div>


                            <div class="row">
                                <br>
                                <div class="col-md-12">
                                    <?php if (($resume_mode == "Add Resume")) {
                                        echo ' <a href="create.php" class="btn btn-danger disabled btn-block btn-lg btn-pill" >Resume not Uploaded</a><br>';
                                    } else {
                                        echo '<a href="' . $resume_src . '" class="btn btn-info  btn-block btn-lg btn-pill"  target="_blank" >Veiw Resume</a><br>';
                                    } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <?php if (($resume_mode == "Add Resume") || ($dp_mode == "Add Profile Pic")) {
                                        echo '<div class= "alert alert-danger">
                        You havent completed your profile!</div>';
                                    } else {
                                        echo '<div class= "alert alert-success">
                            Your Profile is Complete.</div>';
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>



</html>