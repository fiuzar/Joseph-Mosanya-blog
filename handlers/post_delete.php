<?php

    session_start();

    if(isset($_SESSION['id']) && $_SESSION['id'] === "admin_logged_in_34354545sfdsfdff") {
        include_once 'dbh.php';

        if (empty($_GET['id']) || trim($_GET['id']) === '') {
            header('Location: ../admin/index.php');
            exit();
        }

        $postId = trim($_GET['id']);
        $stmt = $conn->prepare("DELETE FROM blogs WHERE id = ?");
        $stmt->bind_param('i', $postId);
        $stmt->execute();
        $stmt->close();

        header('Location: ../admin/index.php?success=post_deleted');
        exit();
    } else {
        header("Location: ../admin/login.php?error=not_logged_in");
        exit();
    }

?>