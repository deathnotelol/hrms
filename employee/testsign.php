<?php
session_start();
include('includes/config.php');
if(strlen($_SESSION['emplogin'])==0)
{   
    header('location:index.php');
}
else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Attendance Employee</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="Steelcoders" />

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <link href="assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/custom.css" rel="stylesheet" type="text/css" />

    <!-- Additional Styles for the Signature Pad -->
    <style>
        .signature-container {
            margin-top: 20px;
            display: block;
        }
        canvas.signature-pad {
            border: 1px solid black;
            width: 100%; /* Adjust to desired width */
            height: 200px; /* Adjust to desired height */
        }
    </style>
</head>
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
                    <!-- retrieve EmpId start -->
                    <?php 
                        $eid=$_SESSION['eid'];
                        $sql = "SELECT FirstName,LastName,EmpId from  tblemployees where id=:eid";
                        $query = $dbh -> prepare($sql);
                        $query->bindParam(':eid',$eid,PDO::PARAM_STR);
                        $query->execute();
                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                        $cnt=1;
                        if($query->rowCount() > 0)
                        {
                        foreach($results as $result)
                        {               ?>  
                              
                                
                         <?php }} ?>
                     <!-- end -->
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

<!-- Scripts -->
<script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
<script src="assets/plugins/materialize/js/materialize.min.js"></script>
<script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
<script src="assets/js/alpha.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
    var canvas = document.getElementById('signature-pad');
    var signaturePad = new SignaturePad(canvas);

    // Function to resize the canvas correctly, taking device pixel ratio into account
    function resizeCanvas() {
        var ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext('2d').scale(ratio, ratio);
        signaturePad.clear(); // Clears the canvas after resizing
    }

    // Adjust canvas when window resizes
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas(); // Initial resize

    // Clear button to clear the signature pad
    document.getElementById('clear').addEventListener('click', function() {
        signaturePad.clear();
    });

    // On form submit, check if the pad is not empty and capture the signature as Base64
    document.getElementById('signature-form').addEventListener('submit', function(event) {
        if (signaturePad.isEmpty()) {
            alert("Please provide a signature first.");
            event.preventDefault(); // Prevent form submission if no signature
        } else {
            // Save signature data as base64
            var dataURL = signaturePad.toDataURL();
            document.getElementById('signature-data').value = dataURL;
        }
    });
</script>
</body>
</html>
<?php } ?>
