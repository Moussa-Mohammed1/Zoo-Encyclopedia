<?php 
    include("./../config.php");

    if(isset($_POST['submitHabitat'])){
        $name = mysqli_real_escape_string($conn, $_POST['nom_habitat']);
        $desc = mysqli_real_escape_string($conn, $_POST['description_hab']);

        $sql = "INSERT INTO habitat (habitat_name, habitat_desc) 
                VALUES ('$name','$desc')";
        // if (mysqli_query($conn, $sql)) {
        //     echo "<script>alert('habitat saved')</script>;";
        // }
        // else{
        //     echo "<script>alert('not saved !')</script>";
        // }
    }
    header('Location: ./../index.php#gestion-zoo');
    exit();
?>