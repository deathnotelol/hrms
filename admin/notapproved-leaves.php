<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

?>

    <body>
        <?php include('includes/header.php'); ?>

        <?php include('includes/sidebar.php'); ?>
        <main class="mn-inner">
            <div class="row">
                <div class="col s12">
                    <div class="page-title"> Not Approved Leave History</div>
                </div>

                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title">Not Approved Leave History</span>
                            <?php if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div><?php } ?>
                            <table id="example" class="display responsive-table ">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th width="200">Employe Name</th>
                                        <th width="120">Leave Type</th>

                                        <th width="180">Posting Date</th>
                                        <th>Status</th>
                                        <th align="center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $status = 2;
                                    $sql = "SELECT tblleaves.id as lid,tblemployees.FirstName,tblemployees.LastName,tblemployees.EmpId,tblemployees.id,tblleaves.LeaveType,tblleaves.PostingDate,tblleaves.Status from tblleaves join tblemployees on tblleaves.empid=tblemployees.id where tblleaves.Status=:status order by lid desc";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':status', $status, PDO::PARAM_STR);
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
        </main>

        </div>
        <div class="left-sidebar-hover"></div>

        <?php include('includes/footer.php');?>

    </body>

    </html>
<?php } ?>