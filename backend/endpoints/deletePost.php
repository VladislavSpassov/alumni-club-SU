<?php
    session_start();

    require_once(realpath(dirname(__FILE__) . '/../services/postService.php'));

    $postService = new PostService();

    $phpInput = json_decode(file_get_contents('php://input'), true);
    header('Content-Type: application/json');

    $postId = $phpInput['postId'];

    try {
        $postService->deletePost($postId);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage(),
        ]);
        exit();
    }

    echo json_encode([
        'success' => true,
        'message' => "The post is deleted successfully.",
    ]);
