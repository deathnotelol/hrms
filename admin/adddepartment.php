<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
}

if (isset($_POST['add'])) {
    $deptname = $_POST['departmentname'];
    $deptshortname = $_POST['departmentshortname'];
    $deptcode = $_POST['deptcode'];
    $sql = "INSERT INTO tbldepartments(DepartmentName,DepartmentCode,DepartmentShortName) VALUES(:deptname,:deptcode,:deptshortname)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':deptname', $deptname, PDO::PARAM_STR);
    $query->bindParam(':deptcode', $deptcode, PDO::PARAM_STR);
    $query->bindParam(':deptshortname', $deptshortname, PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if ($lastInsertId) {
        $msg = "Department Created Successfully";
        echo "<script>
        setTimeout(function(){
            window.location.href = 'managedepartments.php';
        }, 3000);
</script>";
    } else {
        $error = "Something went wrong. Please try again";
    }
}

?>



<body>
    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>
    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
                <div class="page-title">Add Department</div>
            </div>
            <div class="col s12 m12 l6">
                <div class="card">
                    <div class="card-content">

                        <div class="row">
                            <form class="col s12" name="chngpwd" method="post">
                                <?php if ($error) { ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="departmentname" type="text" class="validate" autocomplete="off" name="departmentname" required>
                                        <label for="deptname">Department Name</label>
                                    </div>


                                    <div class="input-field col s12">
                                        <input id="departmentshortname" type="text" class="validate" autocomplete="off" name="departmentshortname" required>
                                        <label for="deptshortname">Department Short Name</label>
                                    </div>
                                    <div class="input-field col s12">
                                        <input id="deptcode" type="text" name="deptcode" class="validate" autocomplete="off" required>
                                        <label for="password">Department Code</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <button type="submit" name="add" class="waves-effect waves-light btn indigo m-b-xs">ADD</button>

                                    </div>
                                </div>
                            </form>
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