<?php
$servername = "localhost";
$username = "root";
$password = "";

/*
** Creating and checking connection
*/

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

/*
** Create database
*/

$sql = "CREATE DATABASE IF NOT EXISTS camagru";
$conn->query($sql);
$conn->close();

/*
** Create users table.
*/

$conn = new mysqli($servername, $username, $password, 'camagru');
$sql = "CREATE TABLE users (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name varchar(255),
	username varchar(255),
	email varchar(255),
	password varchar(255),
	verified int DEFAULT 0,
	notify_on_comment int DEFAULT 1,
	created_at datetime DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);

/*
** Create tokens table.
*/

$sql = "CREATE TABLE tokens (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id int NOT NULL,
	token varchar(255)
)";
$conn->query($sql);

/*
** Create registration_keys table.
*/

$sql = "CREATE TABLE registration_keys (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	v_key varchar(255),
	email varchar(255)
)";
$conn->query($sql);

/*
** Create posts table.
*/

$sql = "CREATE TABLE posts (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id int NOT NULL,
	title varchar(255),
	body text,
	img mediumtext,
	created_at datetime DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);

  $sql = "CREATE TABLE password_reset_keys (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	email varchar(255),
	reset_key varchar(255)
)";
$conn->query($sql);

/*
** Create likes table.
*/

$sql = "CREATE TABLE likes (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	post_id int NOT NULL,
	user_id int NOT NULL,
	created_at datetime DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);

/*
** Create comments table.
*/

$sql = "CREATE TABLE comments (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	post_id int NOT NULL,
	user_id int NOT NULL,
	body text,
	created_at datetime DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);
$conn->close();
?>

