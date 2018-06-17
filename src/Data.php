<?php
namespace My;

class Data {

    function __construct() {
    }

    function load($data, $format = null, $type = null) {
        if (is_null($type)) $type = $this->getDataType($data);

        switch ($type) {
            case 'file':
                return $this->loadFile($data, $format);
            case 'url':
                $data = file_get_contents($data);
            case 'string':
                return $this->loadString($data, $format);
        }
    }

    function getDataType($string) {
        if (is_file($string)) return 'file';
        if (filter_var($string, FILTER_VALIDATE_URL)) return 'url';
        return 'string';
    }

    function loadFile($file, $format= null) {
        if (is_null($format)) $format = $this->getFileFormat($file);

        switch ($format) {
            case 'csv':
                return $this->loadCsvFile($file);
            case 'php':
                return include($file);
            default:
                throw new \Exception("data file format $format not implemented");
        }
    }

    function getFileFormat($file) {
        $oInfo = new \SplFileInfo($file);
        return $oInfo->getExtension();
    }

    function loadCsvFile($file) {
        $handle = fopen($file, "r");
        if (!$handle) throw new Exception('open csv file failed');

        $aResult = [];
        $header = $data = fgetcsv($handle, 1000, ";");
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            if ($data) $aResult[] = array_combine($header, $data);
        }
        fclose($handle);
        #bdump($aResult);

        return $aResult;
    }

    function loadString($data, $format= null) {
        if (is_null($format)) $format = $this->getStringFormat($data);

        switch ($format) {
            default:
                throw new \Exception("data string format $format not implemented");
        }
    }

    function getStringFormat($data) {
        if (preg_match('~^<\?php.*~', $data)) return 'php';
        throw new \Exception('data string format not found');
    }

}
