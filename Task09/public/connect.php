<?php
try {
	$pdo = new PDO('sqlite:../data/barbershop.db');
} catch (PDOException $e) {
	die('Database connect error: ' . $e->getMessage());
}
