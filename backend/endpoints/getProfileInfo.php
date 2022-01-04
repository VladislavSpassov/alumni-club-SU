<?php
    session_start();

    require_once(realpath(dirname(__FILE__) . '/../entities/user.php'));
    require_once(realpath(dirname(__FILE__) . '/../services/userService.php'));

    $userService = new UserService();

    function getUserInfo($userService) {
        return $userService->getUser();
    }

    echo json_encode([
        "success" => true,
        "message" => "User information.",
        "value" => getUserInfo($userService)
    ]);
?>