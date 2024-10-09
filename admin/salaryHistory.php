<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    // Function to fetch employee salary history details
    function getEmployeeSalaryHistory($dbh)
    {

        $sql = "
            SELECT e.EmpId, e.FirstName, e.LastName, p.position_name,
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

    <?php include('includes/footer.php'); ?>

</body>

</html>