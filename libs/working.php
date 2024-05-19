<?php
// получаем данные результатов каждой попытки заездов
$data_attempts = json_decode(file_get_contents('data/data_attempts.json') , true);

// получаем данные участников
$data_cars = json_decode(file_get_contents('data/data_cars.json') , true);

// Создание массива для хранения итоговых результатов
$final_results = [];
$attempts_results = [];

// Группировка результатов по участникам
foreach ($data_attempts as $attempt)
{
    // Берем Id с данных попыток заездов
    $id = $attempt['id'];
    // Берем результат с попыток заездов
    $result = $attempt['result'];
    // Проверяем, есть ли в массиве элемент с ID
    if (!isset($final_results[$id]))
    {
        $final_results[$id] = 0;
    }
    // Добавляем результат к общему счёту
    $final_results[$id] += $result;
    $attempts_results[$id][] = $result;
}

// Функция для получения данных участника по его ID
function getCarsData($id, $data_cars)
{
    foreach ($data_cars as $cars)
    {
        // Проверяем ID к тому, что мы ищем
        if ($cars['id'] == $id)
        {
            // Если найден, возвращаем
            return $cars;
        }
    }
    return null;
}

// Первоначальная сортировка по общей сумме очков
arsort($final_results);

?>