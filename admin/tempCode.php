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
            SELECT e.EmpId, e.FirstName, e.LastName, p.position_name, p.skill_level, p.salary 
            FROM tblemployees e 
            JOIN positions p ON e.PositionID = p.id
        ";
        $stmt = $dbh->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch employees for display
    $employees = getEmployeesWithDetails($dbh);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title -->
    <title>Admin | Manage Salary</title>
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
</head>

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
                                    <th>Skill</th>
                                    <th>Salary</th>
                                    <th>Pay Salary</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($employees as $employee) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($employee['EmpId']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['FirstName'] . " " . $employee['LastName']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['position_name']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['skill_level']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['salary']); ?></td>
                                        <td>
                                            <form method="POST" action="confirmPayment.php">
                                                <input type="hidden" name="empId" value="<?php echo htmlspecialchars($employee['EmpId']); ?>">
                                                <input type="hidden" name="position" value="<?php echo htmlspecialchars($employee['position_name']); ?>">
                                                <input type="hidden" name="skill" value="<?php echo htmlspecialchars($employee['skill_level']); ?>">
                                                <input type="hidden" name="salary" value="<?php echo htmlspecialchars($employee['salary']); ?>">
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


<!-- Leave Detail -->
<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    // code for update the read notification status
    $isread = 1;
    $did = intval($_GET['leaveid']);
    date_default_timezone_set('Asia/Yangon');
    $admremarkdate = date('Y-m-d G:i:s ', strtotime("now"));
    $sql = "update tblleaves set IsRead=:isread where id=:did";
    $query = $dbh->prepare($sql);
    $query->bindParam(':isread', $isread, PDO::PARAM_STR);
    $query->bindParam(':did', $did, PDO::PARAM_STR);
    $query->execute();

    // code for action taken on leave
    if (isset($_POST['update'])) {
        $did = intval($_GET['leaveid']);
        $description = $_POST['description'];
        $status = $_POST['status'];
        date_default_timezone_set('Asia/Yangon');
        $admremarkdate = date('Y-m-d G:i:s ', strtotime("now"));
        $sql = "update tblleaves set AdminRemark=:description,Status=:status,AdminRemarkDate=:admremarkdate where id=:did";
        $query = $dbh->prepare($sql);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':admremarkdate', $admremarkdate, PDO::PARAM_STR);
        $query->bindParam(':did', $did, PDO::PARAM_STR);
        $query->execute();
        $msg = "Leave updated Successfully";
    }



