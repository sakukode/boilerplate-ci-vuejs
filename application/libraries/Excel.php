<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require_once 'Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

class Excel {

	function __construct() {

	}

	public function export($header, $body, $type = 'XLSX', $open_to, $filename) {		
		if($type == 'XLSX') {
			$writer = WriterFactory::create(Type::XLSX);
		} elseif($type == 'CSV') {
			$writer = WriterFactory::create(Type::CSV);
		} elseif($type == 'ODS') {
			$writer = WriterFactory::create(Type::ODS);
		} else {
			return FALSE;
		}

		if($open_to == 'FILE') {
			$writer->openToFile($filename);
		} else {
			$writer->openToBrowser($filename);			
		}

		$writer->addRow($header); // add a row at a time
		$writer->addRows($body); // add multiple rows at a time

		$writer->close();
	}
}
