<?php
//echo $this->Html->css(APP . 'webroot/css/pdf/pdf');

foreach ($pdf['pages'] as $page) {
	$settings = (!empty($page['settings']) ? $page['settings'] : array());
	echo $this->element($page['element'], $settings);
}
?>