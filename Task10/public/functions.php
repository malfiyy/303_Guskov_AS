<?php
include 'rb.php';

R::setup('sqlite:../data/barbershop2.db');
R::useFeatureSet( 'novice/latest' );
if (!R::testConnection()) die('Database connect error.');

$masters = R::findAll('employee');

if (isset($_POST['edit-submit'])) {
    $edit_master = R::dispense('employee');

    $edit_master->last_name = $_POST['inputLastName'];
    $edit_master->name = $_POST['inputName'];
    $edit_master->second_name = $_POST['inputSecondName'];
    $edit_master->birth_date = $_POST['inputBirthDate'];
    $edit_master->phone_number = $_POST['inputPhoneNumber'];
    $edit_master->cost_percent = $_POST['inputCostPercent'];
    $edit_master->gender = $_POST['inputGender'];
    $edit_master->id = $_GET['id'];

    R::store($edit_master);
    header("Location: ".$_SERVER['REQUEST_URI']);
}

if (isset($_POST['add'])) {

    $edit_master = R::dispense('employee');

    $edit_master->last_name = $_POST['inputLastName'];
    $edit_master->name = $_POST['inputName'];
    $edit_master->second_name = $_POST['inputSecondName'];
    $edit_master->birth_date = $_POST['inputBirthDate'];
    $edit_master->phone_number = $_POST['inputPhoneNumber'];
    $edit_master->cost_percent = $_POST['inputCostPercent'];
    $edit_master->gender = $_POST['inputGender'];
    $edit_master->id = $_GET['id'];

    R::store($edit_master);
    header("Location: ".$_SERVER['REQUEST_URI']);	
}

if (isset($_POST['delete_submit'])) {
    $id = $_GET['id'];
    $master = R::load('employee', $id);
    R::trash($master);
    header("Location: ".$_SERVER['REQUEST_URI']);
}