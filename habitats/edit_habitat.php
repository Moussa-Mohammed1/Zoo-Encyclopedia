<?php
    include("./../config.php");

    $id = $_POST['habitat-ID'];
    if (isset($_POST['submitEditHabitat']) && intval($id) > 0) {
        $name = mysqli_real_escape_string($conn, $_POST['nom-habitat']);
        $description = mysqli_real_escape_string($conn, $_POST['description-hab']);

        $sql = "UPDATE habitat
                SET habitat_name = '$name',
                    habitat_desc = '$description'
                WHERE habitat_ID = '$id'";
        if (mysqli_query($conn, $sql)) {
            header("Location: ./../index.php#gestion-zoo");
            exit();
        }
    }
    else{
        header("Location: ./../index.php#gestion-zoo");
        exit();
    }
?>