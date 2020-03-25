<?php

namespace Jsvptf\WordReplacerWrapper;

use Exception;

class RouteVerifier
{

    /**
     * accepted file extensions
     */
    const ACCEPTED_EXTENSIONS = ['docx'];

    /**
     * template file verifications
     *
     * @param string $fileRoute
     * @param array $acceptedExtensions
     * @return boolean
     * @throws Exception
     * @date 2020
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     */
    public static function checkFile(string $fileRoute)
    {
        if (!is_file($fileRoute)) {
            throw new Exception("The file does not exists", 1);
        }

        $extension = pathinfo($fileRoute, PATHINFO_EXTENSION);

        if (!in_array($extension, self::ACCEPTED_EXTENSIONS)) {
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
     * @param string $directory
     * @return boolean
     * @throws Exception
     * @author jhon sebastian valencia <sebasjsv97@gmail.com>
     * @date 2020
     */
    public static function checkDirectory(string $directory)
    {
        $directory = trim($directory, '/');

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        } else {
            chmod($directory, 0777);
        }

        $file = $directory . "/test.txt";

        if (!is_dir($directory) || file_put_contents($file, '') === false) {
            throw new Exception("Invalid directory {$directory}", 1);
        } else {
            unlink($file);
        }

        return true;
    }
}
