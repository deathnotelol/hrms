<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);


include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    // code for Inactive  employee    
    if (isset($_GET['inid'])) {
        $id = $_GET['inid'];
        $status = 0;
        $sql = "update tblemployees set Status=:status  WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        header('location:manageemployee.php');
    }



    //code for active employee
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $status = 1;
        $sql = "update tblemployees set Status=:status  WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        header('location:manageemployee.php');
    }

    // Employee delete method

    if (isset($_GET['delid'])) {
        $id = intval($_GET['delid']); // Convert delid to integer for security

        // Fetch the employee name before deleting for personalized success message
        $sql = "SELECT FirstName, LastName FROM tblemployees WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $employee = $query->fetch(PDO::FETCH_ASSOC);

        if ($employee) {
            $empName = $employee['FirstName'] . ' ' . $employee['LastName'];

            // SQL query to delete the employee by id
            $sql = "DELETE FROM tblemployees WHERE id = :id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the query
            if ($query->execute()) {
                // Redirect with personalized success message
                header('Location: manageemployee.php?msg=Employee ' . urlencode($empName) . ' deleted successfully');
                exit();
            } else {
                // If the deletion fails, redirect with an error message
                header('Location: manageemployee.php?error=Unable to delete employee. Please try again.');
                exit();
            }
        } else {
            // If employee is not found, redirect with an error message
            header('Location: manageemployee.php?error=Employee not found.');
            exit();
        }
    }
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>

        <!-- Title -->
        <title>Admin | Manage Employees</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />

        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
        <link href="../assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">


        <!-- Theme Styles -->
        <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/custom.css" rel="stylesheet" type="text/css" />
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
        <?php include('includes/header.php'); ?>

        <?php include('includes/sidebar.php'); ?>
        <main class="mn-inner">
            <div class="row">
                <div class="col s12">
                    <div class="page-title">Manage Employes</div>
                </div>

                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title">Employees Info</span>

                            <!-- Display message -->

                            <?php if (isset($_GET['msg'])) { ?>
                                <div class="card-panel green white-text" id="notification">
                                    <span><?php echo htmlentities($_GET['msg']); ?></span>
                                    <button class="close-btn" style="float: right; background: none; border: none; color: white; cursor: pointer; font-size: 24px; padding: 0 10px; line-height: 1; margin-left: 10px;">
                                        &times;
                                    </button>
                                </div>
                            <?php } ?>

                            <?php if (isset($_GET['error'])) { ?>
                                <div class="card-panel red white-text" id="notification">
                                    <span><?php echo htmlentities($_GET['error']); ?></span>
                                    <button class="close-btn" style="float: right; background: none; border: none; color: white; cursor: pointer; font-size: 24px; padding: 0 10px; line-height: 1; margin-left: 10px;">
                                        &times;
                                    </button>
                                </div>
                            <?php } ?>



                            <table id="example" class="display responsive-table ">
                                <thead>
                                    <tr>
                                        <th>Sr no</th>
                                        <th>Profile Image</th>
                                        <th>Emp Id</th>
                                        <th>Full Name</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th>Reg Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $sql = "SELECT EmpId, FirstName, LastName, Department, Status, RegDate, id, ProfileImage FROM tblemployees";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) { ?>
                                            <tr>
                                                <td> <?php echo htmlentities($cnt); ?></td>
                                                <td>
                                                    <?php if ($result->ProfileImage != "") { ?>
                                                        <img class="profile-img" src="<?php echo htmlentities($result->ProfileImage); ?>" alt="Profile Image" width="75" height="75">
                                                    <?php } else { ?>
                                                        <img src="../assets/images/upload/profile-image.png" alt="Default Image" width="75" height="75"> <!-- Fallback image -->
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo htmlentities($result->EmpId); ?></td>
                                                <td><?php echo htmlentities($result->FirstName); ?>&nbsp;<?php echo htmlentities($result->LastName); ?></td>
                                                <td><?php echo htmlentities($result->Department); ?></td>
                                                <td><?php $stats = $result->Status;
                                                    if ($stats) {
                                                    ?>
                                                        <a class="waves-effect waves-green btn-flat m-b-xs">Active</a>
                                                    <?php } else { ?>
                                                        <a class="waves-effect waves-red btn-flat m-b-xs">Inactive</a>
                                                    <?php } ?>


                                                </td>
                                                <td><?php echo htmlentities($result->RegDate); ?></td>
                                                <td><a href="editemployee.php?empid=<?php echo htmlentities($result->id); ?>"><i class="material-icons">mode_edit</i></a>
                                                    <?php if ($result->Status == 1) { ?>
                                                        <a href="manageemployee.php?inid=<?php echo htmlentities($result->id); ?>" onclick="return confirm('Are you sure you want to inactive this Employe?');"> <i class=" material-icons" title="Inactive">clear</i>
                                                        <?php } else { ?>

                                                            <a href="manageemployee.php?id=<?php echo htmlentities($result->id); ?>" onclick="return confirm('Are you sure you want to active this employee?');"><i class="material-icons" title="Active">done</i>
                                                            <?php } ?>


                                                            <!-- Delete Button with Confirmation (Passing the Employee's First and Last Name) -->
                                                            <a class="material-symbols-outlined" href="manageemployee.php?delid=<?php echo htmlentities($result->id); ?>"
                                                                onclick="return confirm('Are you sure you want to delete employee <?php echo htmlentities($result->FirstName . ' ' . $result->LastName); ?>?');"
                                                                title="Delete">
                                                                delete
                                                            </a>


                                                </td>
                                            </tr>
                                    <?php $cnt++;
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Close button functionality
                document.querySelectorAll('.close-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        this.parentElement.style.display = 'none'; // Hide the notification
                    });
                });

                // Remove query string if there's 'msg' or 'error'
                if (window.location.search.indexOf('msg=') > -1 || window.location.search.indexOf('error=') > -1) {
                    const url = window.location.href.split('?')[0]; // Get the URL without the query string
                    window.history.replaceState(null, null, url); // Replace the current history state with the clean URL
                }
            </script>
        </main>
        </div>
        <div class="left-sidebar-hover"></div>

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
<?php } ?>