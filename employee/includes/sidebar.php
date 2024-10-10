     <aside id="slide-out" class="side-nav white fixed">
         <div class="side-nav-wrapper">
             <div class="sidebar-profile">
                 <div class="sidebar-profile-info">
                     <?php
                        $eid = $_SESSION['eid'];
                        $sql = "SELECT FirstName,LastName,EmpId, ProfileImage from  tblemployees where id=:eid";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        $cnt = 1;
                        if ($query->rowCount() > 0) {
                            foreach ($results as $result) { ?>   
                                 <img src="<?php echo htmlentities($result->ProfileImage) ?>" class="circle" alt="" width="100px" height="100px">
                             <p><?php echo htmlentities($result->FirstName . " " . $result->LastName); ?></p>
                             <span><?php echo htmlentities($result->EmpId) ?></span>
                     <?php }
                        } ?>
                 </div>
             </div>

             <ul class="sidebar-menu collapsible collapsible-accordion" data-collapsible="accordion">
                 <li class="no-padding"><a class="waves-effect waves-grey" href="dashboard.php"><i class="material-icons">settings_input_svideo</i>Dashboard</a></li>
                 <li class="no-padding"><a class="waves-effect waves-grey" href="myprofile.php"><i class="material-icons">account_box</i>My Profiles</a></li>
                 <li class="no-padding"><a class="waves-effect waves-grey" href="emp-changepassword.php"><i class="material-icons">settings_input_svideo</i>Chnage Password</a></li>
                 <li class="no-padding">
                     <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">apps</i>Leaves<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                     <div class="collapsible-body">
                         <ul>
                             <li><a href="apply-leave.php">Apply Leave</a></li>
                             <li><a href="leavehistory.php">Leave History</a></li>
                         </ul>
                     </div>
                 </li>


                 <li class="no-padding">
                     <?php
                        // Check if the employee has a signature
                        $sql = "SELECT * FROM employee_signatures WHERE eid = :eid";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':eid', $eid, PDO::PARAM_INT);
                        $query->execute();
                        $hasSignature = $query->rowCount() > 0;
                        ?>
                     <a class="waves-effect waves-grey" href="<?php echo $hasSignature ? 'attendance.php' : 'getsign.php'; ?>">
                         <i class="material-icons">view_timeline</i>Attendance
                     </a>
                 </li>


                 <li class="no-padding">
                     <a class="waves-effect waves-grey" href="logout.php"><i class="material-icons">exit_to_app</i>Sign Out</a>
                 </li>


             </ul>
             <div class="footer">
                 <p class="copyright"> HRMS © Design by GROUP-3</p>

             </div>
         </div>
     </aside>