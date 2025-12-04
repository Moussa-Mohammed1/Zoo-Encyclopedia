<?php 
    include("./../config.php");

    if(isset($_POST['submitAnimal'])){
        $name = mysqli_real_escape_string($conn, $_POST['animal_name']);
        $type = mysqli_real_escape_string($conn, $_POST['animal_type']);
        $img = mysqli_real_escape_string($conn, $_POST['animal_img']);
        $habitat = mysqli_real_escape_string($conn, $_POST['habitat_ID']);

        $sql = "INSERT INTO animal (animal_name, animal_type, animal_img, habitat_ID) 
                VALUES ('$name', '$type', '$img', '$habitat')";
        if (mysqli_query($conn, $sql)) {
            header("Location: ./../index.php#animaux");
            exit();
        }
    }
    header("Location: ./../index.php#animaux");
    exit();
?>