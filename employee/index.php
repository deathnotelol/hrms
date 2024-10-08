<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (isset($_POST['signin'])) {
    $uname = $_POST['username'];
    $password = md5($_POST['password']);
    $sql = "SELECT EmailId,Password,Status,id FROM tblemployees WHERE EmailId=:uname and Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uname', $uname, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $status = $result->Status;
            $_SESSION['eid'] = $result->id;
        }
        if ($status == 0) {
            $msg = "Your account is Inactive. Please contact admin";
        } else {
            $_SESSION['emplogin'] = $_POST['username'];
            echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
        }
    } else {

        echo "<script>alert('Invalid Details');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>HRMS | Home Page</title>


</head>

<body>
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>



    <aside id="slide-out" class="side-nav white fixed">
        <div class="side-nav-wrapper">


            <ul class="sidebar-menu collapsible collapsible-accordion" data-collapsible="accordion">
                <li>&nbsp;</li>
                <li class="no-padding"><a class="waves-effect waves-grey" href="index.php"><i class="material-icons">account_box</i>Employe Login</a></li>
                <li class="no-padding"><a class="waves-effect waves-grey" href="forgot-password.php"><i class="material-icons">account_box</i>Emp Password Recovery</a></li>

                <li class="no-padding"><a class="waves-effect waves-grey" href="../admin/"><i class="material-icons">account_box</i>Admin Login</a></li>

            </ul>
            <div class="footer">
                <p class="copyright">HRMS © Design by GROUP-3</p>
            </div>
        </div>
    </aside>
    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
                <div class="page-title">
                    <h4>Welcome to HR Management System</h4>
                </div>

                <div class="col s12 m6 l8 offset-l2 offset-m3">
                    <div class="card white darken-1">

                        <div class="card-content ">
                            <span class="card-title" style="font-size:20px;">Employee Login</span>
                            <?php if ($msg) { ?><div class="errorWrap"><strong>Error</strong> : <?php echo htmlentities($msg); ?> </div><?php } ?>
                            <div class="row">
                                <form class="col s12" name="signin" method="post">
                                    <div class="input-field col s12">
                                        <input id="username" type="text" name="username" class="validate" autocomplete="off" required>
                                        <label for="email">Email Id</label>
                                    </div>
                                    <div class="input-field col s12">
                                        <input id="password" type="password" class="validate" name="password" autocomplete="off" required>
                                        <label for="password">Password</label>
                                    </div>
                                    <div class="col s12 right-align m-t-sm">

                                        <input type="submit" name="signin" value="Sign in" class="waves-effect waves-light btn teal">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    </div>
    <div class="left-sidebar-hover"></div>

    <?php include('includes/footer.php'); ?>

</body>

</html>