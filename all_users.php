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

<body style="background-color:#f7f7f7; ">
    <div style="display: flex; justify-content: flex-start; padding-left:10px">
        <a class="d-table ml-auto mr-auto" href="http://localhost/Resumocha_MiniProject/index.html">
            <i class="fa fa-sign-out fa-3x"></i>
        </a>
    </div>
    <div style="display: flex; align-items: center; height: -webkit-fill-available;justify-content: center;">
        <div class="card col-lg-6 col-md-6 col-sm-12">
            <div class="card-body">
                <div class="wrapper">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-15">
                                <div class="page-header clearfix">
                                    <h2 class="pull-left">Users</h2>
                                </div>
                                <?php
                                // Include config file
                                require_once "config.php";

                                // Attempt select query execution
                                $sql = "SELECT u.email,u.name,u.phone,u.dob,u.sex,r.link,i.image_path FROM users u,resumes r,images i where dp='true' and resume='true' and u.uid=i.uid and u.uid=r.uid";
                                if ($result = $mysqli->query($sql)) {
                                    if ($result->num_rows > 0) {
                                        echo "<table class='table table-bordered table-striped'>";
                                        echo "<thead>";
                                        echo "<tr>";
                                        echo "<th>Profile Pic</th>";
                                        echo "<th>Email</th>";
                                        echo "<th>Name</th>";
                                        echo "<th>Phone</th>";
                                        echo "<th>Birthday</th>";
                                        echo "<th>Gender</th>";
                                        echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";
                                        while ($row = $result->fetch_array()) {
                                            echo "<tr>";
                                            echo "<td><img src='" . $row['image_path'] . "' width='75px'></td>";
                                            echo "<td>" . $row['email'] . "</td>";
                                            echo "<td>" . $row['name'] . "</td>";
                                            echo "<td>" . $row['phone'] . "</td>";
                                            echo "<td>" . $row['dob'] . "</td>";
                                            echo "<td>" . $row['sex'] . "</td>";
                                            echo "<td>";
                                            echo "<a href='" . $row['link'] . "' target='_blank'>Veiw Resume</a>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                        echo "</tbody>";
                                        echo "</table>";
                                        // Free result set
                                        $result->free();
                                    } else {
                                        echo "<p class='lead'><em>No records were found.</em></p>";
                                    }
                                } else {
                                    echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
                                }

                                // Close connection
                                $mysqli->close();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html