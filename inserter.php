<html>
<head>
  <title>
    <?php
    session_start();
    if (!isset($_SESSION['name1'])) {
        header("Location: index.php");
    }
    if ($_SESSION['access_level1']>2) {
      header( "Location: home.php");  
  }
  ?>
</title>
</head>
<body>

  <?php
  require_once('db/db_config.php');
/*Recieve data from add member to insert in db*/
  $Name1   = mysqli_real_escape_string($connection, $_POST['Name']);
  $DOB1    = mysqli_real_escape_string($connection, $_POST['DOB']);
  $DOJ1    = mysqli_real_escape_string($connection, $_POST['DOJ']);
  $Gender1 = mysqli_real_escape_string($connection, $_POST['Gender']);
  $Height1 = mysqli_real_escape_string($connection, $_POST['Height']);
  $Weight1 = mysqli_real_escape_string($connection, $_POST['Weight']);

  $Address1 = mysqli_real_escape_string($connection, $_POST['Address']);
  $Contact1 = mysqli_real_escape_string($connection, $_POST['Contact']);
  $Email1   = mysqli_real_escape_string($connection, $_POST['Email']);
  $Form_No1 = mysqli_real_escape_string($connection, $_POST['Form_No']);

  $Occupation1        = mysqli_real_escape_string($connection, $_POST['Occupation']);
  $Marital_Status1    = mysqli_real_escape_string($connection, $_POST['Marital_Status']);
  $Emergency_Name1    = mysqli_real_escape_string($connection, $_POST['Emergency_Name']);
  $Emergency_Contact1 = mysqli_real_escape_string($connection, $_POST['Emergency_Contact']);

/*Changing date format to suit the db insertion*/
  $dt   = DateTime::createFromFormat('m/d/Y', $DOB1);
  $DOB1 = $dt->format('Y-m-d');

  $dt1  = DateTime::createFromFormat('m/d/Y', $DOJ1);
  $DOJ1 = $dt1->format('Y-m-d');



//query to insert data into db
  $query = mysqli_query($connection, "insert into member_info (name,dob,doj,gender,m_status,height,weight,occupation,address,contact,email,e_name,e_contact,form_no) values ('$Name1','$DOB1','$DOJ1','$Gender1','$Marital_Status1','$Height1','$Weight1','$Occupation1','$Address1','$Contact1','$Email1','$Emergency_Name1','$Emergency_Contact1','$Form_No1') ") or die(mysqli_error());


//query to fetch UID to link with user image
  $sql = mysqli_query($connection, "select * from member_info where name like '$Name1' and contact like '$Contact1'");
  while ($row = mysqli_fetch_array($sql)) {
    $member_id = $row['member_id'];
}


//code for image uploading to server        
$ext           = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
$target_dir    = "images/";
$target_file   = $target_dir . basename($member_id . "." . $ext);
$uploadOk      = 1;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);


// Check if image file is a actual image or fake image
$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
if ($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
} else {
    echo "File is not an image.";
    $uploadOk = 0;
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}


// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}


// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}


// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    
    
    // if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file " . basename($member_id . "." . $ext) . " has been uploaded.";

        /*Inserting image name & extension in db to avoid confusion of diff extensions*/
        $extension = $member_id . "." . $ext;
        print($extension);
        echo "update member_info set image_ext=$extension where member_id=$member_id";
        $query = mysqli_query($connection, "update member_info set image_ext='$extension' where member_id=$member_id") or die(mysqli_error());

    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}


header("Location: add_package.php?id=$member_id");
?>
</body>
</html>