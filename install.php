<?php
	//Connect to database
    $servername = "localhost";
    $username = "root";		//put your phpmyadmin username.(default is "root")
    $password = "";			//if your phpmyadmin has a password put it here.(default is "root")
    $dbname = "";
	$port = 3306;
	$conn = new mysqli($servername, $username, $password, $dbname, $port);

	// Create database
	$sql = "CREATE DATABASE biometricattendace";
	if ($conn->query($sql) === TRUE) {
	    echo "Database created successfully";
	} else {
	    echo "Error creating database: " . $conn->error;
	}

	echo "<br>";

	$dbname = "biometricattendace";
    
	$conn = new mysqli($servername, $username, $password, $dbname, $port);

	// sql to create table
	$sql = "CREATE TABLE IF NOT EXISTS `users` (
			`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`username` varchar(100) NOT NULL,
			`class` varchar(100) NOT NULL,
			`phonenumber` varchar(100) NOT NULL,
			`gender` varchar(10) NOT NULL,
			`fingerprint_id` int(11) NOT NULL,
			`fingerprint_select` tinyint(1) NOT NULL DEFAULT '0',
			`user_date` date NOT NULL,
			`time_in` time NOT NULL,
			`del_fingerid` tinyint(1) NOT NULL DEFAULT '0',
			`add_fingerid` tinyint(1) NOT NULL DEFAULT '0'
	) ENGINE=InnoDB DEFAULT CHARSET=latin1";

	if ($conn->query($sql) === TRUE) {
	    echo "Table users created successfully";
	} else {
	    echo "Error creating table: " . $conn->error;
	}

	$sql = "CREATE TABLE IF NOT EXISTS `users_logs` (
			`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`username` varchar(100) NOT NULL,
			`class` varchar(100) NOT NULL,
			`phonenumber` varchar(100) NOT NULL,
			`fingerprint_id` int(5) NOT NULL,
			`checkindate` date NOT NULL,
			`timein` time NOT NULL,
			`timeout` time NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=latin1";

	if ($conn->query($sql) === TRUE) {
	    echo "Table users_logs created successfully";
	} else {
	    echo "Error creating table: " . $conn->error;
	}
		
	$sql = "CREATE TABLE IF NOT EXISTS `admin` (
		`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`username` VARCHAR(50) NOT NULL UNIQUE,
		`password` VARCHAR(255) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=latin1";
	
	if ($conn->query($sql) === TRUE) {
		echo "Table `admin` created successfully";
	} else {
		echo "Error creating table: " . $conn->error;
	}
	
	// Chèn bản ghi admin với mật khẩu mã hóa MD5
	$admin_username = 'admin';
	$admin_password = '21232f297a57a5a743894a0e4a801fc3'; // MD5 của "admin"

	// Sử dụng Prepared Statement để tránh SQL Injection
	$stmt = $conn->prepare("INSERT INTO `admin` (username, password) VALUES (?, ?)");
	$stmt->bind_param("ss", $admin_username, $admin_password);

	if ($stmt->execute()) {
		echo "Admin record inserted successfully";
	} else {
		echo "Error inserting record: " . $stmt->error;
	}
	$conn->close();
?>