<?php
/**
 * Author: samokspv <samokspv@yandex.ru>
 * Date: 01.03.2014
 * Time: 15:00:00
 * Format: http://book.cakephp.org/2.0/en/controllers.html
 */

/**
 *  Dependencies:
 *  	Libs:
 *			- imagemagick (http://www.imagemagick.org);  
 * 		Bin-files:
 * 			- wkhtmltopdf, wkhtmltoimage (http://wkhtmltopdf.org)
 * 		Plugins:
 * 			- CakePdf (http://github.com/ceeram/CakePdf)
 * 			- Cakephp Task Plugin (http://github.com/imsamurai/cakephp-task-plugin)
 * 			- Cakephp DB Configure (https://github.com/samokspv/CakePHP-DBConfigure)
 */

App::uses('PdfGeneratorAppController', 'PdfGenerator.Controller');

/**
 * PdfGeneratorController
 * 
 * @property PdfGenerator $PdfGenerator PdfGenerator model
 * 
 * @package PdfGenerator.Controller
 */
class PdfGeneratorController extends PdfGeneratorAppController {
	
	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	public $uses = array('PdfGenerator.PdfGenerator');

	/**
	 * Generate pdf file
	 * @return boolean
	 */
	public function generate() {
		$params['name'] = $this->request->query('name');
		$params['curl'] = rawurldecode($this->request->query('curl'));
		$this->PdfGenerator->init($params);
		try {
			$fileName = $params['name'] . Configure::read('PdfGenerator.pdf.ext');
			$filePath = TMP . $fileName;
			$this->PdfGenerator->CakePdf->write($filePath);
			if ($this->PdfGenerator->moveFileToCacheDir($fileName)) {
				return true;
			}
		} catch (Exception $e) {
			error_log($e . "\n", 3, Configure::read('PdfGenerator.pdf.log'));
		}
		return false;
	}
	
	/**
	 * Add generate task
	 * @return string
	 */
	public function generateTask() {
		$this->autoRender = false;
		$curl = $this->request->query('curl');
		$task = $this->PdfGenerator->generateTask($curl);
		return json_encode($task);
	}

	/**
	 * Return generate task status by task id
	 * @return string
	 */
	public function getGenerateStatus() {
		$this->autoRender = false;
		$taskId = $this->request->query('taskId');
		$task = $this->PdfGenerator->getGenerateStatus($taskId);
		return json_encode($task);
	}

	/**
	 * Return page number of pdf file
	 * @return integer
	 */
	public function getPageNumber() {
		$this->autoRender = false;
		return $this->PdfGenerator->getPageNumber();
	}
}
