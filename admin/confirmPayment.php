<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
        $empId = $_POST['empId'];
        $position = $_POST['position'];
        
        $salary = $_POST['salary'];
        $netsalary = $_POST['netsalary'];
        $overtime = $_POST['overtime'];
        $deduction = $_POST['deduction'];

        // Check if the form is submitted and validate the data
        if (isset($_POST['payment_method'], $_POST['month'])) {
            $paymentMethod = $_POST['payment_method'];
            $paymentMonth = $_POST['month'];
            // Proceed with salary payment process
        }
    }
}
?>



<body>
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
                <div class="page-title">Confirm Salary Payment</div>
            </div>
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Payment Information</span>
                        <form method="POST" action="paymentProcess.php">
                            <table id="example" class="display responsive-table ">
                                <thead>
                                    <tr>
                                        <th>Employee ID</th>
                                        <th>Position</th>
                                      
                                        <th>Salary</th>
                                        <th>Overtime</th>
                                        <th>Deduction</th>
                                        <th>Net Salary</th>
                                        <th>Payment Month</th>
                                        <th>Payment Method</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo htmlspecialchars($empId); ?></td>
                                        <td><?php echo htmlspecialchars($position); ?></td>
                                      
                                        <td><?php echo htmlspecialchars($salary); ?></td>
                                        <td><?php echo htmlspecialchars($overtime); ?></td>
                                        <td><?php echo htmlspecialchars($deduction); ?></td>
                                        <td><?php echo htmlspecialchars($netsalary); ?></td>
                                        <td>
                                            <select name="month" required>
                                                <option value="">Select Month</option>
                                                <?php for ($i = 1; $i <= 12; $i++) { ?>
                                                    <option value="<?php echo $i; ?>"><?php echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="payment_method" required>
                                                <option value="">Select Payment Method</option>
                                                <option value="Visa">KBZ Pay</option>
                                                <option value="PayPal">AYA Pay</option>
                                                <option value="Bank Transfer">Bank Transfer</option>
                                            </select>
                                            <input type="hidden" name="empId" value="<?php echo htmlspecialchars($empId); ?>">
                                            <input type="hidden" name="position" value="<?php echo htmlspecialchars($position); ?>">
                                           
                                            <input type="hidden" name="salary" value="<?php echo htmlspecialchars($salary); ?>">
                                            <input type="hidden" name="overtime" value="<?php echo htmlspecialchars($overtime); ?>">
                                            <input type="hidden" name="deduction" value="<?php echo htmlspecialchars($deduction); ?>">
                                            <input type="hidden" name="netsalary" value="<?php echo htmlspecialchars($netsalary); ?>">
                                        </td>
                                        <td><button type="submit" class="btn">Confirm Payment</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>
</body>

</html>