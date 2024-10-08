<?php
session_start();
include('includes/config.php');

// Retrieve data from the AJAX request
$eid = $_POST['emp_id'];
$currentDate = $_POST['attendance_date'];

// Query to check if morning and evening attendance are recorded
$sql = "SELECT shift, attendance FROM employee_attendance WHERE eid = :eid AND attendance_date = :attendance_date";
$query = $dbh->prepare($sql);
$query->bindParam(':eid', $eid, PDO::PARAM_INT);
$query->bindParam(':attendance_date', $currentDate, PDO::PARAM_STR);
$query->execute();

$attendanceRecords = $query->fetchAll(PDO::FETCH_ASSOC);

// Initialize flags
$morningRecorded = false;
$eveningRecorded = false;

// Check the results
foreach ($attendanceRecords as $record) {
    if ($record['shift'] === 'morning') {
        if ($record['attendance'] == 1) {
            $morningRecorded = true;
        } elseif ($record['attendance'] == 0) {
            $morningRecorded = true; // Consider as recorded even if it's absence
        }
    }
    if ($record['shift'] === 'evening') {
        if ($record['attendance'] == 1) {
            $eveningRecorded = true;
        } elseif ($record['attendance'] == 0) {
            $eveningRecorded = true; // Consider as recorded even if it's absence
        }
    }
}

// Return the result as JSON
echo json_encode(['morningRecorded' => $morningRecorded, 'eveningRecorded' => $eveningRecorded]);
?>
