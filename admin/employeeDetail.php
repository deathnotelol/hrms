<?php
session_start();
// error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    $eid = intval($_GET['id']);
    echo $eid;
}

?>

<body>
    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>
    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
                <div class="page-title" style="font-size:24px;">Employee Details</div>
            </div>

            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Employee Info</span>
                        <table id="example" class="display responsive-table ">
                            <tbody>
                                <?php
                                $sql = "SELECT tblemployees.*, positions.position_name as positionName, positions.salary as salary  FROM tblemployees JOIN positions ON tblemployees.PositionID = positions.id
                                WHERE tblemployees.id = $eid";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetch(PDO::FETCH_ASSOC);
                                // var_dump($results);
                                ?>
                                <tr>
                                <td><img src="<?php echo htmlentities($results['ProfileImage']); ?>" alt="" width="100px" height="100px"> </td>
                                </tr>
                                <tr>
                                    <td style="font-size:16px;"><b>Emp Id :</b></td>
                                    <td><?php echo htmlentities($results['EmpId']); ?></td>
                                    <td style="font-size:16px;"> <b>Employe Name :</b></td>
                                    <td><a href="editemployee.php?empid=<?php echo htmlentities($results['id']); ?>" target="_blank">
                                            <?php echo htmlentities($results['FirstName'] . " " . $results['LastName']); ?></a></td>
                                    <td style="font-size:16px;"><b>Gender :</b></td>
                                    <td><?php echo htmlentities($results['Gender']); ?></td>
                                    <td style="font-size:16px;"><b>Date Of Birth :</b></td>
                                    <td><?php echo htmlentities($results['Dob']); ?></td>
                                   
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>

                                <tr>
                                    <td style="font-size:16px;"><b>Dept Name :</b></td>
                                    <td><?php echo htmlentities($results['Department']); ?></td>
                                    <td style="font-size:16px;"><b>Postion Name :</b></td>
                                    <td><?php echo htmlentities($results['positionName']); ?></td>
                                    <td style="font-size:16px;"><b>Email :</b></td>
                                    <td><?php echo htmlentities($results['EmailId']); ?></td>
                                    <td style="font-size:16px;"><b>Address :</b></td>
                                    <td><?php echo htmlentities($results['Address']); ?></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="font-size:16px;"><b>Phone :</b></td>
                                    <td><?php echo htmlentities($results['Phonenumber']); ?></td>
                                    <td style="font-size:16px;"><b>Salary :</b></td>
                                    <td><?php echo htmlentities($results['salary']); ?></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <?php
                                    $sql = "SELECT LeaveType, SUM(duration) as totalDay FROM tblleaves WHERE tblleaves.empid = $eid GROUP BY LeaveType";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $leaves = $query->fetchAll(PDO::FETCH_OBJ);

                                    foreach ($leaves as $leave) {
                                    ?>

                                        <td style="font-size:16px;"><b><?= $leave->LeaveType; ?></b></td>
                                        <td><?= $leave->totalDay; ?></td>
                                    <?php } ?>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    </div>
    <div class="left-sidebar-hover"></div>
    <?php include('includes/footer.php'); ?>


</body>

</html>