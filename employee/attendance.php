<?php
session_start();
include('includes/config.php');

// Check if the user is logged in
if (strlen($_SESSION['emplogin']) == 0) {
    header('location:index.php');
    exit;
}

// Retrieve employee attendance status from the database
$eid = $_SESSION['eid'];
$currentDate = date('Y-m-d'); // Get current date

// Query to fetch attendance details
$sqlAttendance = "SELECT shift, attendance FROM employee_attendance WHERE eid = :eid AND attendance_date = :attendance_date";
$queryAttendance = $dbh->prepare($sqlAttendance);
$queryAttendance->bindParam(':eid', $eid, PDO::PARAM_INT);
$queryAttendance->bindParam(':attendance_date', $currentDate, PDO::PARAM_STR);
$queryAttendance->execute();
$attendance = $queryAttendance->fetch(PDO::FETCH_ASSOC);

// Check if any attendance record was found
if ($attendance) {
    // Determine attendance status based on the shift value
    $morningShiftStatus = ($attendance['shift'] === 'morning' && $attendance['attendance'] == 1);
    $eveningShiftStatus = ($attendance['shift'] === 'evening' && $attendance['attendance'] == 1);
} else {
    // If no attendance record was found, set statuses to false
    $morningShiftStatus = false;
    $eveningShiftStatus = false;
}

// Disable button logic
$disableMorning = $morningShiftStatus ? 'disabled' : '';
$disableEvening = $eveningShiftStatus ? 'disabled' : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Attendance Employee</title>
    <style>
        .signature-container {
            margin-top: 20px;
            display: block;
        }
        canvas.signature-pad {
            border: 1px solid black;
            width: 100%; 
            height: 200px;
        }
        .signature-image {
            display: none;
        }
        .attendance-image {
            width: 100px;
            height: auto;
        }
        .btn {
            margin: 5px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .btn-success:disabled, .btn-danger:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .attend-button {
            background-color: green;
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
                            <!-- Retrieve employee details -->
                            <?php 
                                $sql = "SELECT FirstName, LastName, EmpId, ProfileImage FROM tblemployees WHERE id = :eid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                                $query->execute();
                                $employee = $query->fetch(PDO::FETCH_OBJ);
                            ?>
                            
                            <!-- Attendance table -->
                            <form action="" id="attendance-form">
                                <table class="attendance-table" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Office Day</th>
                                            <th>Morning Shift</th>
                                            <th>Evening Shift</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Signature retrieval -->
                                        <?php
                                            $sqlSignature = "SELECT signature FROM employee_signatures WHERE EmpId = :empId";
                                            $querySignature = $dbh->prepare($sqlSignature);
                                            $querySignature->bindParam(':empId', $employee->EmpId, PDO::PARAM_INT);
                                            $querySignature->execute();
                                            $signatureData = $querySignature->fetch(PDO::FETCH_ASSOC);
                                            $signature = $signatureData ? $signatureData['signature'] : null;
                                        ?>
                                        <tr>
                                            <td colspan="3">
                                                <?php if ($signature): ?>
                                                    <img src="<?php echo htmlspecialchars($signature); ?>" alt="Signature" width="300" height="50">
                                                <?php else: ?>
                                                    No Signature Available
                                                <?php endif; ?>
                                            </td>
                                        </tr>

                                        <!-- Only one row for the current day of the week -->
                                        <?php
                                            $currentDay = date('l'); // Get the current day name (e.g., Monday)
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($currentDay); ?></td>
                                            <td>
                                                <!-- Morning Shift Button -->
                                                <button type="button" class="btn btn-success attend-button" data-day="<?php echo htmlspecialchars($currentDate); ?>" data-shift="morning" data-attendance="1" <?php echo $disableMorning; ?>>
                                                    <i class="material-icons left">check_circle</i> Attend
                                                </button>
                                            </td>
                                            <td>
                                                <!-- Evening Shift Button -->
                                                <button type="button" class="btn btn-success attend-button" data-day="<?php echo htmlspecialchars($currentDate); ?>" data-shift="evening" data-attendance="1" <?php echo $disableEvening; ?>>
                                                    <i class="material-icons left">check_circle</i> Attend
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                            <!-- End of attendance table -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="left-sidebar-hover"></div>

    <?php include('includes/footer.php'); ?>

    <script>
    $(document).ready(function() {
        var empId = <?php echo json_encode($eid); ?>;
        var currentDate = new Date().toISOString().split('T')[0];

        function checkAttendanceStatus() {
            $.ajax({
                url: 'check_attendance.php', 
                type: 'POST',
                data: {
                    emp_id: empId,
                    attendance_date: currentDate
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.morningRecorded) {
                        $('.attend-button[data-shift="morning"]').prop('disabled', true);
                    }
                    if (data.eveningRecorded) {
                        $('.attend-button[data-shift="evening"]').prop('disabled', true);
                    }
                },
                error: function() {
                    console.error('Error checking attendance status.');
                }
            });
        }

        checkAttendanceStatus();

        $('.attend-button').click(function() {
            var buttonClicked = $(this);
            var day = buttonClicked.data('day');
            var shift = buttonClicked.data('shift');
            var attendance = buttonClicked.data('attendance');

            if (buttonClicked.prop('disabled')) {
                return;
            }

            buttonClicked.closest('td').find('button').prop('disabled', true);

            $.ajax({
                url: 'save_attendance.php',
                type: 'POST',
                data: {
                    emp_id: empId,
                    attendance_date: currentDate,
                    day_of_week: day,
                    shift: shift,
                    attendance: attendance
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        alert('Attendance saved successfully!');
                        window.location.href = 'attendanceuserview.php';
                        if (shift === "morning") {
                            $('.attend-button[data-shift="morning"]').prop('disabled', true);
                        } else if (shift === "evening") {
                            $('.attend-button[data-shift="evening"]').prop('disabled', true);
                        }
                    } else {
                        alert(data.message);
                        buttonClicked.prop('disabled', false);
                    }
                },
                error: function() {
                    alert('An error occurred while saving attendance.');
                    buttonClicked.prop('disabled', false);
                }
            });
        });
    });
    </script>
</body>
</html>
