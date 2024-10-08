<?php

// session_start();
// error_reporting(0);
// include('includes/config.php');

// if (strlen($_SESSION['alogin']) == 0) {
//     header('location:index.php');
// } else {
//     // Fetch Total Employees
//     $sql = "SELECT id FROM tblemployees";
//     $query = $dbh->prepare($sql);
//     $query->execute();
//     $empcount = $query->rowCount();

//     // Fetch Total Departments
//     $sql = "SELECT id FROM tbldepartments";
//     $query = $dbh->prepare($sql);
//     $query->execute();
//     $dptcount = $query->rowCount();

//     // Fetch Leave Types
//     $sql = "SELECT id FROM tblleavetype";
//     $query = $dbh->prepare($sql);
//     $query->execute();
//     $leavtypcount = $query->rowCount();

//     // Fetch Total Leaves
//     $sql = "SELECT id FROM tblleaves";
//     $query = $dbh->prepare($sql);
//     $query->execute();
//     $totalleaves = $query->rowCount();

//     // Fetch Approved Leaves
//     $sql = "SELECT id FROM tblleaves WHERE Status = 1";
//     $query = $dbh->prepare($sql);
//     $query->execute();
//     $approvedleaves = $query->rowCount();

//     // Fetch New Leave Applications (Status 0)
//     $sql = "SELECT id FROM tblleaves WHERE Status = 0";
//     $query = $dbh->prepare($sql);
//     $query->execute();
//     $newleaveapplications = $query->rowCount();

//     // Fetch Latest Leave Applications
//     $sql = "SELECT tblleaves.id AS lid, tblemployees.FirstName, tblemployees.LastName, tblemployees.EmpId, 
//                    tblemployees.id, tblleaves.LeaveType, tblleaves.PostingDate, tblleaves.Status 
//             FROM tblleaves 
//             JOIN tblemployees ON tblleaves.empid = tblemployees.id 
//             ORDER BY lid DESC LIMIT 6";
//     $query = $dbh->prepare($sql);
//     $query->execute();
//     $latestLeaveApplications = $query->fetchAll(PDO::FETCH_OBJ);
// }

session_start();
error_reporting(0);
include('includes/config.php');

// Redirect if not logged in
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

// Fetch counts based on table and optional conditions
function fetchCount($dbh, $table, $condition = null) {
    $sql = "SELECT COUNT(id) AS count FROM $table";
    if ($condition) {
        $sql .= " WHERE $condition";
    }
    $query = $dbh->prepare($sql);
    $query->execute();
    return $query->fetchColumn();
}

// Fetch latest leave applications
function fetchLatestLeaveApplications($dbh, $limit = 6) {
    $sql = "SELECT tblleaves.id AS lid, tblemployees.FirstName, tblemployees.LastName, 
                   tblemployees.EmpId, tblemployees.id AS empid, tblleaves.LeaveType, tblleaves.PostingDate, tblleaves.Status 
            FROM tblleaves 
            JOIN tblemployees ON tblleaves.empid = tblemployees.id 
            ORDER BY tblleaves.id DESC LIMIT :limit";
    $query = $dbh->prepare($sql);
    $query->bindParam(':limit', $limit, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_OBJ);
}

// Constants for leave statuses
define('STATUS_APPROVED', 1);
define('STATUS_PENDING', 0);

// Fetch various counts
$empcount = fetchCount($dbh, 'tblemployees');
$dptcount = fetchCount($dbh, 'tbldepartments');
$leavtypcount = fetchCount($dbh, 'tblleavetype');
$totalleaves = fetchCount($dbh, 'tblleaves');
$approvedleaves = fetchCount($dbh, 'tblleaves', 'Status = ' . STATUS_APPROVED);
$newleaveapplications = fetchCount($dbh, 'tblleaves', 'Status = ' . STATUS_PENDING);

// Fetch latest leave applications
$latestLeaveApplications = fetchLatestLeaveApplications($dbh);
?>

