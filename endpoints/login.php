<?php
    require_once(realpath(dirname(__FILE__) . '/../data_models/user.php'));
    require_once(realpath(dirname(__FILE__) . '/../services/user_service.php'));


    $userService = new UserService();

    $phpInput = json_decode(file_get_contents('php://input'), true);
    if (!isset($phpInput['username']) || !isset($phpInput['password'])) {
        echo json_encode([
            'success' => false,
            'message' => "Моля, попълнете потребителско име и парола.",
        ]);
    } else {
        if (empty($phpInput['username']) || empty($phpInput['password'])) {
            echo json_encode([
                'success' => false,
                'message' => "Моля, попълнете потребителско име и парола.",
            ]);
        }
        else {
            $username = $phpInput['username'];
            $password = $phpInput['password'];
            try {
                $userId = $userService->check_login($username, $password);
                $_SESSION['userId'] = $userId;

                $info = $userService->get_user_role_data()["data"]->fetch(PDO::FETCH_ASSOC);
                $_SESSION['role'] = $info["role"];
                $_SESSION['faculty'] = $info["faculty"];
                $_SESSION['speciality'] = $info["speciality"];
                $_SESSION['groupUni'] = $info["groupUni"];
                $_SESSION['graduationYear'] = $info["graduationYear"];

                $_SESSION['username'] = $phpInput['username'];

                echo json_encode([
                    'success' => true,
                    'username' => $_SESSION['username'],
                ]);
            } catch (Exception $e) {
                
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]);
            }
        }  
    }
?>