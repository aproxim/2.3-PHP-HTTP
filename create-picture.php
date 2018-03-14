<?php
header('Content-type: image/png');
$testname = $_POST['testname'];
$username = $_POST['username'];
$date = $_POST['date'];
$correctAnswers = $_POST['correctAnswers'];
$totalAnswers = $_POST['totalAnswers'];
$correct = $correctAnswers . ' из ' . $totalAnswers;
// Создаем картинку
$img = imagecreatefromjpeg('img/picture.jpg');

// Распологаем все данные на картинке
imagettftext($img, 300, 0, $x, 600, $white, 'fonts/OpenSans.ttf', $username . ',');
imagettftext($img, 200, 0, 1600, 1625, $white, 'fonts/OpenSans.ttf', $testname);
imagettftext($img, 180, 0, 1600, 1976   , $white, 'fonts/OpenSans.ttf', $correct);
imagettftext($img, 180, 0, 1600, 2325, $white, 'fonts/OpenSans.ttf', $date);
imagepng($img);
imagedestroy($img);