<?php 
    include("./../config.php");
    $id = $_POST['animal_id'];
    if (isset($_POST['submitEdit']) && intval($id)) {
        $name = mysqli_real_escape_string($conn, $_POST['animal_name']);
        $type = mysqli_real_escape_string($conn, $_POST['animal_type']);
        $img = mysqli_real_escape_string($conn, $_POST['animal_img']);
        $habitat = mysqli_real_escape_string($conn, $_POST['habitat_ID']);
        
        $sql = "UPDATE animal
                SET animal_name = '$name',
                    animal_type = '$type',
                    animal_img = '$img',
                    habitat_ID = '$habitat'
                WHERE id = '$id' ";
        if (mysqli_query($conn, $sql)) {
            header("Location: ./../index.php#animaux");
            exit();
        }
    }
    else{
        header("Location: ./../index.php#animaux");
        exit();
    }
?>