<?php
$pluginPath = App::pluginPath('PdfGenerator');
$defaultConfig = array(
	'pdf' => array(
		'ext' => '.pdf',
		'theme' => '',
		'cacheDir' => WWW_ROOT . 'cache/pdf/'/*$pluginPath . 'webroot/cache/pdf'*/,
		'tmpDir' => TMP/*$pluginPath . 'webroot/tmp'*/,
		'css' => $pluginPath . 'webroot/css/pdf/pages.css',
		'template' => 'PdfGenerator.Pdf',
		'log' => LOGS . 'error.log',
		'pages' => array(
			array(
				'element' => 'PdfGenerator.pdf/pages/cover'
			),
			array(
				'element' => 'PdfGenerator.pdf/pages/documents'
			)
		)
	)
);