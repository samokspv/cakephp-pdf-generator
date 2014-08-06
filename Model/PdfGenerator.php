<?php
/**
 * Author: samokspv <samokspv@yandex.ru>
 * Date: 01.03.2014
 * Time: 15:00:00
 * Format: http://book.cakephp.org/2.0/en/models.html
 */

App::uses('PdfGeneratorAppModel', 'PdfGenerator.Model');

/**
 * @package PdfGenerator.Model
 */
class PdfGenerator extends PdfGeneratorAppModel {
	
	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	public $name = 'PdfGenerator';
	
	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	public $useTable = false;

	/**
	 * CakePdf object
	 * @var object
	 */
	public $CakePdf = null;
	
	/**
	 * Page number of pdf file
	 * @var integer
	 */
	public static $pageNumber = 0;

	/**
	 * Config data
	 * @var array
	 */
	public $config = array();

	/**
	 * Generate params
	 * @var array
	 */
	public $generateParams = array();

	/**
	 * Add generate task
	 * @param  string $curl
	 * @return array
	 */
	public function generateTask($curl) {
		$name = $this->generateName($curl);
		$task = $this->Task->add('Console/cake pdf generate', APP, array(
			'--name' => $name,
			'--curl' => $curl
		));
		DBConfigure::write('PdfGenerator.Task.' . $task['id'] . '.code', -1);
		DBConfigure::write('PdfGenerator.Task.' . $task['id'] . '.status', TaskType::UNSTARTED);
		return array(
			'taskId' => $task['id'], 
			'name' => $name, 
			'status' => TaskType::UNSTARTED,
			'code' => -1
		);
	}

	/**
	 * Returns generate task status by task id
	 * @param  integer $taskId
	 * @return integer
	 */
	public function getGenerateStatus($taskId) {
		$task = array();
		if (!empty($taskId)) {
			$task = DBConfigure::read('PdfGenerator.Task.' . $taskId);
		}
		$task += array('status' => TaskType::UNSTARTED, 'code' => -1);
		
		/*if ($task['status'] == TaskType::FINISHED && $task['code'] == 0) {
			$pdf = DBConfigure::read('PdfGenerator.pdf');
			$pdfPath = $pdf['cacheDir'] . DS . $task['arguments']['--name'] . $pdf['ext'];
			if (!file_exists($pdfPath)) {
				$task['status'] = -2;
			}
		}*/
		
		return array('status' => $task['status'], 'code' => $task['code']);
	}

	/**
	 * {@inheritdoc}
	 * 
	 * @return array
	 */
	public function implementedEvents() {
		return array(
			'Task.taskStarted' => array(
				'callable' => 'setTaskResult', 
				'passParams' => true
			),
			'Task.taskUpdated' => array(
				'callable' => 'setTaskResult', 
				'passParams' => true
			),
			'Task.taskStopped' => array(
				'callable' => 'setTaskResult', 
				'passParams' => true
			)
		) + parent::implementedEvents();
	}

	/**
	 * Set task result
	 * @param  array $task
	 * @return boolean
	 */
	public function setTaskResult($task) {
		if (empty($task)) {
			return false;
		}
		DBConfigure::write('PdfGenerator.Task.' . $task['id'], array(
			'status' => $task['status'],
			'code' => $task['code'],
			'started' => $task['started'],
			'stopped' => $task['stopped'],
			'arguments' => $task['arguments']
		));
		return true;
	}

	/**
	 * Initialize CakePdf object
	 * @param  array $params
	 * @return boolean
	 */
	public function init($params) {
		if (empty($params)) {
			return false;
		}
		$this->config = Configure::read('PdfGenerator.pdf');
		$this->generateParams = $params;
		self::$pageNumber = 0;

		require_once App::pluginPath('CakePdf') . 'Pdf/CakePdf.php';
		$this->CakePdf = new CakePdf();
		//$this->CakePdf->theme($pdf['theme']);
		$this->CakePdf->css($this->config['css']);
		$this->CakePdf->template($this->config['template'], false);
		$this->CakePdf->margin(Configure::read('CakePdf.margin'));
		
		foreach ($this->config['pages'] as &$page) {
			$el = explode(DS, $page['element']);
			if (end($el) == 'documents') {
				$page['settings']['documents'] = $this->getDataDocumentsByUrl($params['curl']);
				break;
			}
		}
		$viewVars = array(
			'pdf' => array(
				'pages' => $this->config['pages'],
				'date' => $this->getDate(),
				'curl' => $this->getDocumentsUrl($params['curl'])
			)
		);
		$this->CakePdf->viewVars($viewVars);
		return true;
	}

	/**
	 * Generate pdf file
	 * @return boolean
	 */
	public function generate() {
		$fileName = $this->generateParams['name'] . $this->config['ext'];
		if (!is_dir($this->config['tmpDir'])) {
			mkdir($this->config['tmpDir'], 0777, true);
		}
		$this->CakePdf->write($this->config['tmpDir'] . $fileName);
		if ($this->moveFileToCacheDir($fileName)) {
			return true;
		}
		return false;
	}

	/**
	 * Generate name of Pdf file
	 * @param  string $curl
	 * @return string
	 */
	public function generateName($curl) {
		return md5($curl . time());
	}

	/**
	 * Returns page number of pdf file
	 * @return integer
	 */
	public function getPageNumber() {
		return (int)++self::$pageNumber;
	}

	/**
	 * Returns current date
	 * @param  string $format
	 * @return string
	 */
	public function getDate($format = 'Y.m.d') {
		return date($format);
	}

	/**
	 * Move pdf file to cache dir
	 * @param  string  $fileName
	 * @return boolean
	 */
	public function moveFileToCacheDir($fileName) {
		$currFilePath = $this->config['tmpDir'] . $fileName;
		if (file_exists($currFilePath)) {
			if (!is_dir($this->config['cacheDir'])) {
				mkdir($this->config['cacheDir'], 0777, true);
			}
			rename($currFilePath, $this->config['cacheDir'] . DS . $fileName);
			return true;
		}
		return false;
	}
	
	/**
	 * Returns data documents by url
	 * @param  string $curl
	 * @return array
	 */
	public function getDataDocumentsByUrl($curl) {
		$curl = $this->fixDocumentsUrl($curl);
		$data = file_get_contents($curl);
		$data = json_decode($data, true);
		return $data;
	}

	/**
	 * Returns fix documents url
	 * @param  string $curl
	 * @param  string $split
	 * @return string
	 */
	public function fixDocumentsUrl($curl, $split = '?') {
		$ext = '.json';
		if (stripos($curl, $split) !== false) {
			$curl = explode($split, $curl);
			$curl = $curl[0] . $ext . $split . $curl[1];
		} else {
			$curl .= $ext;
		}
		return $this->getDocumentsUrl($curl);
	}

	/**
	 * Returns documents url
	 * @param  string $curl
	 * @return string
	 */
	public function getDocumentsUrl($curl) {
		return Router::fullBaseUrl() . $curl;
	}
}