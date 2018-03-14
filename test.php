<?php
// Если не был передан номер теста - возвращать на страницу со списком тестов
if (!isset($_GET['number'])) {
    header('Location: list.php');
    exit;
}
// Если не существует теста с номером из get-запроса, то выдавать 404 ошибку
if (!isset(glob('tests/*.json')[$_GET['number']])) {
    header('HTTP/1.0 404 Not Found');
    exit;
}
// Получаем файл с номером из GET-запроса
$allTests = glob('tests/*.json');
$number = $_GET['number'];
$test = file_get_contents($allTests[$number]);
$test = json_decode($test, true);
// Если была нажата кнопка проверки теста, то проверить и вывести результат
function checkTest($testFile)
{
    // Проверяем, решены ли все задания
    foreach ($testFile as $key => $item) {
        if (!isset($_POST['answer' . $key])) {
            echo 'Должны быть решены все задания!';
            exit;
        }
    }
    // Проверяем тест
    foreach ($testFile as $key => $item) {
        // Здесь идет определение названия класса для блока с вопросом и ответом
        // А также прибавляется 1 к переменной $i, если ответ правильный
        if ($item['correct_answer'] === $_POST['answer' . $key]) {
            $infoStyle = 'correct';
        } else {
            $infoStyle = 'incorrect';
        }
        // Вывод блока с вопросом и ответом
        echo '<div class=' . $infoStyle . '>' .
            'Вопрос: ' . $item['question'] . '<br>' .
            'Ваш ответ: ' . $item['answers'][$_POST['answer' . $key]] . '<br>' .
            'Правильный ответ: ' . $item['answers'][$item['correct_answer']] . '<br>' .
            '</div>' .
            '<hr>';
    }
}
// Функция, считающяя правильные ответы (отдельно от checkTest(), т.к надо вернуть результат и потом нанести на картинку)
function answersCounter($testFile)
{
    $i = 0;
    $questions = 0;
    foreach ($testFile as $key => $item) {
        $questions++;
        if ($item['correct_answer'] === $_POST['answer' . $key]) {
            $i++;
        }
    }
    return ['correct' => $i, 'total' => $questions];
}
if (isset($_POST['check-test'])) {
    $testname = basename($allTests[$number]);
    $username = str_replace(' ', '', $_POST['username']);
    $date = date("d-m-Y H:i");
    $correctAnswers = answersCounter($test)['correct'];
    $totalAnswers = answersCounter($test)['total'];
    $variables = [
        'testname' => $testname,
        'username' => $username,
        'date' => $date,
        'correctAnswers' => $correctAnswers,
        'totalAnswers' => $totalAnswers
    ];
}
if (isset($_POST['generate-picture'])) {
    include_once 'create-picture.php';
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
      <title>Document</title>
   </head>
<body>

<!-- Если пользователь находиться на тесте, ссылка введет на страницу с тестами, если пользователь отправил тест на проверку и видит результаты, то возвращает на страницу с тестом -->

<a href="<?php echo isset($_POST['check-test']) ? $_SERVER['HTTP_REFERER'] : 'list.php' ?>"><div>Назад</div></a><br>

<!-- Если передан номер теста в GET-запросе и пользователь еще не нажал на кнопку проверки теста, то выводить тест -->

<?php if (!isset($_POST['check-test'])): ?>
    <form method="POST">
        <h1><?php echo basename($allTests[$number]); ?></h1>
        <label>Введите ваше имя: <input type="text" name="username" required></label>
        <?php foreach ($test as $key => $item): ?>
            <fieldset>

                <div class="on-hidden-radio"></div>
                <input type="radio" name="answer<?php echo $key ?>" id="hidden-radio" required>

                <legend><?php echo $item['question'] ?></legend>
                <label><input type="radio" name="answer<?php echo $key ?>" value="0"><?php echo $item['answers'][0] ?>
                </label><br>
                <label><input type="radio" name="answer<?php echo $key ?>" value="1"><?php echo $item['answers'][1] ?>
                </label><br>
                <label><input type="radio" name="answer<?php echo $key ?>" value="2"><?php echo $item['answers'][2] ?>
                </label><br>
                <label><input type="radio" name="answer<?php echo $key ?>" value="3"><?php echo $item['answers'][3] ?>
                </label>
            </fieldset>
        <?php endforeach; ?>
        <input type="submit" name="check-test" value="Проверить">
    </form>
<?php endif; ?>

<!-- Если пользователь нажал на кнопку проверки теста, то выводить ему результаты -->
<?php if (isset($_POST['check-test'])): ?>
    <div class="check-test">
        <?php checkTest($test) ?>
        <p style="font-weight: bold;">Итого правильных ответов: <?php echo "$correctAnswers из $totalAnswers" ?></p>
        <h2> <?php echo $username ?>, Вы можете сгенерировать сертификат</h2>
        <form method="POST">
            <input type="submit" name="generate-picture" value="Сгенерировать">
            <?php foreach ($variables as $key => $variable): ?>
                <input type="hidden" value="<?php echo $variable ?>" name="<?php echo $key ?>">
            <?php endforeach; ?>
        </form>
    </div>
<?php endif; ?>


</body>
</html>