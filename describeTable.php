<?php

require_once 'config.php';

//В каждую таблицу можно зайти и увидеть название и тип поля.
//Добавить возможность удалить поле, изменить его тип или название.
$table_name = (string)($_GET['go']);

if (isset($_GET['delete']) && $_GET['delete'] != 'all') {
    $column = (string)($_GET['delete']);
    $query = "ALTER TABLE $table_name DROP $column";

    $statement = $pdo->prepare($query);
    $statement->execute();
}

if (isset($_GET['delete']) && $_GET['delete'] == 'all') {
    $query = "DROP TABLE IF EXISTS $table_name";

    $statement = $pdo->prepare($query);
    $statement->execute();
    die ("Таблица $table_name удалена из базы данных $dbname. <a href=\"index.php\">Назад</a>");
}

if (isset($_POST['editField'])) {
    $column = $_GET['edit'];
    $newColumn = (string)($_POST['field']);
    $type = (string)($_POST['type']);
    $x = substr(substr($_POST['type'], 8), 0, -1);
    $y = substr(substr($_POST['type'], 4), 0, -1);
    //здесь должна быть проверка на правильность названия и типа поля. 
    // int(11), varchar(255), text
    if (preg_match("/^[a-zA-Z][a-zA-Z0-9_]{1,}$/", $newColumn) 
    && (($type == "int($y)" && $y <= 11) 
    || ($type == "varchar($x)" && $x <= 255)
    || ($type == 'text'))) {
        
        $query = "ALTER TABLE $table_name CHANGE $column $newColumn $type";

        $statement = $pdo->prepare($query);
        $statement->execute();
    } else {
        echo "Некорректное название или тип поля. <a href=\"https://www.opennet.ru/docs/RUS/sql/#Syntax\">Ознакомьтесь с документацией.</a>";
    } 
} 

if (isset($_GET['go'])) {
    $query = "DESCRIBE $table_name";

    $statement = $pdo->prepare($query);
    $statement->execute();

    while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $row1[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Table <?php echo $table_name; ?></title>
    <style>
      table { 
        border-spacing: 0;
        border-collapse: collapse;
      }

      table td, table th {
        border: 1px solid #ccc;
        padding: 5px;
      }
    
       table th {
        background: #eee;
      }

      table caption {
        font: bold 110% serif;
      }
      table a {
        text-decoration: none;
      }
  </style>
  </head>
  <body>
    <?php if (!empty($_GET)) { ?>
      <table>
        <caption>Таблица <?php echo $table_name; ?></caption>
        <tr>  
          <th>Название поля</th>
          <th>Тип поля</th>
          <th></th>
        </tr>

        <?php if (isset($row1)) {
            foreach ($row1 as $value) { ?>
              <tr>
                <td>
                  <?php echo $value['Field']; ?> 
                </td>
                <td>
                  <?php echo $value['Type']; ?>
                </td>
                <td>
                  <a href="?go=<?=$table_name?>&edit=<?=$value['Field']?>&type=<?=$value['Type']?>">Изменить</a>
                  <?php if (count($row1) > 1) { ?>
                    <a href="?go=<?=$table_name?>&delete=<?=$value['Field']?>">Удалить</a>
                  <?php } else { ?>
                    <a href="?go=<?=$table_name?>&delete=all">Удалить</a>
                  <?php } ?>  
                </td>                 
              </tr>
        <?php } } ?>
      </table>
      <p>
        <?php if (isset($_GET['edit'])) { ?>
          <form method="POST">  
            <input type="text" name="field" value="<?=$_GET['edit']?>">
            <input type="text" name="type" value="<?=$_GET['type']?>">
            <input type="submit" name="editField" value="Изменить">
          </form>
        <?php } ?>
      </p>
      <p>
        <a href="index.php">Назад</a>
      </p>
    <?php } else { ?>
      <p>
        <a href="index.php">Выберите таблицу</a>
      </p>
    <?php } ?>
  </body>
</html>