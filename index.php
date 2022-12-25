<?php 

require_once "config/connect.php";
require_once "config/functions.php";

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заметки</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>


    <div class='wall'>
        <?php

        echo "<form action='".set_comment($connect)."' enctype='multipart/form-data' method='post' class='add_note'>
            <div class='add_note'>
                <div class='heading'>
                    <h1>Добро пожаловать на стену заметок!</h1>
                </div>
                <div>
                    <textarea name='title' class='note' placeholder='Заголовок'></textarea>
                </div>
                <div>
                    <textarea name='message' class='message' placeholder='Описание заметки'></textarea>
                </div>
                <a href='index.php'><button type='submit' name='comment_submit' class='btn_add_note'>Добавить</button></a>
                <br><br><br>
            </div>
        </form>";

        get_comments($connect);

        ?>
    </div>
    

</body>
</html>