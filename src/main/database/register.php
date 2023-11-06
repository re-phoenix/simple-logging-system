<?php

namespace database;

require_once __DIR__."/UserManagement.php";
require_once __DIR__."/User.php";

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if ( !empty(trim( $data['userId'] )) && isset($data['userPass']) && 
        isset($data['userRePass']) && isset($data['userEmail'])
    ) {
        $userId = (string)$data['userId'];  
        $userPass = (string)$data['userPass'];
        $userRePass = (string)$data['userRePass'];
        $userEmail = (string)$data['userEmail'];
        
        $userManager = new UserManagement();

        //REM: Not the best way of doing thing but it serve its purpose...
        $user = new User($userId, $userPass);
        $user->setUserEmail( $userEmail );
        $user->setSuperUser( false ); //REM: TODO-HERE: Watch out for this, needed more secure logical operations
        
        if ( !$userManager::isValidUserId( $userId ) ) {
            echo json_encode([
                'isSuccess' => false, 
                'isAdmin' => false,
                'message' => "Registration Failed! userId is invalid"
            ]);
        } else if ( $userPass !== $userRePass ) {
            echo json_encode([
                'isSuccess' => false, 
                'isAdmin' => false,
                'message' => "Registration Failed! Re-Password does not match with the prime password."
            ]);
        } else if ( empty(trim( $userId )) || empty(trim( $userPass )) || 
                    empty(trim( $userRePass )) || empty(trim( $userEmail )) 
        ) {
            echo json_encode([
                'isSuccess' => false, 
                'isAdmin' => false,
                'message' => "Registration Failed! Don't left out any input field."
            ]);
        } else if ( !filter_var($userEmail, FILTER_VALIDATE_EMAIL) /*|| $user->getUserEmail() != null */ ) {
            echo json_encode([
                'isSuccess' => false, 
                'isAdmin' => false,
                'message' => "Registration Failed! Invalid Email."
            ]);
        } else if ( !$userManager->add( $user ) /* || $userManager->verifyUserId( $username )*/ ) {
            echo json_encode([
                'isSuccess' => false, 
                'isAdmin' => false,
                'message' => 'Registration Failed! User/Account already exist.'
            ]);
        } else {
            // $user = new User($userId, $userPass);
            // $user->setUserEmail( $userEmail );
            // $user->setSuperUser( false );
            // $userManager->add( $user );
            echo json_encode([
                'isSuccess' => true, 
                'isAdmin' => ( $user->isSuperUser() )? true : false, //REM: not completely correct, must be handle by UserManagement... But for now it serve its purpose...
                'message' => "Registration successful! You can now log in."
            ]);
        }
    } 
    else {
        echo json_encode([
            'isSuccess' => false,
            'isAdmin' => false,
            'message' => 'Registration Failed! Please fill up the required Credentials.'
        ]);
    }
}