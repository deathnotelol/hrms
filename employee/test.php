<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['emplogin']) == 0) {
    header('location:index.php');
    exit;
}

// Get the employee ID from session
$eid = $_SESSION['eid'];

// Fetch attendance records for the logged-in employee
$sql = "SELECT es.EmpId, es.signature, ea.attendance_date, ea.day_of_week, ea.shift, ea.attendance
        FROM employee_signatures es
        INNER JOIN employee_attendance ea ON es.eid = ea.eid
        WHERE ea.eid = :eid
        ORDER BY ea.attendance_date DESC, ea.shift";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':eid', $eid, PDO::PARAM_INT);
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if records were found
$groupedRecords = [];
if (!empty($records)) {
    foreach ($records as $record) {
        $date = $record['attendance_date'];
        $shift = strtolower($record['shift']); // 'morning' or 'evening'

        // Initialize the date array if it doesn't exist
        if (!isset($groupedRecords[$date])) {
            $groupedRecords[$date] = [
                'day_of_week' => $record['day_of_week'],
                'morning' => ['signature' => null, 'attendance' => 0],
                'evening' => ['signature' => null, 'attendance' => 0],
            ];
        }

        // Assign the signature and attendance to the respective shift
        $groupedRecords[$date][$shift] = [
            'signature' => $record['signature'],
            'attendance' => $record['attendance'],
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>Employe | Apply Leave</title>

</head>

<body>
    <?php include('includes/header.php'); ?>

    <?php include('includes/sidebar.php'); ?>
    <main class="mn-inner">
        <?php
        $sql = "SELECT FirstName, LastName, EmpId FROM tblemployees WHERE id = :eid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ); // Change to fetch a single row

        // Check if result is not empty
        if ($result) {
        ?>
            <h4>Attendance Records for Employee ID: <strong><?php echo htmlentities($result->FirstName . " " . $result->LastName); ?></strong></h4>
        <?php
        } else {
        ?>
            <h4>No employee found for ID: <strong><?php echo htmlspecialchars($eid); ?></strong></h4>
        <?php
        }
        ?>

        <?php if (empty($groupedRecords)): ?>
            <h3>No attendance records found for Employee ID: <?php echo htmlspecialchars($eid); ?>.</h3>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day of Week</th>
                            <th>Morning Shift</th>
                            <th>Evening Shift</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groupedRecords as $date => $data): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($date); ?></td>
                                <td><?php echo htmlspecialchars($data['day_of_week']); ?></td>
                                <td>
                                    <?php if ($data['morning']['signature'] && $data['morning']['attendance'] == 1): ?>
                                        <img src="<?php echo htmlspecialchars($data['morning']['signature']); ?>" alt="Signature" width="300" height="100">
                                    <?php elseif (is_null($data['morning']['signature']) && $data['morning']['attendance'] == 0): ?>
                                        <span style="color: red;"></span>
                                    <?php else: ?>
                                        <span style="color: red;">Leave</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($data['evening']['signature'] && $data['evening']['attendance'] == 1): ?>
                                        <img src="<?php echo htmlspecialchars($data['evening']['signature']); ?>" alt="Signature" width="300" height="100">
                                    <?php elseif (is_null($data['evening']['signature']) && $data['evening']['attendance'] == 0): ?>
                                        <span style="color: red;"></span>
                                    <?php else: ?>
                                        <span style="color: red;">Leave</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
    </div>
    <div class="left-sidebar-hover"></div>
    <?php include('includes/footer.php') ?>

</body>

</html>