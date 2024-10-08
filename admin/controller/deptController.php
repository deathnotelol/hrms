<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
}

if (isset($_POST['add'])) {
    $deptname = $_POST['departmentname'];
    $deptshortname = $_POST['departmentshortname'];
    $deptcode = $_POST['deptcode'];
    $sql = "INSERT INTO tbldepartments(DepartmentName,DepartmentCode,DepartmentShortName) VALUES(:deptname,:deptcode,:deptshortname)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':deptname', $deptname, PDO::PARAM_STR);
    $query->bindParam(':deptcode', $deptcode, PDO::PARAM_STR);
    $query->bindParam(':deptshortname', $deptshortname, PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if ($lastInsertId) {
        $msg = "Department Created Successfully";
    } else {
        $error = "Something went wrong. Please try again";
    }
}
