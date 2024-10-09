<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
}

if (isset($_POST['add'])) {
    $positionname = $_POST['positionname'];
    $salary = $_POST['salary'];
    $sql = "INSERT INTO positions (position_name, salary) VALUES (:position_name,:salary)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':position_name', $positionname, PDO::PARAM_STR);
    $query->bindParam(':salary', $salary, PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if ($lastInsertId) {
        $msg = "Position Created Successfully";
        echo "<script>
        setTimeout(function(){
            window.location.href = 'managePosition.php';
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
                <div class="page-title">Add Position</div>
            </div>
            <div class="col s12 m12 l6">
                <div class="card">
                    <div class="card-content">

                        <div class="row">
                            <form class="col s12" name="chngpwd" method="post">
                                <?php if ($error) { ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="departmentname" type="text" class="validate" autocomplete="off" name="positionname" required>
                                        <label for="posname">Position Name</label>
                                    </div>

                                    <div class="input-field col s12">
                                        <input id="deptcode" type="number" name="salary" class="validate" autocomplete="off" required>
                                        <label for="salary">Salary</label>
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