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
        <title>Employee | Leave History</title>

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
                    <div class="page-title">Leave History</div>
                </div>

                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title">Leave History</span>
                            <?php if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div><?php } ?>
                            <table id="example" class="display responsive-table ">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th width="120">Leave Type</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th width="120">Posting Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $eid = $_SESSION['eid'];
                                    $sql = "SELECT tblleaves.id as lid ,LeaveType,ToDate,FromDate,Description,PostingDate,AdminRemarkDate,AdminRemark,Status from tblleaves where empid=:eid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {               ?>
                                            <tr>
                                                <td> <?php echo htmlentities($cnt); ?></td>
                                                <td><?php echo htmlentities($result->LeaveType); ?></td>
                                                <td><?php echo htmlentities($result->FromDate); ?></td>
                                                <td><?php echo htmlentities($result->ToDate); ?></td>
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
                                                <td>
                                                    <a href="leave-details.php?leaveid=<?php echo htmlentities($result->lid); ?>" class="waves-effect waves-light btn blue m-b-xs"> View Details</a>
                                                </td>
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

        <?php include('includes/footer.php')?>
    </body>

    </html>
<?php } ?>