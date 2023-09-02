<?php

/**
 * @Author: Dennis L.
 * @Test: 4
 * @TimeLimit: 30 minutes
 * @Testing: Input Sanitation
 */
// Fix this so there are no SQL Injection attacks, no chance for a man-in-the-middle attacks
// (e.g., use something to determine if the input was changed), and limit the chances of
// brute-forcing this credential system to gain entry. Feel free to change any part of this
// code.

/**
 * $_GET has been changed to $_POST
 * 
 * Login details, especially passwords should not be read via GET request. This would put the users password
 * in the request URL, which a clumsy user may share the URL unknowlingly. This would also appear in log files,
 * browser history and other places where the password would be leaked.
 */

if (!empty($_POST['username'])) {
    $username = $_POST['username'];
} else {
    exit("Username is required.");
}

if (!empty($_POST['password'])) {
    $password = $_POST['password'];
} else {
    exit("Password is required.");
}

/**
 * Use of a Cross Site Request Forgery token has been added
 * 
 * A secure token should be added to the Session (in the GET request for the login form for example)
 * whereby this token is then posted as a hidden input on the form. Here I am checking that this hidden
 * token field is included in the POST and matches the token in the Session. This ensures that this
 * POST came from our form, rather than a potential man-the-middle attack whereby an unknown form is
 * posting to our endpoint / login code.
 */
if (
    empty($_SESSION['csrf_token']) ||
    empty($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    exit("Access denied");
}

$pdo = new PDO('sqlite::memory:');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("DROP TABLE IF EXISTS users");
$pdo->exec("CREATE TABLE users (username VARCHAR(255), password VARCHAR(255))");

/**
 * MD5 has been changed to bcrypt with a salt (uses password_verify to check the password matches rather than
 * checking for an exact match in the database.) The password_hash method should be used to store these
 * passwords in the DB
 * 
 * Password hashing via MD5 is poor - the hash will always be the same if two users share the
 * same password, as no salt is used. This could lead to a brute force attack where many common
 * MD5 hashed passwords. The fact that MD5 is so quick is also what makes this brute force possible.
 */
$rootPassword = password_hash('secret', PASSWORD_DEFAULT);

/**
 * Variables in the SQL have been replaced with prepared statements and bound parameters
 * 
 * Leaving variables diectly in an executing SQL string is vunerable to SQL injection. These
 * variables havn't been escaped and therefore could manipulate the SQL being ran. Using PDO
 * bound parameters means they are safely escaped and quoted.
 */
$rootUserStatement = $pdo->prepare("INSERT INTO users (username, password) VALUES ('root', :rootPassword);");
$rootUserStatement->execute([':rootPassword' => $rootPassword]);

$statement = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$statement->execute([':username' => $username]);
$result = $statement->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    exit("Access deined for $username!<br>\n");
}

if (password_verify($password, $result['password'])) {
    echo "Access granted to $username!<br>\n";
} else {
    echo "Access denied for $username!<br>\n";
}
