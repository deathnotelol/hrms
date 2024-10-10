<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['emplogin']) == 0) {
    header('location:index.php');
} else {
?>

   

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


        <?php include('includes/footer.php'); ?>
    </body>
    </html>
<?php } ?>