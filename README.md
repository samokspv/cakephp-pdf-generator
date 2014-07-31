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
		http://localhost/documents/view/14210
	
		http://localhost/documents/view/14210.json - must return array data in json:
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

	In any place of your code where you need button:
	echo $this->element('PdfGenerator.pdf/generate-link');