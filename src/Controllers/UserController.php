<?php

namespace App\Controllers;

use App\Connection\Connection;
use App\Request\Request;
use App\Response\Response;
use Exception;
class UserController
{

    public static function createUser(Request $request): Response
    {
        try {
            $username = $request->getParam('username');
            $password = $request->getParam('password');
            $firstname = $request->getParam('firstname');
            $lastname = $request->getParam('lastname');
            $email = $request->getParam('email');
            // Hash the password (you should use a secure hashing algorithm)
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Get the current date for the created_at field
            $createdAt = date('Y-m-d H:i:s');

            // Data to be inserted into the 'users' table
            $userData = [
                'username' => $username,
                'password' => $hashedPassword,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'created_at' => $createdAt,
                'email' => $email,
            ];

            // Instantiate the Connection class
            $connection = Connection::getInstance();

            // Insert the new user into the 'users' table
            $userId = $connection->insert('users', $userData);

            if ($userId) {
                // User creation successful
                return new Response('User created successfully');
            } else {
                // User creation failed
                return new Response('Failed to create user', 500);
            }
        } catch (Exception $e) {
            // Log the error or handle it as needed
            return new Response('Error creating user: ' . $e->getMessage(), 500);
        }
    }

}