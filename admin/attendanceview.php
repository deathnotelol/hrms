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

    <main class="mn-inner">
        <div class="row">
        <div class="col s12">
                    <div class="page-title"> Attendance View</div>
                </div>
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <table id="example" class="display responsive-table ">
                            <thead class="bg-primary text-white">
                                <tr>
                                    
                                    <th>Name</th>
                                    <th>EmpID</th>
                                    <th> Date</th>
                                    <th>Day</th>
                                    <th>Morning </th>
                                    <th>Evening </th>
                                    <th>In Time</th>
                                    <th>Out Time</th>
                                    <th>Office time</th>
                                    <th>Overtime Hours</th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php
                                foreach ($results as $result) {  ?>
                                    <tr>
                                        <!--  -->
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

    <?php include('includes/footer.php'); ?>

    </body>

</html>