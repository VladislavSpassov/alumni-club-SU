<?php
    session_start();

    require_once(realpath(dirname(__FILE__) . '/../services/postService.php'));

    $postService = new PostService();

    $phpInput = json_decode(file_get_contents('php://input'), true);
    header('Content-Type: application/json');

    $occasion = $phpInput['occasion'];
    $privacy = $phpInput['privacy'];
    $occasionDate = $phpInput['occasionDate'];
    $location = $phpInput['location'];
    $content = $phpInput['content'];

    $post = new Post(null, $occasion, $privacy, $occasionDate, $location, $content);
    try {
        $postService->createPost($post);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage(),
        ]);
        exit();
    }

    echo json_encode([
        'success' => true,
        'message' => "The post is created successfully.",
    ]);
?>