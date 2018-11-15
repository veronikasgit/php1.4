
<?php

    if ($argv[1] === "--today") {

        $d = date('Y-m-d');
     
        $f = fopen("file.csv", "rt")  or die ("Ошибка!");

        $sum = [];
        for ($i=0; $data = fgetcsv($f,1000,","); $i++) {
          
            if ($data[0] === date('Y-m-d')) {
            $price[] = $data[1];     
            }

            $sum = array_sum($price);
          
        }

        echo "$d расход за день: " . $sum . ".00";

        fclose($f);

    } elseif (empty($argv[1]) || empty($argv[2])) {
        echo "Ошибка! Аргументы не заданы. Укажите флаг --today или запустите скрипт с аргументами {цена} и {описание покупки}";

    } else {
 
        $descript = array_slice($argv, 2);
        $description = implode(' ', $descript);

        $d = date('Y-m-d');

        $row = [];
        $row[0] = $d;
        $row[1] = $argv[1];
        $row[2] = $description;

        $arr = [] ;
        $arr[] = $row;

        $fp = fopen('file.csv', 'a+');

        foreach ($arr as $key => $fields) {
            fputcsv($fp, $fields);
        }

        echo "Добавлена строка: $row[0], $row[1], $row[2]";

        fclose($fp);

    }
?>