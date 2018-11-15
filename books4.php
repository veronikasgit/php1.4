<?php

$query_array = array_slice($argv, 1);
$query_string = implode(' ', $query_array);
$query = urlencode($query_string);

echo PHP_EOL;
echo "Вы ввели: $query_string\n";

$file = file_get_contents("https://www.googleapis.com/books/v1/volumes?q=$query");

$json = json_decode($file, true);

switch (json_last_error()) {
    case JSON_ERROR_DEPTH:
        echo ' - Достигнута максимальная глубина стека';
    break;
    case JSON_ERROR_STATE_MISMATCH:
        echo ' - Некорректные разряды или несоответствие режимов';
    break;
    case JSON_ERROR_CTRL_CHAR:
        echo ' - Некорректный управляющий символ';
    break;
    case JSON_ERROR_SYNTAX:
        echo ' - Синтаксическая ошибка, некорректный JSON';
    break;
    case JSON_ERROR_UTF8:
        echo ' - Некорректные символы UTF-8, возможно неверно закодирован';
    break;
    default:
        echo ' - Неизвестная ошибка';
    break;
    case JSON_ERROR_NONE:

        $csv = array();
        for ($i = 0; $i < count($json['items']); $i++) {

            $csv[$i] = [
                "id" => $json['items'][$i]['id'],
                "title" => $json['items'][$i]['volumeInfo']['title']
            ];

            if ($json['items'][$i]['volumeInfo']['authors'] != false) {

                foreach ($json['items'][$i]['volumeInfo']['authors'] as $value) {
                  $csv[$i]['authors'] = $value;
                }
            } else {
                $csv[$i]['authors'] = "Нет данных об авторе";
            }

        }

        $fp = fopen('books.csv', 'w') or die ("Ошибка!");

            foreach ($csv as $key => $books) {
                fputcsv($fp, $books);
            }

        fclose($fp);

        $row = 1;
        if (($handle = fopen("books.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
           
              echo $row . " - id: " . $data['0'] . "; Title: '" . $data['1'] . "'; authors: " . $data['2'] . "\n";
               $row++;
            
               echo PHP_EOL;
            }
            fclose($handle);
        }
    break;
    
}


?>