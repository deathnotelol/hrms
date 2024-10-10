<?php
session_start();
ob_start(); // Enable output buffering
include('includes/config.php');

if (strlen($_SESSION['emplogin']) == 0) {   
    header('location:index.php');
    exit();
} else {
?>


<body>
<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>
<main class="mn-inner">
    <div class="row">
        <div class="col s12">
            <div class="page-title">Employee Attendance</div>
        </div>
        <div class="col s12 m12 l12">
            <div class="card">
                <div class="card-content">
                    <div class="signature-container" id="signature-container">
                        <h4>Sign For Attendance</h4>
                        <!-- Retrieve EmpId -->
                        <?php 
                        $eid = $_SESSION['eid'];
                        $sql = "SELECT FirstName, LastName, EmpId FROM tblemployees WHERE id = :eid";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                        $query->execute();
                        $result = $query->fetch(PDO::FETCH_OBJ);
                        ?>
                        <!-- End -->
                        <form method="post" id="signature-form" action="save_signature.php" enctype="multipart/form-data">
                            <div>
                                <canvas id="signature-pad" class="signature-pad"></canvas>
                            </div>
                            <!-- Hidden field to store signature data -->
                            <input type="hidden" id="signature-data" name="signature-data">
                            <input type="hidden" name="empId" value="<?php echo htmlentities($result->EmpId); ?>">
                            <button type="submit" name="submit">Submit</button>
                            <button type="button" id="clear">Clear Signature</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<div class="left-sidebar-hover"></div>
<?php include('includes/footer.php') ?>
</body>
</html>
<?php
ob_end_flush(); // Flush output buffer
}
?>
