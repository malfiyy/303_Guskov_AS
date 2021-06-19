<?php
include 'connect.php';

if (isset($_POST['add'])) {
    $sql = "INSERT INTO employee (last_name, `name`, second_name, birth_date, phone_number, cost_percent, gender) VALUES (?, ?, ?, ?, ?, ?, ?)";


    $pdo->prepare($sql)->execute([
        $_POST['inputLastName'],
        $_POST['inputName'],
        $_POST['inputSecondName'],
        $_POST['inputBirthDate'],
        $_POST['inputPhoneNumber'],
        $_POST['inputCostPercent'],
        $_POST['inputGender']
    ]);

    header("Location: " . $_SERVER['REQUEST_URI']);
}

$sql = $pdo->prepare("SELECT master.id AS id,
                        master.name AS name,
                        master.last_name AS last_name,
                        master.second_name AS second_name,
                        master.cost_percent AS percent,
                        master.gender AS gender,
                        master.birth_date as birth_date,
                        master.phone_number as phone_number
                        FROM employee AS master
                    ");
$sql->execute();
$result = $sql->fetchAll();


if (isset($_POST['edit-submit'])) {
    $sqll = "UPDATE employee SET last_name=?, `name`=?, second_name=?, birth_date=?, phone_number=?, cost_percent=?, gender=?
            WHERE id=?";

    $querys = $pdo->prepare($sqll);

    print_r($querys);

    $querys->execute([
        $_POST['inputLastName'],
        $_POST['inputName'],
        $_POST['inputSecondName'],
        $_POST['inputBirthDate'],
        $_POST['inputPhoneNumber'],
        $_POST['inputCostPercent'],
        $_POST['inputGender'],
        $_GET['id']
    ]);

    header("Location: " . $_SERVER['REQUEST_URI']);
}

if (isset($_POST['delete_submit'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM employee WHERE id=?";
    $query = $pdo->prepare($sql);
    $query->execute([$id]);
    header("Location: " . $_SERVER['REQUEST_URI']);
}
