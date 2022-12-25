# Реализация шаблона CRUD

## Цель работы:
Разработать и реализовать клиент-серверную информационную систему, реализующую механизм CRUD.

## Задание: 
- добавление текстовых заметок в общую ленту
- реагирование на чужие заметки (лайки)

## Пользовательский интерфейс

Форма ввода комментариев![input.png](input.png)

Комментарии пользователей![output.png](output.png)


##  Пользовательский сценарий работы

#### API сервера и хореография
Сервер использует HTTP POST запросы с полями заголовка и текста комментария.

**Функция добавления комментариев на сайт:**
с помощью POST запроса отправляются такие данные как: дата, заголовок и текст комментария.

**Функция вывода комментариев на сайте:**
из базы данных берётся только 100 последних комментариев (их дата добавления, заголовок и текст) и присваивает каждому имя пользователя "Аноним".

#### Пользовательский сценарий работы
При входе на страницу пользователь видит приветствие "Добро пожаловать в ленту комментариев" и форму для ввода комментария: поля заголовка и комментария.

## Структура базы данных
- **id** (Уникальный идентификатор комментария): INT(11), AUTO_INCREMENT
- **title** (Заголовок): VARCHAR(1024), по умолчанию NULL
- **message** (Текст комментария): TEXT, NULL
- **date** (Дата и время создания записи): DATETIME, NULL 
- **likes** (Количество лайков на комментарии): INT(11), NULL

## Алгоритмы

- **Алгоритм создания комментария**

![add.png](add.png)

Пользователь может ввести только заголовок и комментарий. Так как лента комментариев анонимная, то у всех пользователей автоматически добавляется имя: Аноним. Также каждому комментарию присваивается дата и время, когда он был отправлен.

![comm.png](comm.png)


- **Алгоритм реакций на комментарии**

Пользователь может выбрать для одну из двух реакций: Like или Dislike. Нажимая на каждую из них количество выбранных реакций увеличивается.

![react.png](react.png)



## Программный код, реализующий систему

#### Реализация добавления комментария в БД
```php
function setComments($conn) {
    if(isset($_POST['commentSubmit'])) {
        $date = $_POST['date'];
        $title = $_POST['title'];
        $message = $_POST['message'];
        $sql = "INSERT INTO comments (date, title, message) VALUES ('$date', '$title', '$message')";
        $result = $conn->query($sql);
        unset($_POST['date']);
        unset($_POST['title']);
        unset($_POST['message']);
    }
}
```
###  Реализация вывода комментариев с лайками на сайт
```php
function getComments($conn) {
    $sql = "SELECT DISTINCT* FROM comments ORDER BY date DESC LIMIT 100";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<div class='comment'>
            <br>
            <div class='name'> Аноним </div>
            ".$row['date']."<br>
            <div class='title'> ".$row['title']."<br> </div>
            ".$row['message']."
            <div><br><form method='POST' action='".likeSubmit($row)."'> <button type='submit' name='".$row['id']."' class='likebtn'>♡ Like</button>  Likes: ".$row["likes"]."</form></div>
            <br>
        </div>
        <br>";
    }
}
```
### Реализация лайков к комментариям
```php
function likeSubmit($row) {    
    require("connection.php");
    if(isset($_POST[$row['id']])) {
        $id = $row['id'];
        $likes = $row['likes']+1;
        $query = "UPDATE comments SET likes = '$likes' WHERE id = '$id'";
        $result = mysqli_query($conn, $query);
        header('Location: main.php');
        exit;
    }
}
```
