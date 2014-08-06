<?php

/**
 * Author: samokspv <samokspv@yandex.ru>
 * Date: 04.08.2014
 * Time: 12:00:00 AM
 * Format: http://book.cakephp.org/2.0/en/development/testing.html
 */

class AllPdfGeneratorTest extends PHPUnit_Framework_TestSuite {

	/**
	 * Suite define the tests for this suite
	 *
	 * @return void
	 */
	public static function suite() {
		$suite = new CakeTestSuite('All PdfGenerator Tests');

		$path = App::pluginPath('PdfGenerator') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);
		return $suite;
	}

}