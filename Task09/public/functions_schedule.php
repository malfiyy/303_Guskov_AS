<?php

include 'connect.php';


$master_id = $_GET['master_id'];
$sql = $pdo->prepare("SELECT id, last_name || ' ' || name || ' ' || second_name AS 'master' FROM employee
                                    WHERE id = ?");
$sql->execute([$master_id]);
$fio = $sql->fetchAll();

if (isset($_POST['addSсhedule'])) {


    $sql = ("INSERT INTO work_schedule (id_employee, work_date,
                start_time, end_time) VALUES (?,?,?,?)");
    $query = $pdo->prepare($sql);
    $query->execute(
        [
            $master_id,
            $_POST['inputDate'],
            $_POST['inputStartTime'],
            $_POST['inputEndTime']
        ]
    );

    header("Location: " . $_SERVER['REQUEST_URI']);
}

$sql = $pdo->prepare("SELECT work_schedule.id AS id,
                        work_schedule.id_employee AS id_master,
                        work_schedule.work_date AS day,
                        work_schedule.start_time AS start_time, 
                        work_schedule.end_time AS end_time
                      FROM work_schedule
                      WHERE id_employee = ?
                    ");
$sql->execute([$master_id]);
$result = $sql->fetchAll();


if (isset($_POST['edit-submit-schedule'])) {

    $id = $_GET['id'];

    $sql = "UPDATE work_schedule SET id_employee=?, work_date=?,
                start_time=?, end_time=?
            WHERE id=?";

    $query = $pdo->prepare($sql);
    $query->execute([
        $master_id,
        $_POST['inputDate'],
        $_POST['inputStartTime'],
        $_POST['inputEndTime'], 
        $id
    ]);

    header("Location: " . $_SERVER['REQUEST_URI']);
}

if (isset($_POST['delete_submit_sh'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM work_schedule WHERE id=?";
    $query = $pdo->prepare($sql);
    $query->execute([$id]);
    header("Location: " . $_SERVER['REQUEST_URI']);
}
