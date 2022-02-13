<?php
    session_start();

    echo json_encode([
        "success" => true,
        "message" => "User role information.",
        "value" => $_SESSION['role']
    ]);
?>