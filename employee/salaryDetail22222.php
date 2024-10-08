<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('includes/config.php');

if (strlen($_SESSION['emplogin']) == 0) {
    header('location:index.php');
} else {
    // Function to fetch employee salary history details
    function getEmployeeSalaryHistory($dbh)
    {

        $sql = "
            SELECT e.EmpId, e.FirstName, e.LastName, p.position_name, p.skill_level, 
                   s.PaymentMonth, s.PaymentDate, s.PaymentMethod, s.Salary, s.Allowance, s.Deduction, s.NetSalary
            FROM tblemployees e
            JOIN positions p ON e.PositionID = p.id
            JOIN tblsalaries s ON e.EmpId = s.EmpId
        ";
        $stmt = $dbh->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch salary history for display
    $salaryHistory = getEmployeeSalaryHistory($dbh);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title -->
    <title>Admin | Salary History</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="Steelcoders" />

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <link href="assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Theme Styles -->
    <link href="assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/custom.css" rel="stylesheet" type="text/css" />
</head>

<body>


    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
                <div class="page-title">Salary Payment History</div>
                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title">Salary Information</span>
                            <table id="example" class="display responsive-table ">
                                <thead>
                                    <tr>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Skill</th>
                                        <th>Payment Month</th>
                                        <th>Payment Date</th>
                                        <th>Payment Method</th>
                                        <th>Salary</th>
                                        <th>Allowance</th>
                                        <th>Deduction</th>
                                        <th>Net Salary</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($salaryHistory as $history) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($history['EmpId']); ?></td>
                                            <td><?php echo htmlspecialchars($history['FirstName'] . " " . $history['LastName']); ?></td>
                                            <td><?php echo htmlspecialchars($history['position_name']); ?></td>
                                            <td><?php echo htmlspecialchars($history['skill_level']); ?></td>
                                            <td><?php echo htmlspecialchars($history['PaymentMonth']); ?></td>
                                            <td><?php echo htmlspecialchars($history['PaymentDate']); ?></td>
                                            <td><?php echo htmlspecialchars($history['PaymentMethod']); ?></td>
                                            <td><?php echo htmlspecialchars($history['Salary']); ?></td>
                                            <td><?php echo htmlspecialchars($history['Allowance']); ?></td>
                                            <td><?php echo htmlspecialchars($history['Deduction']); ?></td>
                                            <td><?php echo htmlspecialchars($history['NetSalary']); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Javascripts -->
    <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/alpha.min.js"></script>
    <script src="assets/js/pages/table-data.js"></script>
</body>

</html>