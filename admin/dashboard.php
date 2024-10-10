<?php
session_start();
// error_reporting(0);
include('includes/config.php');

// Redirect if not logged in
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

// Fetch counts based on table and optional conditions
function fetchCount($dbh, $table, $condition = null)
{
    $sql = "SELECT COUNT(id) AS count FROM $table";
    if ($condition) {
        $sql .= " WHERE $condition";
    }
    $query = $dbh->prepare($sql);
    $query->execute();
    return $query->fetchColumn();
}



// Fetch latest leave applications
function fetchLatestLeaveApplications($dbh, $limit = 6)
{
    $sql = "SELECT tblleaves.id AS lid, tblemployees.FirstName, tblemployees.LastName, 
                   tblemployees.EmpId, tblemployees.id AS empid, tblleaves.LeaveType, tblleaves.PostingDate, tblleaves.Status, positions.position_name 
            FROM tblleaves 
            JOIN tblemployees ON tblleaves.empid = tblemployees.id 
            JOIN positions ON tblemployees.PositionID = positions.id 
            ORDER BY tblleaves.id DESC LIMIT :limit";
    $query = $dbh->prepare($sql);
    $query->bindParam(':limit', $limit, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_OBJ);
}



// Constants for leave statuses
define('STATUS_APPROVED', 1);
define('STATUS_PENDING', 0);

// Fetch various counts
$empcount = fetchCount($dbh, 'tblemployees');
$position = fetchCount($dbh, 'positions');
$dptcount = fetchCount($dbh, 'tbldepartments');
$leavtypcount = fetchCount($dbh, 'tblleavetype');
// $totalleaves = todayLeave($dbh, 'tblleaves', $day);
$approvedleaves = fetchCount($dbh, 'tblleaves', 'Status = ' . STATUS_APPROVED);
$newleaveapplications = fetchCount($dbh, 'tblleaves', 'Status = ' . STATUS_PENDING);

// Fetch latest leave applications
$latestLeaveApplications = fetchLatestLeaveApplications($dbh);


?>



