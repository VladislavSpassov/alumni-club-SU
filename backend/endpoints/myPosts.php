<?php
    session_start();

    require_once(realpath(dirname(__FILE__) . '/../services/postService.php'));

    $postService = new PostService();

    function getPosts($postService) {
        return $postService->getMyPosts();
    }

    echo json_encode([
        "success" => true,
        "message" => "List of all posts.",
        "value" => getPosts($postService), 
        // "role" => $_SESSION['role']
    ]);
?>