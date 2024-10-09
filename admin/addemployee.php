<?php
session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

// error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    if (isset($_POST['add'])) {
        // Retrieve other form data
        $empid = $_POST['empcode'];
        $fname = $_POST['firstName'];
        $lname = $_POST['lastName'];
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $department = $_POST['department'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $mobileno = $_POST['mobileno'];
        $position = $_POST['position'];
        $status = 1;

        // File upload handling
        $target_dir = "../assets/images/upload/";  // Directory where image will be saved
        $imageFileType = strtolower(pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        $uploadOk = 1;

        // Check if image file is a valid image
        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $error = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (5MB limit)
        if ($_FILES["profile_image"]["size"] > 5000000) {
            $error = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // If $uploadOk is set to 0, it means there was an error
        if ($uploadOk == 0) {
            $error = "Sorry, your file was not uploaded.";
        } else {
            // If everything is ok, try to upload file
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                // File uploaded successfully, proceed with database insertion
                $profileImagePath = $target_file; // Save the file path to the variable

                // Insert into database
                $sql = "INSERT INTO tblemployees(EmpId, FirstName, LastName, EmailId, Password, Gender, Dob, Department, Address, City, Country, PositionID, Phonenumber, Status, ProfileImage) 
                        VALUES(:empid, :fname, :lname, :email, :password, :gender, :dob, :department, :address, :city, :country, :position, :mobileno, :status, :profileImage)";

                $query = $dbh->prepare($sql);
                $query->bindParam(':empid', $empid, PDO::PARAM_STR);
                $query->bindParam(':fname', $fname, PDO::PARAM_STR);
                $query->bindParam(':lname', $lname, PDO::PARAM_STR);
                $query->bindParam(':email', $email, PDO::PARAM_STR);
                $query->bindParam(':password', $password, PDO::PARAM_STR);
                $query->bindParam(':gender', $gender, PDO::PARAM_STR);
                $query->bindParam(':dob', $dob, PDO::PARAM_STR);
                $query->bindParam(':department', $department, PDO::PARAM_STR);
                $query->bindParam(':address', $address, PDO::PARAM_STR);
                $query->bindParam(':city', $city, PDO::PARAM_STR);
                $query->bindParam(':country', $country, PDO::PARAM_STR);
                $query->bindParam(':position', $position, PDO::PARAM_STR);
                $query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
                $query->bindParam(':status', $status, PDO::PARAM_STR);
                $query->bindParam(':profileImage', $profileImagePath, PDO::PARAM_STR);
                $query->execute();
                $lastInsertId = $dbh->lastInsertId();

                if ($lastInsertId) {
                    $msg = "Employee record and profile image added successfully.";
                    echo "<script> setTimeout(function() { window.location.href = 'manageemployee.php';}, 3000);</script>";
                } else {
                    $error = "Something went wrong. Please try again.";
                }
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        }
    }

?>


    <body>
        <?php include('includes/header.php'); ?>

        <?php include('includes/sidebar.php'); ?>
        <main class="mn-inner">
            <div class="row">
                <div class="col s12">
                    <div class="page-title">Add employee</div>
                </div>
                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content">
                            <form id="example-form" method="post" name="addemp" enctype="multipart/form-data">
                                <div>
                                    <h3>Employee Info</h3>
                                    <section>
                                        <div class="wizard-content">
                                            <div class="row">
                                                <div class="col m6">
                                                    <div class="row">
                                                        <?php if (isset($error)) { ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } else if (isset($msg)) { ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>


                                                        <div class="input-field col  s12">
                                                            <label for="empcode">Employee Code(Must be unique)</label>
                                                            <input name="empcode" id="empcode" onBlur="checkAvailabilityEmpid()" type="text" autocomplete="off" required>
                                                            <span id="empid-availability" style="font-size:12px;"></span>
                                                        </div>


                                                        <div class="input-field col m6 s12">
                                                            <label for="firstName">First name</label>
                                                            <input id="firstName" name="firstName" type="text" required>
                                                        </div>

                                                        <div class="input-field col m6 s12">
                                                            <label for="lastName">Last name</label>
                                                            <input id="lastName" name="lastName" type="text" autocomplete="off" required>
                                                        </div>

                                                        <div class="input-field col s12">
                                                            <label for="email">Email</label>
                                                            <input name="email" type="email" id="email" onBlur="checkAvailabilityEmailid()" autocomplete="off" required>
                                                            <span id="emailid-availability" style="font-size:12px;"></span>
                                                        </div>

                                                        <div class="input-field col s12">
                                                            <label for="password">Password</label>
                                                            <input id="password" name="password" type="password" autocomplete="off" required>
                                                        </div>

                                                        <div class="input-field col s12">
                                                            <label for="confirm">Confirm password</label>
                                                            <input id="confirm" name="confirmpassword" type="password" autocomplete="off" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col m6">
                                                    <div class="row">
                                                        <div class="input-field col m6 s12">
                                                            <select name="gender" autocomplete="off">
                                                                <option value="">Gender...</option>
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>

                                                        <div class="input-field col m6 s12">
                                                            <label for="birthdate">Birthdate</label>
                                                            <input id="birthdate" name="dob" type="date" class="datepicker" autocomplete="off">
                                                        </div>



                                                        <div class="input-field col m6 s12">
                                                            <select name="department" autocomplete="off">
                                                                <option value="">Department...</option>
                                                                <?php $sql = "SELECT DepartmentName from tbldepartments";
                                                                $query = $dbh->prepare($sql);
                                                                $query->execute();
                                                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                                $cnt = 1;
                                                                if ($query->rowCount() > 0) {
                                                                    foreach ($results as $result) {   ?>
                                                                        <option value="<?php echo htmlentities($result->DepartmentName); ?>"><?php echo htmlentities($result->DepartmentName); ?></option>
                                                                <?php }
                                                                } ?>
                                                            </select>
                                                        </div>

                                                        <div class="input-field m6 col s12">
                                                            <select name="position" autocomplete="off">
                                                                <option value="">Positon</option>
                                                                <?php $sql = "SELECT * from positions";
                                                                $query = $dbh->prepare($sql);
                                                                $query->execute();
                                                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                                if ($query->rowCount() > 0) {
                                                                    foreach ($results as $result) {   ?>
                                                                        <option value="<?php echo htmlentities($result->id); ?>"><?php echo htmlentities($result->position_name); ?></option>
                                                                <?php }
                                                                } ?>
                                                            </select>
                                                        </div>


                                                        <div class="input-field col m6 s12">
                                                            <label for="address">Address</label>
                                                            <input id="address" name="address" type="text" autocomplete="off" required>
                                                        </div>

                                                        <div class="input-field col m6 s12">
                                                            <label for="city">City/Town</label>
                                                            <input id="city" name="city" type="text" autocomplete="off" required>
                                                        </div>

                                                        <div class="input-field col m6 s12">
                                                            <label for="country">Country</label>
                                                            <input id="country" name="country" type="text" autocomplete="off" required>
                                                        </div>


                                                        <div class="input-field col m6 s12">
                                                            <label for="phone">Mobile number</label>
                                                            <input id="phone" name="mobileno" type="tel" maxlength="12" autocomplete="off" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <label for="image-upload"></label>
                                                    <input id="image-upload" name="profile_image" type="file" accept="image/*" class="alpha-input alpha-file-input" required>
                                                    <br><br>
                                                    <!-- Image preview -->
                                                    <img id="image-preview" src="#" alt="Image Preview" style="display: none; " />
                                                </div>

                                                <div class="input-field col s12">
                                                    <button type="submit" name="add" onclick="return valid();" id="add" class="waves-effect waves-light btn indigo m-b-xs">ADD</button>
                                                </div>
                                            </div>
                                        </div>
                                    </section>


                                    </section>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
          
        </main>
        </div>
        <div class="left-sidebar-hover"></div>

        <?php include('includes/footer.php'); ?>

    </body>

    </html>
<?php } ?>