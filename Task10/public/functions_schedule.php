<?php

include 'rb.php';
R::setup('sqlite:../data/barbershop2.db');
R::useFeatureSet( 'novice/latest' );
if (!R::testConnection()) die('Database connect error.');


$master_id = $_GET['master_id'];

$master = R::load('employee', $master_id);

$fio = $master['last_name'].' '.$master['name'].' '.$master['second_name'];

$workschedule = R::findLike('workschedule', array('id_employee' => $master_id), 'ORDER BY id');

if (isset($_POST['addSсhedule'])) {

    $new = R::dispense('workschedule');

    $new->id_employee = $master_id;
    $new->work_date =$_POST['inputDate'];
    $new->start_time = $_POST['inputStartTime'];
    $new->end_time = $_POST['inputEndTime'];

    // print_r($new);
    echo 222222;

    R::store($new);
    header("Location: " . $_SERVER['REQUEST_URI']);
}



if (isset($_POST['edit-submit-schedule'])) {

    $id = $_GET['id'];
    
    $new = R::dispense('workschedule');
    $new->work_date = $_POST['inputDate'];
    $new->start_time = $_POST['inputStartTime'];
    $new->end_time = $_POST['inputEndTime'];
    $new->id = $id;

    R::store($new);
    header("Location: " . $_SERVER['REQUEST_URI']);
}

if (isset($_POST['delete_submit_schedule'])) {
    $trash = R::load('workschedule', (int)$_GET['id']);
    R::trash($trash);
	header("Location: ".$_SERVER['REQUEST_URI']);
}
