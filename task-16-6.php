<?php

$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];


// Разбиение и объединение ФИО

function getFullnameFromParts($surname, $name, $patronomyc) // принимает как аргумент три строки — фамилию, имя и отчество
{
    $full_name = $surname . ' ' . $name . ' ' . $patronomyc; // возвращает как результат их же, но склеенные через пробел
    return $full_name;
}


function getPartsFromFullname($full_name) // принимает как аргумент одну строку — склеенное ФИО
{
    $keys = [
        'surname',
        'name',
        'patronomyc',
    ]; // возвращает как результат массив из трёх элементов с ключами 'name', 'surname' и 'patronomyc'
    $values = explode(' ', $full_name);
    return array_combine($keys, $values);
}


// Сокращение ФИО

function getShortName($full_name) // принимает как аргумент строку, содержащую ФИО
{
    $parts = getPartsFromFullname($full_name); // для разбиения строки на составляющие использую функцию getParstFromFullname
    $shortSurname = mb_substr($parts['surname'], 0, 1); // сокращается фамилия
    $shortFullname = $parts['name'] . ' ' . $shortSurname . '.'; // отбрасывается отчество
    return $shortFullname;
}


// Функция определения пола по ФИО

function getGenderFromName($full_name) // принимает как аргумент строку, содержащую ФИО
{
    $partsFromFullname = getPartsFromFullname($full_name); // внутри функции делю ФИО на составляющие с помощью функции getPartsFromFullname
    $gender = 0; // изначально «суммарный признак пола» считаем равным 0
    $patronomycEnding = mb_substr($partsFromFullname['patronomyc'], -3, 3);
    $nameEnding = mb_substr($partsFromFullname['name'], -1, 1);
    $surnameEnding = mb_substr($partsFromFullname['surname'], -2, 2);

    if ($patronomycEnding == 'вна') { // признаки женского пола: отчество заканчивается на "вна"
        $gender--; // после проверок всех признаков, если "суммарный признак пола" меньше нуля — возвращаем -1 (женский пол);
    } elseif (mb_substr($patronomycEnding, -2, 2) == 'ич') { // признаки мужского пола: отчество заканчивается на "ич"
        $gender++; // после проверок всех признаков, если "суммарный признак пола" больше нуля — возвращаем 1 (мужской пол);
    }

    if ($nameEnding == 'а') { // признаки женского пола: имя заканчивается на "а"
        $gender--; // после проверок всех признаков, если "суммарный признак пола" меньше нуля — возвращаем -1 (женский пол);
    } elseif ($nameEnding == 'й' || $nameEnding == 'н') { // признаки мужского пола: имя заканчивается на "й" или "н"
        $gender++; // после проверок всех признаков, если "суммарный признак пола" больше нуля — возвращаем 1 (мужской пол);
    }

    if ($surnameEnding == 'ва') { // признаки женского пола: фамилия заканчивается на "ва"
        $gender--; // после проверок всех признаков, если "суммарный признак пола" меньше нуля — возвращаем -1 (женский пол);
    } elseif (mb_substr($surnameEnding, -1, 1) == 'в') { // признаки мужского пола: фамилия заканчивается на "в"
        $gender++; // после проверок всех признаков, если "суммарный признак пола" больше нуля — возвращаем 1 (мужской пол);
    }

    return $gender <=> 0; // после проверок всех признаков, если "суммарный признак пола" равен 0 — возвращаем 0 (неопределенный пол)
}


// Определение возрастно-полового состава

function getGenderDescription($example_persons_array) // как аргумент в функцию передаётся массив
{
    // фильтрация элементов массива
    $genderMale = array_filter($example_persons_array, function ($example_persons_array) {
        $fullname = $example_persons_array['fullname'];
        $sexMale = getGenderFromName($fullname);
        if ($sexMale > 0) return $sexMale;
    });

    $genderFemale = array_filter($example_persons_array, function ($example_persons_array) {
        $fullname = $example_persons_array['fullname'];
        $sexFemale = getGenderFromName($fullname);
        if ($sexFemale < 0) return $sexFemale;
    });

    $genderIndefined = array_filter($example_persons_array, function ($example_persons_array) {
        $fullname = $example_persons_array['fullname'];
        $sexUknown = getGenderFromName($fullname);
        if ($sexUknown == 0) return $sexUknown + 1;
    });

    // подсчёт элементов массива
    $totalArr = count($example_persons_array);
    $menArr = count($genderMale);
    $womenArr = count($genderFemale);
    $indefinedArr = count($genderIndefined);

    // округление
    $menCount = round((($menArr / $totalArr) * 100), 1);
    $womenCount = round((($womenArr / $totalArr) * 100), 1);
    $indefinedCount = round((($indefinedArr / $totalArr) * 100), 1);

    // вывод информации
    $structure = <<<MYHEREDOCTEXT
    Гендерный состав аудитории:<br>
    ---------------------------<br>
    Мужчины - $menCount %<br>
    Женщины - $womenCount %<br>
    Не удалось определить - $indefinedCount %
    MYHEREDOCTEXT;

    echo $structure;
}


// Идеальный подбор пары

function getPerfectPartner($surname, $name, $patronomyc, $example_persons_array) // первые три аргумента в функцию передаются строки с фамилией, именем и отчеством (именно в этом порядке), четвёртым аргументом в функцию передается массив
{
    $surname = mb_convert_case($surname, MB_CASE_TITLE_SIMPLE); // привожу фамилию к привычному регистру
    $name = mb_convert_case($name, MB_CASE_TITLE_SIMPLE); // привожу имя к привычному регистру
    $patronomyc = mb_convert_case($patronomyc, MB_CASE_TITLE_SIMPLE); // привожу отчество к привычному регистру

    $full_name = getFullnameFromParts($surname, $name, $patronomyc); // склеиваю ФИО, используя функцию getFullnameFromParts

    $gender = getGenderFromName($full_name); // определяю пол для ФИО с помощью функции getGenderFromName

    $totalArr = count($example_persons_array);

    do {
        $randomPartner = rand(0, $totalArr - 1); // случайным образом выбираю любого человека в массиве
        $randomPartnerFullname = $example_persons_array[$randomPartner]['fullname'];
        $genderRandomPartner = getGenderFromName($randomPartnerFullname);
    } while (($genderRandomPartner == $gender) || ($genderRandomPartner == 0)); //проверяю с помощью getGenderFromName, что выбранное из массива ФИО - противоположного пола

    $shortFullname = getShortName($full_name);
    $shortFullnameRandomPartner = getShortName($randomPartnerFullname);
    $comp = rand(5000, 10000) / 100; // процент совместимости — случайное число от 50% до 100% с точностью два знака после запятой

    $result = <<<HEREDOCTEXT
    $shortFullname + $shortFullnameRandomPartner =<br>
    ♡ Идеально на $comp% ♡
    HEREDOCTEXT;

    echo $result;    
}


getGenderDescription($example_persons_array);
echo "<br>";
getPerfectPartner('ИваНов', 'ИВАН', 'иванович', $example_persons_array)

?>