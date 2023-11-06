<?php

namespace database;

require_once __DIR__."/UserManagement.php";

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['userId']) && isset($data['userPass'])) {
        $userId = (string)$data['userId'];
        $userPass = (string)$data['userPass'];
        $userManager = new UserManagement();
        if ( $userManager->verifyUser( $userId, $userPass ) ) {
            echo json_encode([
                'isSuccess' => true,
                'isAdmin' => true,
                'message' => 'LogIn Successful!'
            ]);
        } else {
            echo json_encode([
                'isSuccess' => false,
                'isAdmin' => false,
                'message' => 'LogIn Failed! Invalid Credentials!'
            ]);
        }
    } else {
        echo json_encode([
            'isSuccess' => false,
            'isAdmin' => false,
            'message' => 'LogIn Failed! Please fill up the required Credentials.'
        ]);
    }
}
?>