<?php

$host = "localhost";
$username = "root";
$password = "root";
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



$emailChecker = new EmailChecker($pdo);


$emailChecker->checkAndInsertEmails('example1@mail.com;example2@mail.com;example3mail.com;example4@mail.com');


class EmailChecker {
    private $pdo;

    public function __construct(PDO $pdo)  {
        $this->pdo = $pdo;
    }

    public function checkAndInsertEmails(string $emailsSrting): void {
        $emails = explode(";", $emailsSrting);
        $correct_emails = 0;
        $incorrect_emails = 0;

        $incorrect_emails_list = [];


        foreach ($emails as $email) {
            // проверка email-адреса на корректность с помощью регулярного выражения
            if (preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
                $correct_emails++;
            } else {
                $incorrect_emails_list[] = $email;
                $incorrect_emails++;
            }
        }
        echo "Количество корректных адресов: {$correct_emails}\n";
        echo "Количество некорректных адресов: {$incorrect_emails}\n";

        if ($incorrect_emails > 0) {
            echo "Некорректные адреса: " . implode('; ',$incorrect_emails_list) . ".\n";
        }

    }


    // Добавление корректных email-адресов в БД
    private function insertEmail(string $email):void {
        $sql = "INSERT INTO emails (email) VALUES (:email)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    }   

}

?>