?>
    <!DOCTYPE html>
    <html lang="en">

    <head>

        <!-- Title -->
        <title>Admin | Leave Details </title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />

        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
        <link href="../assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

        <link href="../assets/plugins/google-code-prettify/prettify.css" rel="stylesheet" type="text/css" />
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
                    <div class="page-title" style="font-size:24px;">Leave Details</div>
                </div>

                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title">Leave Details</span>
                            <?php if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div><?php } ?>
                            <table id="example" class="display responsive-table ">


                                <tbody>
                                    <?php
                                    $lid = intval($_GET['leaveid']);
                                    $sql = "SELECT tblleaves.id as lid,tblemployees.FirstName,tblemployees.LastName,tblemployees.EmpId,tblemployees.id,tblemployees.Gender,tblemployees.Phonenumber,tblemployees.EmailId,tblleaves.LeaveType,tblleaves.ToDate,tblleaves.FromDate,tblleaves.Description,tblleaves.PostingDate,tblleaves.Status,tblleaves.AdminRemark,tblleaves.AdminRemarkDate from tblleaves join tblemployees on tblleaves.empid=tblemployees.id where tblleaves.id=:lid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':lid', $lid, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {
                                    ?>

                                            <tr>
                                                <td style="font-size:16px;"> <b>Employe Name :</b></td>
                                                <td><a href="editemployee.php?empid=<?php echo htmlentities($result->id); ?>" target="_blank">
                                                        <?php echo htmlentities($result->FirstName . " " . $result->LastName); ?></a></td>
                                                <td style="font-size:16px;"><b>Emp Id :</b></td>
                                                <td><?php echo htmlentities($result->EmpId); ?></td>
                                                <td style="font-size:16px;"><b>Gender :</b></td>
                                                <td><?php echo htmlentities($result->Gender); ?></td>
                                            </tr>

                                            <tr>
                                                <td style="font-size:16px;"><b>Emp Email id :</b></td>
                                                <td><?php echo htmlentities($result->EmailId); ?></td>
                                                <td style="font-size:16px;"><b>Emp Contact No. :</b></td>
                                                <td><?php echo htmlentities($result->Phonenumber); ?></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>

                                            <tr>
                                                <td style="font-size:16px;"><b>Leave Type :</b></td>
                                                <td><?php echo htmlentities($result->LeaveType); ?></td>
                                                <td style="font-size:16px;"><b>Leave Date . :</b></td>
                                                <td>From <?php echo htmlentities($result->FromDate); ?> to <?php echo htmlentities($result->ToDate); ?></td>
                                                <td style="font-size:16px;"><b>Posting Date</b></td>
                                                <td><?php echo htmlentities($result->PostingDate); ?></td>
                                            </tr>

                                            <tr>
                                                <td style="font-size:16px;"><b>Employe Leave Description : </b></td>
                                                <td colspan="5"><?php echo htmlentities($result->Description); ?></td>

                                            </tr>

                                            <tr>
                                                <td style="font-size:16px;"><b>leave Status :</b></td>
                                                <td colspan="5"><?php $stats = $result->Status;
                                                                if ($stats == 1) {
                                                                ?>
                                                        <span style="color: green">Approved</span>
                                                    <?php }
                                                                if ($stats == 2) { ?>
                                                        <span style="color: red">Not Approved</span>
                                                    <?php }
                                                                if ($stats == 0) { ?>
                                                        <span style="color: blue">waiting for approval</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="font-size:16px;"><b>Admin Remark: </b></td>
                                                <td colspan="5"><?php
                                                                if ($result->AdminRemark == "") {
                                                                    echo "waiting for Approval";
                                                                } else {
                                                                    echo htmlentities($result->AdminRemark);
                                                                }
                                                                ?></td>
                                            </tr>

                                            <tr>
                                                <td style="font-size:16px;"><b>Admin Action taken date : </b></td>
                                                <td colspan="5"><?php
                                                                if ($result->AdminRemarkDate == "") {
                                                                    echo "NA";
                                                                } else {
                                                                    echo htmlentities($result->AdminRemarkDate);
                                                                }
                                                                ?></td>
                                            </tr>
                                            <?php
                                            if ($stats == 0) {

                                            ?>
                                                <tr>
                                                    <td colspan="5">
                                                        <a class="modal-trigger waves-effect waves-light btn" href="#modal1">Take&nbsp;Action</a>
                                                        <form name="adminaction" method="post">
                                                            <div id="modal1" class="modal modal-fixed-footer" style="height: 60%">
                                                                <div class="modal-content" style="width:90%">
                                                                    <h4>Leave take action</h4>
                                                                    <select class="browser-default" name="status" required="">
                                                                        <option value="">Choose your option</option>
                                                                        <option value="1">Approved</option>
                                                                        <option value="2">Not Approved</option>
                                                                    </select></p>
                                                                    <p><textarea id="textarea1" name="description" class="materialize-textarea" name="description" placeholder="Description" length="500" maxlength="500" required></textarea></p>
                                                                </div>
                                                                <div class="modal-footer" style="width:90%">
                                                                    <input type="submit" class="waves-effect waves-light btn blue m-b-xs" name="update" value="Submit">
                                                                </div>

                                                            </div>

                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </form>
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
        <script src="assets/js/pages/ui-modals.js"></script>
        <script src="assets/plugins/google-code-prettify/prettify.js"></script>

    </body>

    </html>
<?php } ?>

<!-- Function for Deduction -->

function calculateLeaveDays($fromDate, $toDate) {
    // Convert dates to DateTime objects
    $from = new DateTime($fromDate);
    $to = new DateTime($toDate);
    
    // Calculate the difference in days
    $interval = $from->diff($to);
    
    // Return the number of days (include both start and end date)
    return $interval->days + 1; // +1 to count both days
}

function calculateSalaryDeduction($leaveType, $leaveDays, $basicSalary) {
    $deduction = 0;
    
    // Define deduction percentages for different leave types
    $deductionRates = [
        'Earned Leave' => 0.02,  // 2% deduction per leave day
        'Sick Leave' => 0.01,    // 1% deduction per leave day
        'Unpaid Leave' => 0.05   // 5% deduction per leave day
    ];

    // Check if leave type has a defined deduction rate
    if (isset($deductionRates[$leaveType])) {
        // Calculate the deduction: (percentage * leave days * basic salary)
        $deduction = $deductionRates[$leaveType] * $leaveDays * $basicSalary;
    }

    return $deduction;
}

<!-- Payment Process  -->
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

        // Get the current date and time as the payment date
        $paymentDate = date('Y-m-d H:i:s'); // Including both date and time

        // Ensure required fields are not empty
        if ($empId && $paymentMonth && $paymentMethod && $salary) {
            try {
                // Insert the data into the tblsalaries table
                $sql = "INSERT INTO tblsalaries (EmpId, PaymentMonth, PaymentDate, PaymentMethod, Salary) 
                        VALUES (:empId, :paymentMonth, :paymentDate, :paymentMethod, :salary)";
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':empId', $empId, PDO::PARAM_STR);
                $stmt->bindParam(':paymentMonth', $paymentMonth, PDO::PARAM_STR);
                $stmt->bindParam(':paymentDate', $paymentDate, PDO::PARAM_STR);
                $stmt->bindParam(':paymentMethod', $paymentMethod, PDO::PARAM_STR);
                $stmt->bindParam(':salary', $salary, PDO::PARAM_STR);

                $stmt->execute();

                // If insertion is successful, redirect to the salary history page
                // $_SESSION['msg'] = "Payment has been processed successfully!";
                header('location:salaryHistory.php');
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            // Handle missing required fields
            echo "Error: Missing required fields.";
        }
    }
}
?>




