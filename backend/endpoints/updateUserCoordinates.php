<?php
    session_start();

    require_once(realpath(dirname(__FILE__) . '/../services/userService.php'));

    $userService = new UserService();

    $phpInput = json_decode(file_get_contents('php://input'), true);
    header('Content-Type: application/json');

    $longitude = $phpInput['longitude'];
    $latitude = $phpInput['latitude'];

    try {
        $userService->updateCoordinates($longitude, $latitude);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage(),
        ]);
        exit();
    }

    echo json_encode([
        'success' => true,
        'message' => "The coordinates are updated successfully.",
    ]);
?>