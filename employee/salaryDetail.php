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

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="
        " />

        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css" />
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/plugins/metrojs/MetroJs.min.css" rel="stylesheet">
        <link href="assets/plugins/weather-icons-master/css/weather-icons.min.css" rel="stylesheet">
        <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
        <link href="assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">


        <!-- Theme Styles -->
        <link href="assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/custom.css" rel="stylesheet" type="text/css" />




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
                                        <th>#</th>
                                        <th>Employe Name</th>
                                        <th>Salary </th>
                                        <th>Allowance</th>
                                        <th>Deduction</th>
                                        <th>Net Salary</th>
                                        <th>Payment Month</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $sql = "SELECT emp.FirstName, emp.LastName, emp.EmpId, sal.Salary, sal.Allowance, sal.Deduction, sal.NetSalary, sal.PaymentMonth
                                        FROM tblsalaries sal
                                        JOIN tblemployees emp ON sal.EmpId = emp.EmpId where emp.id = $eid order by sal.PaymentDate desc";

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


        <!-- Javascripts -->
       
        <script src="assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
        <script src="assets/plugins/chart.js/chart.min.js"></script>
        <script src="assets/plugins/flot/jquery.flot.min.js"></script>
        <script src="assets/plugins/flot/jquery.flot.time.min.js"></script>
        <script src="assets/plugins/flot/jquery.flot.symbol.min.js"></script>
        <script src="assets/plugins/flot/jquery.flot.resize.min.js"></script>
        <script src="assets/plugins/flot/jquery.flot.tooltip.min.js"></script>
        <script src="assets/plugins/curvedlines/curvedLines.js"></script>
        <script src="assets/plugins/peity/jquery.peity.min.js"></script>
        <script src="assets/js/alpha.min.js"></script>
        <script src="assets/js/pages/dashboard.js"></script>
        <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
        <script src="assets/js/alpha.min.js"></script>
        <script src="assets/js/pages/table-data.js"></script>
    </body>
    </html>
<?php } ?>