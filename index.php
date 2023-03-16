<?php

$host = "localhost";
$username = "root";
$password = "";
$dbname = "testtask";


// Создание подключения базы данных
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Выброс исключений при ошибке
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $connect) {
    echo "Ошибка подключения: ". $connect->getMessage();

    exit();
}


// входная строка с email-адресами

$email_string = "example1@mail.com;example2@mail.com;example3mail.com;example4@mail.com";

// делим строку на отдельные email-адреса, используя ";"
$emails = explode(";", $email_string);


$correct_emails = 0;
$incorrect_emails = 0;
$incorrect_emails_list = [];
foreach ($emails as $email) {
    // проверка email-адреса на корректность с помощью регулярного выражения
    if (preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
        // email-адрес корректный, добавляем его в базу данных
        $stmt = $pdo->prepare("INSERT INTO emails (email) VALUES (?)");
        $stmt->execute([$email]);
        $correct_emails++;
    } else {
        // email-адрес некорректный, добавляем его в список некорректных email-адресов
        $incorrect_emails++;
        $incorrect_emails_list[] = $email;
    }
}


echo "Количество корректных адресов: " . $correct_emails . "<br>";
echo "Количество некорректных адресов: " . $incorrect_emails . "<br>";
echo "Список некорректных адресов: <br>";
foreach ($incorrect_emails_list as $email) {
    echo $email . "<br>";
}


?>