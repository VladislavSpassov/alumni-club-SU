<?php
    session_start();

    require_once(realpath(dirname(__FILE__) . '/../services/userService.php'));

    $userService = new UserService();

    function getAllNearbyUsers($userService) {
        return $userService->getAllNearbyUsers();
    }

    echo json_encode([
        "success" => true,
        "message" => "List of all nearby users.",
        "value" => getAllNearbyUsers($userService)
    ]);
?>