<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['emplogin']) == 0) {
    header('location:index.php');
} else {
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>

        <!-- Title -->
        <title>Employee | Dashboard</title>

    </head>

    <body>
        <?php include('includes/header.php'); ?>

        <?php include('includes/sidebar.php'); ?>

        <main class="mn-inner">
            <div class="">
                <div class="row no-m-t no-m-b">




                    <a href="leavehistory.php" target="blank">
                        <div class="col s12 m12 l4">
                            <div class="card stats-card" style="background-color: #7C00FE;">
                                <div class="card-content">
                                    <span class="card-title">Total Leaves</span>
                                    <?php $eid = $_SESSION['eid'];
                                    $sql = "SELECT id from  tblleaves where empid ='$eid'";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $totalleaves = $query->rowCount();
                                    ?>
                                    <span class="stats-counter"><span class="counter"><?php echo htmlentities($totalleaves); ?></span></span>

                                </div>
                                <div class="progress stats-card-progress">
                                    <div class="success" style="width: 70%"></div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="leavehistory.php" target="blank">
                        <div class="col s12 m12 l4">
                            <div class="card stats-card" style="background-color: #C7253E;">
                                <div class="card-content">
                                    <span class="card-title">Approved Leaves</span>
                                    <?php
                                    $sql = "SELECT id from  tblleaves where Status=1 and empid ='$eid'";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $approvedleaves = $query->rowCount();
                                    ?>
                                    <span class="stats-counter"><span class="counter"><?php echo htmlentities($approvedleaves); ?></span></span>

                                </div>
                                <div class="progress stats-card-progress">
                                    <div class="success" style="width: 70%"></div>
                                </div>
                            </div>
                        </div>
                    </a>



                    <a href="leavehistory.php" target="blank">
                        <div class="col s12 m12 l4">
                            <div class="card stats-card" style="background-color: #6EC207;">
                                <div class="card-content">
                                    <span class="card-title">Earned Total Salary</span>
                                    <?php
                                    $sql = "SELECT tblemployees.*,  sum(tblsalaries.NetSalary) as totalSalary 
                                    from tblemployees  
                                    join tblsalaries on tblsalaries.EmpId=tblemployees.EmpId where tblemployees.id=$eid";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetch(PDO::FETCH_ASSOC);
                                    //var_dump($results);
                                    //$approvedleaves = $query->rowCount();
                                    ?>
                                    <span class="stats-counter"><span class="counter"><?php echo htmlentities($results['totalSalary']); ?></span></span>

                                </div>
                                <div class="progress stats-card-progress">
                                    <div class="success" style="width: 70%"></div>
                                </div>
                            </div>
                        </div>
                    </a>

                </div>

                <div class="row no-m-t no-m-b">
                    <div class="col s15 m12 l12">
                        <div class="card invoices-card">
                            <div class="card-content">

                                <span class="card-title">Latest Salary</span>
                                <table id="example" class="display responsive-table ">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employe Name</th>
                                            <th>Salary </th>
                                            <th>Allowance</th>
                                            <th>Deduction</th>
                                            <th>Net Salary</th>
                                            <th>Payment Month</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php 
                                        $sql = "SELECT emp.FirstName, emp.LastName, emp.EmpId, sal.Salary, sal.Allowance, sal.Deduction, sal.NetSalary, sal.PaymentMonth
                                        FROM tblsalaries sal
                                        JOIN tblemployees emp ON sal.EmpId = emp.EmpId where emp.id = $eid order by sal.PaymentDate desc limit 1";
                                        
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);


                                        // var_dump($results);
                                        $cnt = 1;
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) {
                                        ?>

                                                <tr>
                                                    <td> <b><?php echo htmlentities($cnt); ?></b></td>
                                                    <td> <?php echo htmlentities($result->FirstName . " " . $result->LastName); ?>(<?php echo htmlentities($result->EmpId); ?>)</td>
                                                    <td><?php echo htmlentities($result->Salary); ?></td>
                                                    <td><?php echo htmlentities($result->Allowance); ?></td>
                                                    <td><?php echo htmlentities($result->Deduction); ?></td>
                                                    <td><?php echo htmlentities($result->NetSalary); ?></td>
                                                    <td><?php echo htmlentities($result->PaymentMonth); ?></td>

                                                    <td><a href="salaryDetail.php?empid=<?php echo htmlentities($result->EmpId); ?>" class="waves-effect waves-light btn blue m-b-xs"> View Details</a></td>
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

                <div class="row no-m-t no-m-b">
                    <div class="col s15 m12 l12">
                        <div class="card invoices-card">
                            <div class="card-content">

                                <span class="card-title">Latest Leave Applications</span>
                                <table id="example" class="display responsive-table ">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Employe Name</th>
                                            <th>Leave Type</th>
                                            <th>Posting Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $sql = "SELECT tblleaves.id as lid,tblemployees.FirstName,tblemployees.LastName,tblemployees.EmpId,tblemployees.id,tblleaves.LeaveType,tblleaves.PostingDate,tblleaves.Status from tblleaves join tblemployees on tblleaves.empid=tblemployees.id where tblleaves.empid='$eid' order by lid desc limit 6";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) {
                                        ?>

                                                <tr>
                                                    <td> <b><?php echo htmlentities($cnt); ?></b></td>
                                                    <td><a href="editemployee.php?empid=<?php echo htmlentities($result->id); ?>" target="_blank"><?php echo htmlentities($result->FirstName . " " . $result->LastName); ?>(<?php echo htmlentities($result->EmpId); ?>)</a></td>
                                                    <td><?php echo htmlentities($result->LeaveType); ?></td>
                                                    <td><?php echo htmlentities($result->PostingDate); ?></td>
                                                    <td><?php $stats = $result->Status;
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

                                                    <td><a href="leave-details.php?leaveid=<?php echo htmlentities($result->lid); ?>" class="waves-effect waves-light btn blue m-b-xs"> View Details</a></td>
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
            </div>

        </main>

        </div>

        <?php include ('includes/footer.php'); ?>
    </body>
    
    </html>
    <?php } ?>