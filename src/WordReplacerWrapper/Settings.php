<?php


namespace Jsvptf\WordReplacerWrapper;


use Exception;

class Settings
{
    /**
     * store the temporal directory
     * @var string $workspace
     */
    protected static string $workspace;

    /**
     * @return string
     */
    public static function getWorkspace(): string
    {
        return self::$workspace;
    }

    /**
     * @param string $workspace
     * @throws Exception
     */
    public static function setWorkspace(string $workspace = null): void
    {
        if (!$workspace) {
            $workspace = sys_get_temp_dir();
        }

        if (!RouteVerifier::checkDirectory($workspace)) {
            throw new Exception("Invalid workspace ${workspace}");
        }

        self::$workspace = $workspace;
    }
}