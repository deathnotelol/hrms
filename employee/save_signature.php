<?php
session_start();
include('includes/config.php');

if (isset($_POST['submit'])) {
    // Retrieve EmpId, eid, and signature data
    $EmpId = $_POST['empId'];
    $eid = $_SESSION['eid']; // Assuming eid is stored in the session
    $signatureData = $_POST['signature-data'];

    // Ensure signatureData is not empty
    if (!empty($signatureData)) {
        // Remove "data:image/png;base64," from the signature data
        $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
        $signatureData = str_replace(' ', '+', $signatureData); // Replace spaces with pluses
        $decodedSignature = base64_decode($signatureData);

        // Check if the EmpId already exists
        $checkSql = "SELECT COUNT(*) FROM employee_signatures WHERE EmpId = :EmpId";
        $checkStmt = $dbh->prepare($checkSql);
        $checkStmt->bindParam(':EmpId', $EmpId, PDO::PARAM_STR);
        $checkStmt->execute();
        $exists = $checkStmt->fetchColumn();

        if ($exists) {
            // Show a JavaScript alert and redirect to attendance.php
            echo "<script>
                    alert('Signature already saved for this employee.');
                    window.location.href = 'attendance.php';
                  </script>";
            exit(); // Ensure the script stops executing after the redirect
        } else {
            // Create a unique file name for the signature image
            $fileName = 'signature_' . $EmpId . '_' . time() . '.png';
            $filePath = 'signatures/' . $fileName;

            // Save the signature image to the server
            if (file_put_contents($filePath, $decodedSignature) !== false) {
                // Insert EmpId, eid, and signature file path into the database
                $sql = "INSERT INTO employee_signatures (EmpId, eid, signature) VALUES (:EmpId, :eid, :signature)";
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':EmpId', $EmpId, PDO::PARAM_STR);
                $stmt->bindParam(':eid', $eid, PDO::PARAM_STR); // Bind the eid
                $stmt->bindParam(':signature', $filePath, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    // Redirect to a success page
                    header('Location: attendance.php');
                    exit(); // Ensure the script stops executing after the redirect
                } else {
                    echo "Error: Could not save signature to the database.";
                }
            } else {
                echo "Error: Could not save signature image.";
            }
        }
    } else {
        echo "No signature data provided.";
    }
} else {
    header('Location: index.php'); // Redirect if accessed directly
    exit(); // Ensure the script stops executing after the redirect
}
?>
