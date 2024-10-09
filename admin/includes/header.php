<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title -->
    <title> HRMS </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="" />

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../assets/plugins/metrojs/MetroJs.min.css" rel="stylesheet">
    <link href="../assets/plugins/weather-icons-master/css/weather-icons.min.css" rel="stylesheet">
    <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <link href="../assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="../assets/plugins/google-code-prettify/prettify.css" rel="stylesheet" type="text/css" />



 





    






    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }

        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }
        #profile_image_preview {
                width: 150px;
                height: 150px;
            }

    </style>
     <script type="text/javascript">
            function valid() {
                if (document.addemp.password.value != document.addemp.confirmpassword.value) {
                    alert("New Password and Confirm Password Field do not match  !!");
                    document.addemp.confirmpassword.focus();
                    return false;
                }
                return true;
            }
        </script>

        <script>
            function checkAvailabilityEmpid() {
                $("#loaderIcon").show();
                jQuery.ajax({
                    url: "check_availability.php",
                    data: 'empcode=' + $("#empcode").val(),
                    type: "POST",
                    success: function(data) {
                        $("#empid-availability").html(data);
                        $("#loaderIcon").hide();
                    },
                    error: function() {}
                });
            }
        </script>

        <script>
            function checkAvailabilityEmailid() {
                $("#loaderIcon").show();
                jQuery.ajax({
                    url: "check_availability.php",
                    data: 'emailid=' + $("#email").val(),
                    type: "POST",
                    success: function(data) {
                        $("#emailid-availability").html(data);
                        $("#loaderIcon").hide();
                    },
                    error: function() {}
                });
            }
        </script>
</head>


 <div class="loader-bg"></div>
 <div class="mn-content fixed-sidebar">
     <header class="mn-header navbar-fixed">
         <nav class="cyan darken-1">
             <div class="nav-wrapper row">
                 <section class="material-design-hamburger navigation-toggle">
                     <a href="#" data-activates="slide-out" class="button-collapse show-on-large material-design-hamburger__icon">
                         <span class="material-design-hamburger__layer"></span>
                     </a>
                 </section>
                 <div class="header-title col s3">
                     <span class="chapter-title">HRMS | Admin</span>
                 </div>

                 <ul class="right col s9 m3 nav-right-menu">

                     <li class="hide-on-small-and-down"><a href="javascript:void(0)" data-activates="dropdown1" class="dropdown-button dropdown-right show-on-large"><i class="material-icons">notifications_none</i>
                             <?php
                                $isread = 0;
                                $sql = "SELECT id from tblleaves where IsRead=:isread";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':isread', $isread, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $unreadcount = $query->rowCount(); ?>


                             <span class="badge"><?php echo htmlentities($unreadcount); ?></span></a></li>
                     <li class="hide-on-med-and-up"><a href="javascript:void(0)" class="search-toggle"><i class="material-icons">search</i></a></li>
                 </ul>

                 <ul id="dropdown1" class="dropdown-content notifications-dropdown">
                     <li class="notificatoins-dropdown-container">
                         <ul>
                             <li class="notification-drop-title">Notifications</li>
                             <?php
                                $isread = 0;
                                $sql = "SELECT tblleaves.id as lid,tblemployees.FirstName,tblemployees.LastName,tblemployees.EmpId,tblleaves.PostingDate from tblleaves join tblemployees on tblleaves.empid=tblemployees.id where tblleaves.IsRead=:isread";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':isread', $isread, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $result) { ?>

                                     <li>
                                         <a href="leave-details.php?leaveid=<?php echo htmlentities($result->lid); ?>">
                                             <div class="notification">
                                                 <div class="notification-icon circle cyan"><i class="material-icons">done</i></div>
                                                 <div class="notification-text">
                                                     <p><b><?php echo htmlentities($result->FirstName . " " . $result->LastName); ?><br />(<?php echo htmlentities($result->EmpId); ?>)</b> applied for leave</p><span>at <?php echo htmlentities($result->PostingDate); ?></b< /span>
                                                 </div>
                                             </div>
                                         </a>
                                     </li>
                             <?php }
                                } ?>
                         </ul>
             </div>
         </nav>
     </header>

 </html>