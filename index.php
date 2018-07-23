<?php

require_once 'config.php';

//Создать новую таблицу через php.
$query = $pdo->exec("DROP TABLE IF EXISTS `cats`;");

$query = $pdo->exec("CREATE TABLE `cats` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(10) NULL,
    `fname` VARCHAR(20) NOT NULL DEFAULT '',
    `email` VARCHAR(50) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$query = $pdo->exec("INSERT INTO `cats` VALUES
    (null, 'Федя', 'Радмарис', 'radmaris_ferz@gmail.cocom'),
    (null, 'Котя', 'Сиамская', 'verybadcat@yandex.ruru')
");

//Сделать страницу, где будет выводиться список таблиц текущей базы данных.
$statement = $pdo->prepare("SHOW TABLES FROM $dbname");
$statement->execute();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Tables</title>
  </head>
  <body>
    <ul>Список таблиц базы данных <?php echo $dbname; ?>
      <?php foreach ($statement as $value) { ?>
        <li>
          <a href="describeTable.php?go=<?=$value[0]?>"><?php echo "$value[0]"; ?></a>
        </li>    
      <?php } ?>
    </ul>
  </body>
</html>