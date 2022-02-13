<?php
    session_start();

    require_once(realpath(dirname(__FILE__) . '/../data_models/user.php'));
    require_once(realpath(dirname(__FILE__) . '/../services/user_service.php'));

    $user_service = new UserService();

    echo json_encode([
        "success" => true,
        "message" => "User information.",
        "value" => $user_service->get_user()
    ]);
?>