<?php
    session_start();

    require_once(realpath(dirname(__FILE__) . '/../services/userService.php'));

    $userService = new UserService();

    function getUsers($userService) {
        return $userService->getAllUsers();
    }

    echo json_encode([
        "success" => true,
        "message" => "List of all posts.",
        "value" => getUsers($userService)
    ]);
?>