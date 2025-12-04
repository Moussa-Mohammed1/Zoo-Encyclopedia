<?php 
    include("./../config.php");

    $id = $_GET['id'];
    if ($id) {
        $sql = "DELETE FROM animal
                WHERE id = '$id'";
        if (mysqli_query($conn, $sql)) {
            header("Location: ./../index.php#animaux");
            exit();
        }
        else{
            header("Location: ./../index.php#animaux");
            exit();
        }
    }
    else{
        header("Location: ./../index.php#animaux");
        exit();
    }
?>