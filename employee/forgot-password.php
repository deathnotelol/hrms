<?php
session_start();
error_reporting(0);
include('includes/config.php');
// Code for change password 
if (isset($_POST['change'])) {
    $newpassword = md5($_POST['newpassword']);
    $empid = $_SESSION['empid'];

    $con = "update tblemployees set Password=:newpassword where id=:empid";
    $chngpwd1 = $dbh->prepare($con);
    $chngpwd1->bindParam(':empid', $empid, PDO::PARAM_STR);
    $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
    $chngpwd1->execute();
    $msg = "Your Password succesfully changed";
    echo "<script>
    setTimeout(function(){
        window.location.href = 'index.php';
    }, 3000);
</script>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>HRMS | Password Recovery</title>
    <?php include('includes/header.php') ?>

    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }

        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }
    </style>

</head>

<body>

    <div class="mn-content fixed-sidebar">

        <aside id="slide-out" class="side-nav white fixed">
            <div class="side-nav-wrapper">


                <ul class="sidebar-menu collapsible collapsible-accordion" data-collapsible="accordion" style="">
                    <li>&nbsp;</li>
                    <li class="no-padding"><a class="waves-effect waves-grey" href="index.php"><i class="material-icons">account_box</i>Employe Login</a></li>
                    <li class="no-padding"><a class="waves-effect waves-grey" href="forgot-password.php"><i class="material-icons">account_box</i>Emp Password Recovery</a></li>

                    <li class="no-padding"><a class="waves-effect waves-grey" href="admin/"><i class="material-icons">account_box</i>Admin Login</a></li>

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
                        <h4>Employee Password Recovery</h4>
                    </div>

                    <div class="col s12 m6 l8 offset-l2 offset-m3">
                        <div class="card white darken-1">

                            <div class="card-content ">
                                <span class="card-title" style="font-size:20px;">Employee details</span>
                                <?php if ($msg) { ?><div class="succWrap"><strong>Success </strong> : <?php echo htmlentities($msg); ?> </div><?php } ?>
                                <div class="row">
                                    <form class="col s12" name="signin" method="post">
                                        <div class="input-field col s12">
                                            <input id="empid" type="text" name="empid" class="validate" autocomplete="off" required>
                                            <label for="email">Employee Id</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input id="password" type="text" class="validate" name="emailid" autocomplete="off" required>
                                            <label for="password">Email id</label>
                                        </div>
                                        <div class="col s12 right-align m-t-sm">

                                            <input type="submit" name="submit" value="Reset" class="waves-effect waves-light btn teal">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php if (isset($_POST['submit'])) {
                                $empid = $_POST['empid'];
                                $email = $_POST['emailid'];
                                $sql = "SELECT id FROM tblemployees WHERE EmailId=:email and EmpId=:empid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':email', $email, PDO::PARAM_STR);
                                $query->bindParam(':empid', $empid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $result) {
                                        $_SESSION['empid'] = $result->id;
                                    }
                            ?>

                                    <div class="row">
                                        <span class="card-title" style="font-size:20px;">change your password </span>
                                        <form class="col s12" name="udatepwd" method="post">
                                            <div class="input-field col s12">
                                                <input id="password" type="password" name="newpassword" class="validate" autocomplete="off" required>
                                                <label for="password">New Password</label>
                                            </div>

                                            <div class="input-field col s12">
                                                <input id="password" type="password" name="confirmpassword" class="validate" autocomplete="off" required>
                                                <label for="password">Confirm Password</label>
                                            </div>


                                            <div class="input-field col s12">
                                                <button type="submit" name="change" class="waves-effect waves-light btn indigo m-b-xs" onclick="return valid();">Change</button>

                                            </div>
                                    </div>
                                    </form>
                                <?php } else { ?>
                                    <div class="errorWrap" style="margin-left: 2%; font-size:22px;">
                                        <strong>ERROR</strong> : <?php echo htmlentities("Invalid details");
                                                                } ?>
                                    </div>
                                <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>
    <div class="left-sidebar-hover"></div>

    <?php include('includes/footer.php') ?>

</body>

</html>