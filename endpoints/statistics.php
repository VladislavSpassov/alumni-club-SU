<?php
    session_start();

    require_once(realpath(dirname(__FILE__) . '/../services/statistics_service.php'));

    $statisticsService = new StatisticsService();

    echo json_encode([
        "success" => true,
        "message" => "List of all posts.",
        "faculty" => $statisticsService->groupUsersByFaculty(),
        "speciality" => $statisticsService->groupUsersBySpeciality(),
        "graduationYear" => $statisticsService->groupUsersByGraduationYear(),
        "userCount" => $statisticsService->getUsersCount(),
        "postCount" => $statisticsService->getPostsCount()
    ]);
?>