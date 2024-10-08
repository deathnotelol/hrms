<?php
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input data
    $empId = isset($_POST['emp_id']) ? intval($_POST['emp_id']) : 0;
    $attendanceDate = isset($_POST['attendance_date']) ? $_POST['attendance_date'] : '';
    $shift = isset($_POST['shift']) ? $_POST['shift'] : '';
    $attendance = isset($_POST['attendance']) ? intval($_POST['attendance']) : 0;

    // Validate the inputs before proceeding
    if ($empId > 0 && !empty($attendanceDate) && !empty($shift)) {
        try {
            // SQL to insert or update attendance, using DAYNAME to automatically get the day of the week
            $sql = "INSERT INTO employee_attendance (eid, attendance_date, day_of_week, shift, attendance)
                    VALUES (:empId, :attendanceDate, DAYNAME(:attendanceDate), :shift, :attendance)
                    ON DUPLICATE KEY UPDATE attendance = :attendance";

            $query = $dbh->prepare($sql);

            // Bind the parameters
            $query->bindParam(':empId', $empId, PDO::PARAM_INT);
            $query->bindParam(':attendanceDate', $attendanceDate, PDO::PARAM_STR);
            $query->bindParam(':shift', $shift, PDO::PARAM_STR);
            $query->bindParam(':attendance', $attendance, PDO::PARAM_INT);

            // Execute the query
            if ($query->execute()) {
                // Attendance saved successfully
                echo json_encode(['status' => 'success', 'message' => 'Attendance saved successfully']);
            } else {
                // Failed to save attendance
                echo json_encode(['status' => 'error', 'message' => 'Failed to save attendance']);
            }
        } catch (PDOException $e) {
            // Catch any database errors
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        // If inputs are missing or invalid
        echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
