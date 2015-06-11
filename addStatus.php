<?php
include('tools.php');

$database = new Database();

    if(isset($_POST['submit'])) {

        $data = new DataPreferences();

        $note = $_POST['note'];
        $data->validateInputData($note);

        $database->addNote($_SESSION['user_id'], $note);

    }

    if(isset($_GET['delete'])) {
        $data = new DataPreferences();
        $note_id = $_GET['delete'];
        $data->validateInputData($note_id);
        $note_id = (int)$note_id;
        $database->deleteNote($_SESSION['user_id'], $note_id);
    }

$notes = $database->getNotes($_SESSION['user_id']);

