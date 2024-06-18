PHP - <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve form data
  $firstName = $_POST["firstName"];
  $lastName = $_POST["lastName"];
  $email = $_POST["email"];
  $contactNumber = $_POST["contactNumber"];
  $message = $_POST["message"];

  // File upload handling
  $file = $_FILES["fileToUpload"];
  $fileName = $file["name"];
  $fileTmpName = $file["tmp_name"];
  $fileType = $file["type"];
  $fileError = $file["error"];
  $fileSize = $file["size"];

  // Check for file upload errors
  if ($fileError === 0) {
    // Read the file content
    $fileContent = file_get_contents($fileTmpName);
    $encodedContent = chunk_split(base64_encode($fileContent));

    // Prepare email with attachment
    $to = "connect@anywherecv.com"; // Replace with your email address
    $subject = "Contact Form Submission from $firstName $lastName";
    $separator = md5(time());
    $eol = "\r\n";
    $headers = "From: $email" . $eol;
    $headers .= "MIME-Version: 1.0" . $eol; 
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;

    $messageBody = "--" . $separator . $eol;
    $messageBody .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
    $messageBody .= "Content-Transfer-Encoding: 7bit" . $eol . $eol;
    $messageBody .= "Name: $firstName $lastName\nEmail: $email\nContact Number: $contactNumber\nMessage:\n$message" . $eol;

    // Attachment
    $messageBody .= "--" . $separator . $eol;
    $messageBody .= "Content-Type: $fileType; name=\"" . $fileName . "\"" . $eol; 
    $messageBody .= "Content-Transfer-Encoding: base64" . $eol;
    $messageBody .= "Content-Disposition: attachment" . $eol . $eol;
    $messageBody .= $encodedContent . $eol;
    $messageBody .= "--" . $separator . "--";

    // Send the email
    if (mail($to, $subject, $messageBody, $headers)) {
      echo "success";
    } else {
      echo "error";
    }
  } else {
    echo "error uploading file";
  }
}
?>