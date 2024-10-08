<?php
session_start();
include('includes/config.php');

// Fetch employee and salary details by EmpId
$empId = intval($_GET['empId']);
$sql = "
    SELECT e.FirstName, e.LastName, s.PaymentMonth, s.PaymentDate, s.PaymentMethod, s.Salary, s.Allowance, s.Deduction, s.NetSalary
    FROM tblsalaries s
    JOIN tblemployees e ON s.EmpId = e.EmpId
    WHERE s.EmpId = :empId
    ORDER BY s.id DESC LIMIT 1
";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':empId', $empId, PDO::PARAM_INT);
$stmt->execute();
$payslip = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payslip</title>
    <!-- Add your CSS and other headers here -->
</head>
<body>
    <h2>Payslip for <?php echo $payslip['FirstName'] . ' ' . $payslip['LastName']; ?></h2>

    <table>
        <tr>
            <th>Payment Month:</th>
            <td><?php echo $payslip['PaymentMonth']; ?></td>
        </tr>
        <tr>
            <th>Payment Date:</th>
            <td><?php echo $payslip['PaymentDate']; ?></td>
        </tr>
        <tr>
            <th>Payment Method:</th>
            <td><?php echo $payslip['PaymentMethod']; ?></td>
        </tr>
        <tr>
            <th>Basic Salary:</th>
            <td><?php echo $payslip['Salary']; ?></td>
        </tr>
        <tr>
            <th>Allowance:</th>
            <td><?php echo $payslip['Allowance']; ?></td>
        </tr>
        <tr>
            <th>Deduction:</th>
            <td><?php echo $payslip['Deduction']; ?></td>
        </tr>
        <tr>
            <th>Net Salary:</th>
            <td><?php echo $payslip['NetSalary']; ?></td>
        </tr>
    </table>

    <!-- Add edit and delete options if needed -->
</body>
</html>
