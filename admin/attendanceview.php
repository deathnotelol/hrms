<?php
session_start();
include('includes/config.php');
include('includes/sidebar.php');
include('includes/header.php');
// Query to retrieve detailed attendance information for both morning and evening shifts
$sql = "SELECT 
            tblemployees.id,
            tblemployees.EmpId, 
            tblemployees.FirstName, 
            tblemployees.LastName, 
            employee_attendance.attendance_date, 
            DAYNAME(employee_attendance.attendance_date) AS day_of_week,
            MAX(CASE WHEN employee_attendance.shift = 'morning' THEN employee_attendance.attendance END) AS morning_shift_attendance,
            MAX(CASE WHEN employee_attendance.shift = 'evening' THEN employee_attendance.attendance END) AS evening_shift_attendance,
             MAX(CASE WHEN employee_attendance.shift = 'morning' THEN employee_attendance.createddate END) AS morning_createddate,
            MAX(CASE WHEN employee_attendance.shift = 'evening' THEN employee_attendance.createddate END) AS evening_createddate,
       
            employee_attendance.createddate
        FROM 
            tblemployees
        JOIN 
            employee_attendance 
        ON 
            tblemployees.id = employee_attendance.eid
        GROUP BY 
            tblemployees.EmpId, 
            tblemployees.FirstName, 
            tblemployees.LastName, 
            employee_attendance.attendance_date
        ORDER BY 
            employee_attendance.attendance_date DESC";


$stmt = $dbh->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <main class="mn-inner">
        <div class="row">
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <table id="example" class="display responsive-table ">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>e ID</th>
                                    <th>Name</th>
                                    <th>EmpID</th>
                                    <th>Attendance Date</th>
                                    <th>Day of the Week</th>
                                    <th>Morning Shift Attendance</th>
                                    <th>Evening Shift Attendance</th>
                                    <th>Morning Created Time</th>
                                    <th>Evening Created Time</th>
                                    <th>Working Time</th>
                                    <th>Overtime Hours</th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php
                                foreach ($results as $result) {  ?>
                                    <tr>
                                        <td><?php echo htmlentities($result['id']); ?></td>
                                        <td><?php echo htmlentities($result['FirstName']) . ' ' . htmlentities($result['LastName']); ?></td>
                                        <td><?php echo htmlentities($result['EmpId']); ?></td>
                                        <td><?php echo htmlentities($result['attendance_date']); ?></td>
                                        <td><?php echo htmlentities($result['day_of_week']); ?></td>
                                        <td><?php echo htmlentities($result['morning_shift_attendance']) == 1 ? 'Present' : 'Absent'; ?></td>
                                        <td><?php echo htmlentities($result['evening_shift_attendance']) == 1 ? 'Present' : 'Absent'; ?></td>
                                        <td><?php echo !empty($result['morning_createddate']) ? date('H:i:s', strtotime($result['morning_createddate'])) : 'N/A'; ?></td>
                                        <td><?php echo !empty($result['evening_createddate']) ? date('H:i:s', strtotime($result['evening_createddate'])) : 'N/A'; ?></td>

                                        <!-- Calculate Difference -->
                                        <td>
                                            <?php
                                            // Initialize variables for created dates
                                            $morning_created = $result['morning_createddate'] ?? null;
                                            $evening_created = $result['evening_createddate'] ?? null;

                                            if (!empty($morning_created) && !empty($evening_created)) {
                                                // Convert to timestamps
                                                $morning_time = strtotime($morning_created);
                                                $evening_time = strtotime($evening_created);

                                                // Calculate the difference in seconds
                                                $diff = $evening_time - $morning_time;

                                                // Check if difference is negative (i.e., evening time is before morning time)
                                                if ($diff < 0) {
                                                    echo 'N/A'; // Indicate an error if evening is before morning
                                                } else {
                                                    // Calculate hours, minutes, and seconds
                                                    $hours = floor($diff / 3600);
                                                    $minutes = floor(($diff % 3600) / 60);
                                                    $seconds = $diff % 60;

                                                    // Format and display as HH:MM:SS
                                                    echo sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
                                                }
                                            } else {
                                                echo 'N/A'; // If either time is not available
                                            }
                                            ?>
                                        </td>


                                        <td>
                                            <?php
                                            // Overtime Calculation
                                            $fixed_morning_time = '07:00:00'; // Fixed morning time
                                            $fixed_morning_seconds = strtotime($fixed_morning_time) - strtotime('TODAY'); // Convert to seconds

                                            if (!empty($morning_created) && !empty($evening_created)) {
                                                // Use previously calculated difference
                                                $overtime_seconds = $diff - $fixed_morning_seconds;

                                                // Check if overtime is negative
                                                if ($overtime_seconds < 0) {
                                                    echo 'No Overtime'; // No overtime
                                                } else {
                                                    // Calculate hours, minutes, and seconds for overtime
                                                    $overtime_hours = floor($overtime_seconds / 3600);
                                                    $overtime_minutes = floor(($overtime_seconds % 3600) / 60);
                                                    $overtime_seconds_remainder = $overtime_seconds % 60;

                                                    // Format and display overtime as HH:MM:SS
                                                    echo sprintf("%02d:%02d:%02d", $overtime_hours, $overtime_minutes, $overtime_seconds_remainder) . 'Hr';
                                                }
                                            } else {
                                                echo 'N/A'; // If either time is not available
                                            }
                                            ?>
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