<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<main class="mn-inner">
    <div class="row no-m-t no-m-b">
        <!-- Total Employee Card -->
        <a href="manageemployee.php" target="blank">
            <div class="col s12 m12 l4">
                <div class="card stats-card" style="background-color: #0D92F4;">
                    <div class="card-content">
                        <span class="card-title">Total Employee</span>
                        <span class="stats-counter">
                            <span class="counter"><?php echo htmlentities($empcount); ?></span>
                        </span>
                    </div>
                    <div class="progress stats-card-progress">
                        <div class="determinate" style="width: 70%"></div>
                    </div>
                </div>
            </div>
        </a>

        <!-- Total Departments Card -->
        <a href="managedepartments.php" target="blank">
            <div class="col s12 m12 l4">
                <div class="card stats-card" style="background-color: #86D293;">
                    <div class="card-content">
                        <span class="card-title">Departments</span>
                        <span class="stats-counter">
                            <span class="counter"><?php echo htmlentities($dptcount); ?></span>
                        </span>
                    </div>
                    <div class="progress stats-card-progress">
                        <div class="determinate" style="width: 70%"></div>
                    </div>
                </div>
            </div>
        </a>

        <!-- Leave Type Card -->
        <a href="manageleavetype.php" target="blank">
            <div class="col s12 m12 l4">
                <div class="card stats-card" style="background-color: #FFEB00;">
                    <div class="card-content">
                        <span class="card-title">Leave Type</span>
                        <span class="stats-counter">
                            <span class="counter"><?php echo htmlentities($leavtypcount); ?></span>
                        </span>
                    </div>
                    <div class="progress stats-card-progress">
                        <div class="determinate" style="width: 70%"></div>
                    </div>
                </div>
            </div>
        </a>

        <!-- Total Leaves Card -->
        <a href="leaves.php" target="blank">
            <div class="col s12 m12 l4">
                <div class="card stats-card" style="background-color: #6EC207;">
                    <div class="card-content">
                        <span class="card-title">Today Leaves</span>
                        <span class="stats-counter">
                            <span class="counter">
                                <?php
                                $date = new DateTime();
                                $day = $date->format("Y-m-d");  // Current date in Y-m-d format

                                $sql = "SELECT COUNT(*) as totalLeaves FROM tblleaves WHERE PostingDate = :day";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':day', $day, PDO::PARAM_STR); // Binding the date parameter
                                $query->execute();
                                $result = $query->fetch(PDO::FETCH_ASSOC);

                                // Display the result
                                echo $result['totalLeaves'];
                                ?>
                            </span>
                        </span>

                    </div>
                    <div class="progress stats-card-progress">
                        <div class="success" style="width: 70%"></div>
                    </div>
                </div>
            </div>
        </a>

        <!-- Approved Leaves Card -->
        <a href="managePosition.php" target="blank">
            <div class="col s12 m12 l4">
                <div class="card stats-card" style="background-color: #C7253E;">
                    <div class="card-content">
                        <span class="card-title">Positions</span>
                        <span class="stats-counter">
                            <span class="counter"><?php echo htmlentities($position); ?></span>
                        </span>
                    </div>
                    <div class="progress stats-card-progress">
                        <div class="success" style="width: 70%"></div>
                    </div>
                </div>
            </div>
        </a>

        <!-- New Leave Applications Card -->
        <a href="salaryHistory.php" target="blank">
            <div class="col s12 m12 l4">
                <div class="card stats-card" style="background-color: #7C00FE;">
                    <div class="card-content">
                        <span class="card-title">Total Salary of Lasted Month</span>
                        <span class="stats-counter">
                            <span class="counter">
                                <?php
                                $month = date('F', strtotime('first day of last month'));
                                $sql = "SELECT SUM(NetSalary) as totalSalary FROM tblsalaries WHERE PaymentMonth = :month";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':month', $month);
                                $query->execute();
                                $results = $query->fetch();

                                echo ($results['totalSalary']);
                                ?></span>
                        </span>
                    </div>
                    <div class="progress stats-card-progress">
                        <div class="success" style="width: 70%"></div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Latest Leave Applications -->
    <div class="row no-m-t no-m-b">
        <div class="col s15 m12 l12">
            <div class="card invoices-card">
                <div class="card-content">
                    <span class="card-title">Latest Leave Applications</span>
                    <table id="example" class="display responsive-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>Position Name</th>
                                <th>Leave Type</th>
                                <th>Posting Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cnt = 1;
                            if (count($latestLeaveApplications) > 0) {
                                foreach ($latestLeaveApplications as $result) { ?>
                                    <tr>
                                        <td><?php echo htmlentities($cnt); ?></td>
                                        <td><a href="editemployee.php?empid=<?php echo htmlentities($result->empid); ?>" target="_blank">
                                                <?php echo htmlentities($result->FirstName . " " . $result->LastName); ?> (<?php echo htmlentities($result->EmpId); ?>)</a>
                                        </td>
                                        <td><?php echo htmlentities($result->position_name); ?></td>
                                        <td><?php echo htmlentities($result->LeaveType); ?></td>
                                        <td><?php echo htmlentities($result->PostingDate); ?></td>
                                        <td><?php
                                            $status = $result->Status;
                                            if ($status == 1) echo '<span style="color: green">Approved</span>';
                                            else if ($status == 2) echo '<span style="color: red">Not Approved</span>';
                                            else echo '<span style="color: blue">Pending</span>';
                                            ?>
                                        </td>
                                        <td><a href="leave-details.php?leaveid=<?php echo htmlentities($result->lid); ?>" class="waves-effect waves-light btn blue m-b-xs" target="_blank"> View Details</a></td>
                                    </tr>
                            <?php
                                    $cnt++;
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include('includes/footer.php') ?>
</body>

</html>