<?php
try {
	$pdo = new PDO('sqlite:../data/barbershop2.db');
} catch (PDOException $e) {
	die('Database connect error: ' . $e->getMessage());
}
