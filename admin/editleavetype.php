<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['update'])) {
        $lid = intval($_GET['lid']);
        $leavetype = $_POST['leavetype'];
        $description = $_POST['description'];
        $sql = "update tblleavetype set LeaveType=:leavetype,Description=:description where id=:lid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':leavetype', $leavetype, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':lid', $lid, PDO::PARAM_STR);
        $query->execute();

        $msg = "Leave type updated Successfully";
        echo "<script> 
                setTimeout(function(){window.location.href = 'editleavetype.php';}, 3000);
            </script>";
    }

?>


    <body>
        <?php include('includes/header.php'); ?>

        <?php include('includes/sidebar.php'); ?>
        <main class="mn-inner">
            <div class="row">
                <div class="col s12">
                    <div class="page-title">Edit Leave Type</div>
                </div>
                <div class="col s12 m12 l6">
                    <div class="card">
                        <div class="card-content">

                            <div class="row">
                                <form class="col s12" name="chngpwd" method="post">
                                    <?php if ($error) { ?><div class="errorWrap"><strong>ERROR</strong> : <?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div><?php } ?>
                                    <?php
                                    $lid = intval($_GET['lid']);
                                    $sql = "SELECT * from tblleavetype where id=:lid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':lid', $lid, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {               ?>

                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input id="leavetype" type="text" class="validate" autocomplete="off" name="leavetype" value="<?php echo htmlentities($result->LeaveType); ?>" required>
                                                    <label for="leavetype">Leave Type</label>
                                                </div>


                                                <div class="input-field col s12">
                                                    <textarea id="textarea1" name="description" class="materialize-textarea" name="description" length="500"><?php echo htmlentities($result->Description); ?></textarea>
                                                    <label for="deptshortname">Description</label>
                                                </div>

                                        <?php }
                                    } ?>
                                        <div class="input-field col s12">
                                            <button type="submit" name="update" class="waves-effect waves-light btn indigo m-b-xs">Update</button>

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