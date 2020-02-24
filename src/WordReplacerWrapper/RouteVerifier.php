<?php

namespace Jsvptf\WordReplacerWrapper;

use Exception;
use Gaufrette\Adapter\Local;
use Gaufrette\Filesystem;

class RouteVerifier
{

    /**
     * template file verifications
     *
     * @param string $fileRoute
     * @return boolean
     * @author jhon sebastian valencia <jhon.valencia@cerok.com>
     * @throws WordReplacerWrapperException
     * @date 2020
     */
    public static function checkFile(string $fileRoute, array $aceptedExtensions = [])
    {
        if (!is_file($fileRoute)) {
            throw new Exception("The file does not exists", 1);
        }

        $extension = pathinfo($fileRoute, PATHINFO_EXTENSION);

        if (!in_array($extension, $aceptedExtensions)) {
            throw new Exception("Invalid file extension", 1);
        }

        if (false === file_get_contents($fileRoute)) {
            throw new Exception("Empty file", 1);
        }

        return true;
    }


    /**
     * template file verifications
     *
     * @param string $fileRoute
     * @return boolean
     * @author jhon sebastian valencia <jhon.valencia@cerok.com>
     * @throws WordReplacerWrapperException
     * @date 2020
     */
    public static function checkDirectory(string $directory)
    {
        //open or create folder
        $adapter = new Local($directory, true);
        $Filesystem = new Filesystem($adapter);

        //check permissions
        $Filesystem->write('test.txt', '', true);
        $Filesystem->delete('test.txt');

        return true;
    }
}
