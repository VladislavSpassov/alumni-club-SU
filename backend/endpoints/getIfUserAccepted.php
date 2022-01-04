<?php
    session_start();

    require_once(realpath(dirname(__FILE__) . '/../services/postService.php'));

    $postService = new PostService();

    $answer;

    try {
        $answer = $postService->getIfUserAccepted($_GET["postId"]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage(),
        ]);
        exit();
    }

    echo json_encode([
        'success' => true,
        'message' => "Getting if user accepted is successful.",
        'value' => $answer
    ]);
?>
