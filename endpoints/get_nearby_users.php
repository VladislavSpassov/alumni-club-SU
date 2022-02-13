<?php
    session_start();

    require_once(realpath(dirname(__FILE__) . '/../services/user_service.php'));

    $userService = new UserService();

    function get_all_nearby_users($userService) {
        return $userService->get_all_nearby_users();
    }

    echo json_encode([
        "success" => true,
        "message" => "List of all nearby users.",
        "value" => get_all_nearby_users($userService)
    ]);
?>