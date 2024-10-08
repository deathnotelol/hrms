<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Redirect to login page if session is not set
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    $posid = intval($_GET['posid']);  // Get position ID from URL

    // Check if form has been submitted
    if (isset($_POST['update'])) {
        // Retrieve updated form data
        $positionname = $_POST['positionname'];
        $skilllevel = $_POST['skilllevel'];
        $salary = $_POST['salary'];

        // Prepare SQL query to update the record
        $sql = "UPDATE positions SET position_name=:position_name, salary=:salary WHERE id=:posid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':position_name', $positionname, PDO::PARAM_STR);
        $query->bindParam(':salary', $salary, PDO::PARAM_STR);
        $query->bindParam(':posid', $posid, PDO::PARAM_STR);

        // Execute the update query
        if ($query->execute()) {
            $msg = "Position updated successfully.";
            echo "<script>
                setTimeout(function(){
                    window.location.href = 'managePosition.php';
                }, 3000);
            </script>";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin | Update Position</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css" />
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }

        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?> 
    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
                <div class="page-title">Update Position</div>
            </div>
            <div class="col s12 m12 l6">
                <div class="card">
                    <div class="card-content">
                        <!-- Display Success or Error Messages -->
                        <?php if ($msg) { ?>
                            <div class="succWrap">
                                <strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?>
                                <!-- Redirection message -->
                                <p>Redirecting to manage positions page in 3 seconds...</p>
                            </div>
                        <?php } elseif ($error) { ?>
                            <div class="errorWrap">
                                <strong>ERROR</strong>: <?php echo htmlentities($error); ?>
                            </div>
                        <?php } ?>

                        <div class="row">
                            <form class="col s12" method="post">
                                <?php
                                $sql = "SELECT * FROM positions WHERE id=:posid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':posid', $posid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);

                                if ($query->rowCount() > 0) {
                                    foreach ($results as $result) { ?>
                                        <div class="input-field col s12">
                                            <input id="positionname" type="text" name="positionname" class="validate" required value="<?php echo htmlentities($result->position_name); ?>">
                                            <label for="positionname">Position Name</label>
                                        </div>


                                        <div class="input-field col s12">
                                            <input id="salary" type="number" name="salary" class="validate" required value="<?php echo htmlentities($result->salary); ?>">
                                            <label for="salary">Salary</label>
                                        </div>

                                        <div class="input-field col s12">
                                            <button type="submit" name="update" class="waves-effect waves-light btn indigo m-b-xs">Update</button>
                                        </div>
                                    <?php }
                                } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Javascripts -->
    <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="../assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/alpha.min.js"></script>
    <script src="../assets/js/pages/table-data.js"></script>
</body>

</html>
