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
                                                <td><a href="employeeDetail.php?id=<?php echo htmlentities($result->id); ?>"><i class="material-icons">preview</i></a>

                                                <a href="editemployee.php?empid=<?php echo htmlentities($result->id); ?>"><i class="material-icons">mode_edit</i></a>

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

            <?php include('includes/footer.php'); ?>
    </body>

    </html>
<?php } ?>