<?php

class CsvFileReader extends AbstractCsvReader
{

    public function __construct($file_name, $csv_delimeter, $csv_enclosure)
    {
        $this->csv_delimeter = $csv_delimeter;
        $this->csv_enclosure = $csv_enclosure;
//        $cmd = 'iconv -f Windows-1251 -t UTF-8 '.$file_name;
        //      $cmd = 'iconv -f UTF-8 -t UTF-8 '.$file_name;
        //      $this->proc = proc_open($cmd, array(1 => array("pipe", "w"), 2 => array("pipe", "w")), $pipes);
        $this->file = fopen($file_name, 'r');//$pipes[1];
        //var_dump($this->file); exit;
    }
}

