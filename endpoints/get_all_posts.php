<?php
    session_start();

    require_once(realpath(dirname(__FILE__) . '/../services/post_service.php'));

    $postService = new PostService();

    function getPosts($postService) {
        return $postService->filter_posts();
    }

    echo json_encode([
        "success" => true,
        "message" => "List of all posts.",
        "value" => getPosts($postService)
    ]);
?>