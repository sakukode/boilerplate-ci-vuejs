<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require_once 'Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Excel {

	function __construct() {

	}

	public function write($header, $body, $type = 'XLSX', $open_to, $filename) {		
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

	public function read($filePath, $type='XLSX') {
		if($type == 'XLSX') {
			$reader = ReaderFactory::create(Type::XLSX);
		} elseif($type == 'CSV') {
			$reader = ReaderFactory::create(Type::CSV);
		} elseif($type == 'ODS') {
			$reader = ReaderFactory::create(Type::ODS);
		} else {
			return FALSE;
		}

		$reader->open($filePath);

		return $reader;

		$reader->close();
	}
}
