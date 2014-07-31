<?php

/**
 * Author: samokspv <samokspv@yandex.ru>
 * Date: 01.03.2014
 * Time: 15:00:00
 * Format: http://book.cakephp.org/2.0/en/views.html
 */

CakePlugin::load('CakePdf', array('bootstrap' => true, 'routes' => true));
$config = Hash::mergeDiff(
	(array)Configure::read('PdfGenerator'),
	array(
		'pdf' => array(
			'ext' => '.pdf',
			'theme' => '',
			'cacheDir' => WWW_ROOT . 'cache/pdf',
			'css' => ROOT . DS . 'Plugin/PdfGenerator/webroot/css/pdf/pdf.css',
			'template' => 'PdfGenerator.Pdf',
			'log' => LOGS . 'error.log',
			'pages' => array(
				array(
					'element' => 'pdf/cover'
				),
				array(
					'element' => 'pdf/documents'
				)
			)
		)
	)
);
Configure::write('PdfGenerator', $config);
