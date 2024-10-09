<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    // Function to fetch all employees with their position details
    function getEmployeesWithDetails($dbh)
    {
        $sql = "
            SELECT e.EmpId, e.FirstName, e.LastName, p.position_name, p.salary, e.id 
            FROM tblemployees e 
            JOIN positions p ON e.PositionID = p.id
        ";
        $stmt = $dbh->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Function to calculate overtime hours
    function calculateOvertime($dbh, $employeeId)
    {
        $sql = "SELECT 
                    MAX(CASE WHEN employee_attendance.shift = 'morning' THEN employee_attendance.createddate END) AS morning_createddate,
                    MAX(CASE WHEN employee_attendance.shift = 'evening' THEN employee_attendance.createddate END) AS evening_createddate
                FROM employee_attendance
                WHERE employee_attendance.eid = :eid";

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':eid', $employeeId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Initialize variables for created dates
        $morning_created = $result['morning_createddate'] ?? null;
        $evening_created = $result['evening_createddate'] ?? null;

        // Calculate overtime if both created dates are available
        if (!empty($morning_created) && !empty($evening_created)) {
            $morning_time = strtotime($morning_created);
            $evening_time = strtotime($evening_created);
            $fixed_morning_time = strtotime('07:00:00'); // Fixed morning time in seconds

            // Calculate the difference in seconds
            $diff = $evening_time - $morning_time;
            $overtime_seconds = $diff - ($fixed_morning_time - strtotime('TODAY')); // Overtime calculation

            if ($overtime_seconds < 0) {
                return 0; // No overtime
            } else {
                // Calculate hours for overtime
                return floor($overtime_seconds / 3600); // Return only hours
            }
        }
        return 0; // If either time is not available
    }

    // Fetch employees for display
    $employees = getEmployeesWithDetails($dbh);

    // Define overtime rate
    $overtime_rate = 50; // Rate per hour
}
?>

<body>
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
                <div class="page-title">Employee Salary Payment</div>
            </div>
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Payment Information</span>

                        <table id="example" class="display responsive-table ">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <!-- <th>Skill</th> -->
                                    <th>Salary</th>
                                    <th>Allowance</th>
                                    <th>Deduction</th>
                                    <th>Net Salary</th>
                                    <th>Overtime</th>
                                    <th>Pay Salary</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($employees as $employee) {
                                    $empid = $employee['id']; // Assign empid here for use in calculations
                                    $overtime_hours = calculateOvertime($dbh, $empid);
                                    $overtime = $overtime_rate * $overtime_hours;
                                    $deduction = 0;
                                    $allowance = 0; // Make sure to define allowance before using it
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($employee['EmpId']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['FirstName'] . " " . $employee['LastName']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['position_name']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['salary']); ?></td>

                                        <td><?php echo $overtime; ?></td> <!-- Use calculated overtime here -->

                                        <?php
                                        $st = 1;
                                        $month = date('m'); // Current month
                                        $year = date('Y'); // Current year

                                        // SQL query to filter by AdminRemarkDate month and year
                                        $sql = "SELECT * FROM tblleaves 
                                        WHERE tblleaves.Status = $st 
                                        AND tblleaves.empid = $empid 
                                        AND MONTH(tblleaves.AdminRemarkDate) = $month 
                                        AND YEAR(tblleaves.AdminRemarkDate) = $year";

                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                        if (count($results) > 0) {
                                            foreach ($results as $result) {
                                                $deduction += $result->Deduction;
                                            }
                                        }
                                        ?>

                                        <td><?php echo "$deduction"; ?></td>

                                        <td>
                                            <?php
                                            $netsalary =  $employee['salary'] +  $overtime - $deduction;
                                            echo "$netsalary";
                                            ?>
                                        </td>
                                        <td><?php echo $overtime_hours; ?>&nbsp;Hr</td> <!-- Show calculated overtime hours -->
                                        <td>
                                            <form method="POST" action="confirmPayment.php">
                                                <input type="hidden" name="empId" value="<?php echo htmlspecialchars($employee['EmpId']); ?>">
                                                <input type="hidden" name="position" value="<?php echo htmlspecialchars($employee['position_name']); ?>">

                                                <input type="hidden" name="salary" value="<?php echo htmlspecialchars($employee['salary']); ?>">
                                                <input type="hidden" name="netsalary" value="<?php echo $netsalary; ?>">
                                                <input type="hidden" name="overtime" value="<?php echo  $overtime; ?>">
                                                <input type="hidden" name="deduction" value="<?php echo $deduction; ?>">
                                                <button type="submit" name="pay" class="btn">Pay Salary</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include('includes/footer.php'); ?>

</body>

</html>