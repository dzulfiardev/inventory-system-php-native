<?php
// database_connection.php

$connect = new PDO("mysql:host=localhost;dbname=inventory_system_test", 'root', '');
session_start();
