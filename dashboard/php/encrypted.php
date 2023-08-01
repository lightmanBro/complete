<?php
//ENCRYPTING AND DECRYPTING PASSWORDS
$password = "password123"; // The password you want to hash

// Hash the password using MD5
$hashedPassword = md5($password);

// Store $hashedPassword in your database or wherever you need to save it
$passwordFromUser = "password123"; // The password entered by the user
$hashedPasswordFromDB = "5f4dcc3b5aa765d61d8327deb882cf99"; // Retrieve hashed password from the database

// Verify the password
if (md5($passwordFromUser) === $hashedPassword) {
    // Password is correct
    echo "Password is correct";
} else {
    // Password is incorrect
    echo "Password is incorrect";
}

echo $hashedPassword;