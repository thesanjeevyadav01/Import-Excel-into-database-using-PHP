<?php 
require 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import excel into MySQL using php</title>
</head>
<body>
    <form action="" class="" enctype="multipart/form-data" method="post">
        <input type="file" name="excel" required value="">
        <button type="submit" name="import">Import</button>
    </form>

    <table border=1>
        <tr>
            <td>#</td>
            <td>Name</td>
            <td>Age</td>
            <td>Country</td>
        </tr>
        <?php $i=1;
        $rows=mysqli_query($conn, "SELECT * FROM tb_data");
        foreach($rows as $row):
        ?>
        <tr>
            <td><?php echo $i++;?></td>
            <td><?php echo $row["name"];?></td>
            <td><?php echo $row["age"];?></td>
            <td><?php echo $row["country"];?></td>
        </tr>

        <?php endforeach; ?>

    </table>

    <?php
    if(isset($_POST["import"])){
        $filename = $_FILES["excel"]["name"];
        $fileExtension = explode('.',$filename);
        $fileExtension = strtolower(end($fileExtension));

        $newFileName = date("Y.m.d") . " - " .date("h.i.sa") . "." . $fileExtension;
        $targetDirectory = "uploads/" . $newFileName;
        move_uploaded_file($_FILES["excel"]["tmp_name"],$targetDirectory);

        error_reporting(0);
        ini_set('display_errors',0);

        require 'excelReader/excel_reader2.php';
        require 'excelReader/SpreadsheetReader.php';

        $reader = new SpreadsheetReader($targetDirectory);
        foreach($reader as $key => $row){
            $name = $row[0];
            $age = $row[1];
            $country = $row[2];
            
            mysqli_query($conn, "INSERT INTO tb_data VALUES('','$name','$age','$country')");
           

        }
        echo
        "
        <script>
        alert('Successfully Imported');
        document.location.href = '';
        </script>

        
        ";
    }
    ?>
</body>
</html>
