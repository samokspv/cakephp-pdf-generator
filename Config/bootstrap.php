<?php

/**
 * Author: samokspv <samokspv@yandex.ru>
 * Date: 01.03.2014
 * Time: 15:00:00
 * Format: http://book.cakephp.org/2.0/en/views.html
 */

CakePlugin::load('CakePdf', array('bootstrap' => true, 'routes' => true));

include 'defaultConfig.php';
$config = Hash::mergeDiff(
	(array)Configure::read('PdfGenerator'),
	$defaultConfig
);
Configure::write('PdfGenerator', $config);
