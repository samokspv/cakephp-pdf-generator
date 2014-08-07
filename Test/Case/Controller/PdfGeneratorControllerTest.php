<?php

/**
 * Author: samokspv <samokspv@yandex.ru>
 * Date: 04.08.2014
 * Time: 12:00:00 AM
 * Format: http://book.cakephp.org/2.0/en/development/testing.html
 */

App::uses('AppControllerTestCase', 'TestSuite');
App::uses('ActiveRecordManager', 'ActiveRecord.Lib/ActiveRecord');

class PdfGeneratorControllerTest extends AppControllerTestCase {

	/**
	 * {@inheritdoc}
	 *
	 * @var array
	 */
	public $fixtures = array(
		'Session'
	);

	/**
	 * {@inheritdoc}
	 */
	public function setUp() {
		parent::setUp();
		
		$this->pluginPath = App::pluginPath('PdfGenerator');
		$this->setDefaultConfig();
	}

	/**
	 * Set default config
	 */
	public function setDefaultConfig() {
		include $this->pluginPath . 'Config/defaultConfig.php';
		Configure::write('PdfGenerator', $defaultConfig);
		$this->config = $defaultConfig['pdf'];
		return true;
	}

	/**
	 * Test generate task
	 *
	 * @param array $data
	 *
	 * @dataProvider generateProvider
	 */
	public function testGenerate($data) {
		$fileName = md5($data . time());
		$data = file_get_contents($this->pluginPath . 'Test' . DS . 'Data' . DS . $data);
		$data = json_decode($data, true);
		
		$PdfGenerator = $this->generate('PdfGenerator.PdfGenerator', array(
			'models' => array(
				'PdfGenerator.PdfGenerator' => array('getDataDocumentsByUrl')
			)
		));
		$PdfGenerator->PdfGenerator
			->expects($this->any())
			->method('getDataDocumentsByUrl')
			->will($this->returnValue($data));
		$url = 'PdfGenerator.PdfGenerator/generate?name=' . $fileName . '&curl=/test';
		$generate = $this->testAction($url, array(
			'method' => 'GET'
		));
		$fileName = $this->config['cacheDir'] . $fileName . $this->config['ext'];

		$this->assertTrue($generate);
		$this->assertTrue(file_exists($fileName));
		unlink($fileName);
	}

	/**
	 * data provider for testGenerate
	 *
	 * @return array
	 */
	public static function generateProvider() {
		return array(
			# set0
			array(
				'data_url_0.json'
			),
			# set1
			array(
				'data_url_1.json'
			)
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function tearDown() {
		parent::tearDown();
	}
}