<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['emplogin']) == 0) {
    header('location:index.php');
} else {
    $eid = intval($_GET['empid']);
    if (isset($_POST['update'])) {
        $fname = $_POST['firstName'];
        $lname = $_POST['lastName'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $department = $_POST['department'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $position = $_POST['position'];
        $mobileno = $_POST['mobileno'];

        // Handling image upload
        if (!empty($_FILES['profile_image']['name'])) {
            $file_name = $_FILES['profile_image']['name'];
            $file_tmp = $_FILES['profile_image']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $new_file_name = uniqid() . '.' . $file_ext;
            $file_path = "../assets/images/upload/" . $new_file_name;

            // Move file to upload directory
            if (move_uploaded_file($file_tmp, $file_path)) {
                // Update with new image
                $sql = "UPDATE tblemployees SET FirstName=:fname, LastName=:lname, 
                        Gender=:gender, Dob=:dob, Department=:department, 
                        Address=:address, City=:city, Country=:country, 
                        PositionID=:position, Phonenumber=:mobileno, 
                        ProfileImage=:image WHERE id=:eid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':image', $file_path, PDO::PARAM_STR);
            } else {
                echo "Failed to upload image.";
                exit;
            }
        } else {
            // Update without image
            $sql = "UPDATE tblemployees SET FirstName=:fname, LastName=:lname, 
                    Gender=:gender, Dob=:dob, Department=:department, 
                    Address=:address, City=:city, Country=:country, 
                    PositionID=:position, Phonenumber=:mobileno WHERE id=:eid";
            $query = $dbh->prepare($sql);
        }

        // Bind other parameters
        $query->bindParam(':fname', $fname, PDO::PARAM_STR);
        $query->bindParam(':lname', $lname, PDO::PARAM_STR);
        $query->bindParam(':gender', $gender, PDO::PARAM_STR);
        $query->bindParam(':dob', $dob, PDO::PARAM_STR);
        $query->bindParam(':department', $department, PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->bindParam(':city', $city, PDO::PARAM_STR);
        $query->bindParam(':country', $country, PDO::PARAM_STR);
        $query->bindParam(':position', $position, PDO::PARAM_STR);
        $query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
        $query->bindParam(':eid', $eid, PDO::PARAM_STR);

        // Execute query and redirect or display message
        if ($query->execute()) {
            echo "Employee record updated successfully!";
            echo "<script>
                    setTimeout(function(){
                        window.location.href = 'manageemployee.php';
                    }, 3000);
                  </script>";
        } else {
            echo "Error updating record. Please try again.";
        }
    }

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <!-- Title -->
        <title>Employee | Update Employee</title>

        <style>
            .errorWrap {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #dd3d36;
                box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            }

            .succWrap {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #5cb85c;
                box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            }

            #profile_image_preview {
                width: 150px;
                height: 150px;
            }
        </style>
    </head>

    <body>
        <?php include('includes/header.php'); ?>
        <?php include('includes/sidebar.php'); ?>
        <main class="mn-inner">
            <div class="row">
                <div class="col s12">
                    <div class="page-title">Update Employee</div>
                </div>
                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content">
                            <form id="example-form" method="post" name="updatemp" enctype="multipart/form-data">
                                <div>
                                    <h3>Update Employee Info</h3>
                                    <?php if (isset($error)) { ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?></div><?php } else if (isset($msg)) { ?><div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?></div><?php } ?>
                                    <div class="wizard-content">
                                        <div class="row">
                                            <div class="col m6">
                                                <div class="row">
                                                    <?php
                                                    $sql = "SELECT e.*, p.salary FROM tblemployees e
                                                            LEFT JOIN positions p ON e.PositionID = p.id
                                                            WHERE e.id = :eid";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $result) {
                                                    ?>
                                                            <div class="input-field col s12">
                                                                <label for="empcode">Employee Code(Must be unique)</label>
                                                                <input name="empcode" id="empcode" value="<?php echo htmlentities($result->EmpId); ?>" type="text" autocomplete="off" readonly required>
                                                            </div>

                                                            <div class="input-field col m6 s12">
                                                                <label for="firstName">First name</label>
                                                                <input id="firstName" name="firstName" value="<?php echo htmlentities($result->FirstName); ?>" type="text" required>
                                                            </div>

                                                            <div class="input-field col m6 s12">
                                                                <label for="lastName">Last name</label>
                                                                <input id="lastName" name="lastName" value="<?php echo htmlentities($result->LastName); ?>" type="text" required>
                                                            </div>

                                                            <div class="input-field col s12">
                                                                <label for="email">Email</label>
                                                                <input name="email" type="email" id="email" value="<?php echo htmlentities($result->EmailId); ?>" readonly required>
                                                            </div>

                                                            <div class="input-field col s12">
                                                                <label for="phone">Mobile number</label>
                                                                <input id="phone" name="mobileno" type="tel" value="<?php echo htmlentities($result->Phonenumber); ?>" maxlength="10" required>
                                                            </div>

                                                            <div class="input-field col m6 s12">
                                                                <label for="birthdate"></label>
                                                                <input id="birthdate" name="dob" class="datepicker" value="<?php echo htmlentities($result->Dob); ?>">
                                                            </div>

                                                            <div class="input-field col m6 s12">
                                                                <label for="gender"></label>
                                                                <select name="gender">
                                                                    <option value="<?php echo htmlentities($result->Gender); ?>"><?php echo htmlentities($result->Gender); ?></option>
                                                                    <option value="Male">Male</option>
                                                                    <option value="Female">Female</option>
                                                                </select>
                                                            </div>
                                                </div>
                                            </div>

                                            <div class="col m6">
                                                <div class="row">


                                                    <div class="input-field col m6 s12">
                                                        <label for="department"></label>
                                                        <select name="department" autocomplete="off">

                                                            <?php $sql = "SELECT DepartmentName from tbldepartments";
                                                            $query = $dbh->prepare($sql);
                                                            $query->execute();
                                                            $departments = $query->fetchAll(PDO::FETCH_OBJ);
                                                            $cnt = 1;
                                                            if ($query->rowCount() > 0) {
                                                                foreach ($departments as $department) {   ?>
                                                                    <option value="<?php echo htmlentities($department->DepartmentName); ?>"
                                                                        <?php
                                                                        if ($result->Department == $department->DepartmentName) {
                                                                            echo "selected";
                                                                        }

                                                                        ?>><?php echo htmlentities($department->DepartmentName); ?></option>
                                                            <?php }
                                                            } ?>
                                                        </select>
                                                    </div>

                                                    <div class="input-field col m6 s12">
                                                        <label for="position"></label>
                                                        <select id="position" name="position" onchange="updateSalary()">
                                                            <?php $sql = "SELECT * FROM positions";
                                                            $query = $dbh->prepare($sql);
                                                            $query->execute();
                                                            $positions = $query->fetchAll(PDO::FETCH_OBJ);
                                                            foreach ($positions as $pos) { ?>
                                                                <option value="<?php echo htmlentities($pos->id); ?>" data-salary="<?php echo htmlentities($pos->salary); ?>" <?php if ($pos->id == $result->PositionID) echo 'selected'; ?>> <?php echo htmlentities($pos->position_name); ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="input-field col s12">
                                                        <label for="salary">Salary</label>
                                                        <input id="salary" name="salary" type="text" value="<?php echo htmlentities($result->salary); ?>" readonly>
                                                    </div>

                                                    <div class="input-field col s12">
                                                        <label for="address">Address</label>
                                                        <input id="address" name="address" type="text" value="<?php echo htmlentities($result->Address); ?>" required>
                                                    </div>

                                                    <div class="input-field col m6 s12">
                                                        <label for="city">City/Town</label>
                                                        <input id="city" name="city" type="text" value="<?php echo htmlentities($result->City); ?>" required>
                                                    </div>

                                                    <div class="input-field col m6 s12">
                                                        <label for="country">Country</label>
                                                        <input id="country" name="country" type="text" value="<?php echo htmlentities($result->Country); ?>" required>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <label for="image-upload"></label>
                                                        <input id="image-upload" name="profile_image" type="file" accept="image/*" class="alpha-input alpha-file-input">
                                                        <br><br>
                                                        <!-- Image preview -->
                                                        <img id="profile_image_preview" src="<?php echo $result->ProfileImage; ?>" alt="Image Preview" width="100" height="100" />
                                                    </div>
                                            <?php }
                                                    } ?>
                                                </div>
                                            </div>
                                            <div class="col m6">
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <button type="submit" name="update" class="waves-effect waves-light btn indigo m-b-xs">Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <div class="left-sidebar-hover"></div>

      <?php include('includes/footer.php') ?>                                              

        <!-- Salary Update Script -->
        <script>
            function updateSalary() {
                var positionSelect = document.getElementById("position");
                var selectedOption = positionSelect.options[positionSelect.selectedIndex];
                var salary = selectedOption.getAttribute("data-salary");
                document.getElementById("salary").value = salary;
            }
            //Rander for Profile images

            function previewImage(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('profile_image_preview');
                    output.src = reader.result;
                }
                reader.readAsDataURL(event.target.files[0]);
            }
        </script>
    </body>

    </html>
<?php } ?>