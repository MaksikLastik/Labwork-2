# Реализация шаблона CRUD

## Цель работы:
Разработать и реализовать клиент-серверную информационную систему, реализующую механизм CRUD.

## Задание: 
- добавление текстовых заметок в общую ленту
- реагирование на чужие заметки (лайки)

## Пользовательский интерфейс

Форма ввода комментариев![Форма ввода заметок.png](Форма ввода заметок.png)

Комментарии пользователей![комментарий](https://github.com/MaksikLastik/Labwork-2/blob/main/images/for%20README/Заметки%20пользователей.png)


##  Пользовательский сценарий работы

#### API сервера и хореография
Сервер использует HTTP POST запросы с полями заголовка и текста заметки.

**Функция добавления комментариев на сайт:**
с помощью POST запроса отправляются такие данные как, заголовок и текст заметки.

**Функция вывода комментариев на сайте:**
из базы данных берётся только 100 последних комментариев (их дата добавления, заголовок и текст) и присваивает каждому имя пользователя "Аноним".

#### Пользовательский сценарий работы
При входе на страницу пользователь видит приветствие "Добро пожаловать на стену заметок!" и форму для ввода комментария: поля заголовка и заметки.

## Структура базы данных
- **id** (Уникальный идентификатор комментария): INT(11), AUTO_INCREMENT
- **title** (Заголовок): VARCHAR(1024), по умолчанию NULL
- **message** (Текст комментария): TEXT, NULL
- **date** (Дата и время создания записи): DATETIME, NULL 
- **likes** (Количество лайков на комментарии): INT(11), NULL

## Алгоритмы

- **Алгоритм создания комментария**

![создание](https://github.com/MaksikLastik/Labwork-2/blob/main/images/for%20README/Алгоритм%20создания%20заметки.png)

Пользователь может ввести только заголовок и заметки. Так как стена заметок анонимная, то у всех пользователей автоматически добавляется имя: Аноним. Также каждой заметке присваивается дата и время, когда он был отправлен.

![аноним](https://github.com/MaksikLastik/Labwork-2/blob/main/images/for%20README/Заметка.png)


- **Алгоритм реакций на комментарии**

Пользователь может оценить заметку кнопкой с иконкой лайка. Нажимая на ее количество лайков увеличивается с каждым разом на 1 увеличивается.

![реакция](https://github.com/MaksikLastik/Labwork-2/blob/main/images/for%20README/Реагирование%20на%20заметку.png)



## Программный код, реализующий систему

#### Реализация добавления заметки в БД
```php
function set_comment($connect) {
    require_once "connect.php";
    if(isset($_POST['comment_submit'])) {
        $title = $_POST['title'];
        $message = $_POST['message'];

        mysqli_query($connect, "INSERT INTO `comments` (`id`, `title`, `message`, `date`, `likes`) VALUES (NULL, '$title', '$message', NOW(), NULL)");

        unset($_POST['title']);
        unset($_POST['message']);
    }
}
```
###  Реализация вывода заметок с лайками на сайт
```php
function get_comments($connect) {
    $result = mysqli_query($connect, "SELECT * FROM comments ORDER BY date DESC LIMIT 100");
    while ($block = mysqli_fetch_assoc($result)) {
        echo "<div class='comment'>
            <br>
            <div class='name'>Аноним</div>
            От ".$block['date']."<hr>
            <div class='title'>".$block['title']."<br></div><br>
            ".$block['message']."
            <div><br><form method='post' action='".add_like($block)."'><button type='submit' name='".$block['id']."' class='like'><img class='img' src='../images/for site/like.png'> ".$block['likes']."</button></form></div>
        </div>
        <br>";
    }
}
```
### Реализация лайков в заметках
```php
function add_like($block) {
    require("connect.php");
    if (isset($_POST[$block['id']])) {
        $id = $block['id'];
        $likes = $block['likes'] + 1;

        mysqli_query($connect, "UPDATE comments SET likes = '$likes' WHERE id = '$id'");
        
        header('Location: ../index.php');
        exit;
    }
}
```
