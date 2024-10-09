<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['update'])) {
        $did = intval($_GET['deptid']);
        $deptname = $_POST['departmentname'];
        $deptshortname = $_POST['departmentshortname'];
        $deptcode = $_POST['deptcode'];
        $sql = "update tbldepartments set DepartmentName=:deptname,DepartmentCode=:deptcode,DepartmentShortName=:deptshortname where id=:did";
        $query = $dbh->prepare($sql);
        $query->bindParam(':deptname', $deptname, PDO::PARAM_STR);
        $query->bindParam(':deptcode', $deptcode, PDO::PARAM_STR);
        $query->bindParam(':deptshortname', $deptshortname, PDO::PARAM_STR);
        $query->bindParam(':did', $did, PDO::PARAM_STR);
        $query->execute();
        $msg = "Department updated Successfully";
        echo "<script>
                    setTimeout(function(){
                        window.location.href = 'managedepartments.php';
                    }, 3000);
            </script>";
    }

?>

    <body>
        <?php include('includes/header.php'); ?>

        <?php include('includes/sidebar.php'); ?>
        <main class="mn-inner">
            <div class="row">
                <div class="col s12">
                    <div class="page-title">Update Department</div>
                </div>
                <div class="col s12 m12 l6">
                    <div class="card">
                        <div class="card-content">

                            <div class="row">
                                <form class="col s12" name="chngpwd" method="post">
                                    <?php if ($error) { ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div><?php } ?>
                                    <?php
                                    $did = intval($_GET['deptid']);
                                    $sql = "SELECT * from tbldepartments WHERE id=:did";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':did', $did, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {               ?>

                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input id="departmentname" type="text" class="validate" autocomplete="off" name="departmentname" value="<?php echo htmlentities($result->DepartmentName); ?>" required>
                                                    <label for="deptname">Department Name</label>
                                                </div>


                                                <div class="input-field col s12">
                                                    <input id="departmentshortname" type="text" class="validate" autocomplete="off" value="<?php echo htmlentities($result->DepartmentShortName); ?>" name="departmentshortname" required>
                                                    <label for="deptshortname">Department Short Name</label>
                                                </div>
                                                <div class="input-field col s12">
                                                    <input id="deptcode" type="text" name="deptcode" class="validate" autocomplete="off" value="<?php echo htmlentities($result->DepartmentCode); ?>" required>
                                                    <label for="password">Department Code</label>
                                                </div>

                                        <?php }
                                    } ?>


                                        <div class="input-field col s12">
                                            <button type="submit" name="update" class="waves-effect waves-light btn indigo m-b-xs">UPDATE</button>

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
<?php } ?>