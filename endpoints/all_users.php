<?php
    session_start();

    require_once(realpath(dirname(__FILE__) . '/../services/user_service.php'));

    $userService = new UserService();

    function get_users($userService) {
        return $userService->get_all_users();
    }

    echo json_encode([
        "success" => true,
        "message" => "List of all posts.",
        "value" => get_users($userService)
    ]);
?>