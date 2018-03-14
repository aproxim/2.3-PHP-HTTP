<?php
    // Определяем массив со всеми файлами из папки с тестами
    $allFiles = glob('tests/*.json');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
   
</head>
<body>
    <a href="index.php" class="back"><div>Назад</div></a>
    <h2>Все тесты</h2>
    <hr>

    <!-- Цикл, который выводит список всех загруженных файлов -->
    <?php if (!empty($allFiles)): ?>
        <?php foreach ($allFiles as $file): ?>

            <div class="file-block">
                <h1><?php echo str_replace('tests/', '', $file); ?></h1><br>
                <em>Загружен: <?php echo date("d-m-Y H:i", filemtime($file)) ?></em><br>
                <a href="test.php?number=<?php echo array_search($file, $allFiles); ?>">Перейти на страницу с тестом ></a>
            </div>
            <hr>

        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Если файлов нету, то сообщить об этом -->
    <?php if (empty($allFiles)) echo 'Пока не загружено ни одного теста';?>

</body>
</html>
