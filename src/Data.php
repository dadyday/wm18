<?php
namespace My;

class Data {

    static function load($data, $format = null, $type = null) {
        if (is_null($type)) $type = static::getDataType($data);

        switch ($type) {
            case 'file':
                return static::loadFile($data, $format);
            case 'url':
                $data = file_get_contents($data);
            case 'string':
                return static::loadString($data, $format);
        }
    }

    static function getDataType($string) {
        if (is_file($string)) return 'file';
        if (filter_var($string, FILTER_VALIDATE_URL)) return 'url';
        return 'string';
    }

    static function loadFile($file, $format= null) {
        if (is_null($format)) $format = static::getFileFormat($file);

        switch ($format) {
            case 'csv':
                return static::loadCsvFile($file);
            case 'php':
                return include($file);
            default:
                throw new \Exception("data file format $format not implemented");
        }
    }

    static function getFileFormat($file) {
        $oInfo = new \SplFileInfo($file);
        return $oInfo->getExtension();
    }

    static function loadCsvFile($file) {
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

    static function loadString($data, $format= null) {
        if (is_null($format)) $format = static::getStringFormat($data);

        switch ($format) {
            default:
                throw new \Exception("data string format $format not implemented");
        }
    }

    static function getStringFormat($data) {
        if (preg_match('~^<\?php.*~', $data)) return 'php';
        throw new \Exception('data string format not found');
    }

}
