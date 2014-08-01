cakephp-pdf-generator
=====================

Use it if you want add button pdf generation to the page for task pdf generation (get data from current url, format json)

## Installation

	cd my_cake_app/app
	git clone git://github.com/samokspv/cakephp-pdf-generator.git Plugin/PdfGenerator

or if you use git add as submodule:

	cd my_cake_app
	git submodule add "git://github.com/samokspv/cakephp-pdf-generator.git" "app/Plugin/PdfGenerator"

then add plugin loading in Config/bootstrap.php

	CakePlugin::load('PdfGenerator', array('bootstrap' => true, 'routes' => true));

## Usage

	For example:

	You url:
		http://your_domain/documents/view/14210
	
		http://your_domain/documents/view/14210.json - must return array data in json:
		[
			{
				"field1": "text1",
				"field2": 'text2',
				...
				"fieldN": [
					"text1",
					"text2",
					...
					"textN"
				]
			},
			...
		]

	In any place of your view where you need button:
	echo $this->element('PdfGenerator.pdf/generate-link');

	app/Config/core.php:
	Configure::write('PdfGenerator', array(
		'pdf' => array(
			'cacheDir' => WWW_ROOT . 'cache/pdf', // link to pdf file
			'css' => WWW_ROOT . 'css/pdf/pdf.css' // link to css for pdf file
			'log' => LOGS . 'error.log', // link to log file
			'pages' => array(
				array(
					'element' => 'pdf/cover' // first page
				),
				array(
					'element' => 'pdf/documents' // data from http://your_domain/documents/view/14210.json
				)
			) // elements will be included in the pdf file
		)
	));
	Configure::write('Task', array(
		'timeout' => 60 * 60 * 24 * 5,
		'dateDiffFormat' => "%h hours, %i min, %s sec",
		'processEvents' => array(
			array(
				'model' => 'PdfGenerator.PdfGenerator',
				'key' => 'Task.taskStarted',
				'options' => array()
			),
			array(
				'model' => 'PdfGenerator.PdfGenerator',
				'key' => 'Task.taskUpdated',
				'options' => array()
			),
			array(
				'model' => 'PdfGenerator.PdfGenerator',
				'key' => 'Task.taskStopped',
				'options' => array()
			)
		)
	));
	Configure::write('App.fullBaseUrl', 'http://your_domain');