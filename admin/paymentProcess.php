<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve POST values from the confirmPayment form
        $empId = isset($_POST['empId']) ? $_POST['empId'] : null;
        $paymentMonth = isset($_POST['month']) ? $_POST['month'] : null;
        $paymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : null;
        $salary = isset($_POST['salary']) ? $_POST['salary'] : null;
        $overtime = isset($_POST['overtime']) ? $_POST['overtime'] : null;
        $deduction = isset($_POST['deduction']) ? $_POST['deduction'] : null;
        $netsalary = isset($_POST['netsalary']) ? $_POST['netsalary'] : null;

        // Convert the month integer to a month name
        $monthNames = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];

        $paymentMonthName = isset($monthNames[(int)$paymentMonth]) ? $monthNames[(int)$paymentMonth] : null;

        $sqlCheck = "SELECT COUNT(*) FROM tblsalaries WHERE EmpId = :empId AND PaymentMonth = :paymentMonthName";
        $stmtCheck = $dbh->prepare($sqlCheck);
        $stmtCheck->bindParam(':empId', $empId, PDO::PARAM_STR);
        $stmtCheck->bindParam(':paymentMonthName', $paymentMonthName, PDO::PARAM_STR);
        $stmtCheck->execute();
        $paymentExists = $stmtCheck->fetchColumn();

        if ($paymentExists > 0) {
            echo "<script>
                alert('Error: This employee has already been paid for the selected month.');
                window.location.href = 'paySalary.php';
            </script>";
        } else {

            // Ensure required fields are not empty
            if ($paymentMonthName) {
                try {
                    // Insert the data into the tblsalaries table
                    $sql = "INSERT INTO tblsalaries (EmpId, PaymentMonth, PaymentDate, PaymentMethod, Salary, Allowance, Deduction, NetSalary) 
                    VALUES (:empId, :paymentMonthName, :paymentDate, :paymentMethod, :salary, :overtime, :deduction, :netsalary)";
                    $stmt = $dbh->prepare($sql);
                    $stmt->bindParam(':empId', $empId, PDO::PARAM_STR);
                    $stmt->bindParam(':paymentMonthName', $paymentMonthName, PDO::PARAM_STR);
                    $stmt->bindParam(':paymentDate', $paymentDate, PDO::PARAM_STR);
                    $stmt->bindParam(':paymentMethod', $paymentMethod, PDO::PARAM_STR);
                    $stmt->bindParam(':salary', $salary, PDO::PARAM_STR);
                    $stmt->bindParam(':overtime', $overtime, PDO::PARAM_STR);
                    $stmt->bindParam(':deduction', $deduction, PDO::PARAM_STR);
                    $stmt->bindParam(':netsalary', $netsalary, PDO::PARAM_STR);
    
                    $stmt->execute();
    
                    header('location:salaryHistory.php');
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "Error: Missing required fields.";
            }

        }

    }
}
