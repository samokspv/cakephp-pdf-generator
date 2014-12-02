<?php
/**
 * Author: samokspv <samokspv@yandex.ru>
 * Date: 01.03.2014
 * Time: 15:00:00
 * Format: http://book.cakephp.org/2.0/en/development/routing.html
 */

Router::connect('/PdfGenerator/:action/*', array('plugin' => 'PdfGenerator', 'controller' => 'PdfGenerator'));