<?php
session_start();
error_reporting(0);
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
                   tblemployees.EmpId, tblemployees.id AS empid, tblleaves.LeaveType, tblleaves.PostingDate, tblleaves.Status 
            FROM tblleaves 
            JOIN tblemployees ON tblleaves.empid = tblemployees.id 
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
$dptcount = fetchCount($dbh, 'tbldepartments');
$leavtypcount = fetchCount($dbh, 'tblleavetype');
$totalleaves = fetchCount($dbh, 'tblleaves');
$approvedleaves = fetchCount($dbh, 'tblleaves', 'Status = ' . STATUS_APPROVED);
$newleaveapplications = fetchCount($dbh, 'tblleaves', 'Status = ' . STATUS_PENDING);

// Fetch latest leave applications
$latestLeaveApplications = fetchLatestLeaveApplications($dbh);

?>

<body>
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <main class="mn-inner">
        <div class="row no-m-t no-m-b">
            <!-- Total Employee Card -->
            <a href="manageemployee.php" target="blank">
                <div class="col s12 m12 l4">
                    <div class="card stats-card">
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
                    <div class="card stats-card">
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
                    <div class="card stats-card">
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
                    <div class="card stats-card">
                        <div class="card-content">
                            <span class="card-title">Total Leaves</span>
                            <span class="stats-counter">
                                <span class="counter"><?php echo htmlentities($totalleaves); ?></span>
                            </span>
                        </div>
                        <div class="progress stats-card-progress">
                            <div class="success" style="width: 70%"></div>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Approved Leaves Card -->
            <a href="approvedleave-history.php" target="blank">
                <div class="col s12 m12 l4">
                    <div class="card stats-card">
                        <div class="card-content">
                            <span class="card-title">Approved Leaves</span>
                            <span class="stats-counter">
                                <span class="counter"><?php echo htmlentities($approvedleaves); ?></span>
                            </span>
                        </div>
                        <div class="progress stats-card-progress">
                            <div class="success" style="width: 70%"></div>
                        </div>
                    </div>
                </div>
            </a>

            <!-- New Leave Applications Card -->
            <a href="pending-leavehistory.php" target="blank">
                <div class="col s12 m12 l4">
                    <div class="card stats-card">
                        <div class="card-content">
                            <span class="card-title">New Leaves Applications</span>
                            <span class="stats-counter">
                                <span class="counter"><?php echo htmlentities($newleaveapplications); ?></span>
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
                                    <th width="200">Employee Name</th>
                                    <th width="120">Leave Type</th>
                                    <th width="180">Posting Date</th>
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