<?php
/**
 * Created by PhpStorm.
 * User: ydomenjoud
 * Date: 12/10/16
 * Time: 18:15
 */

namespace MiniBlog\Content;


class File
{

    /**
     * get files recursively by extension in a directory
     * @param $path
     * @param $extension
     * @return array
     */
    public static function browse($path, $extension){

        $files = [];

        $path = realpath($path);
        // browse file
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $filename) {
            $filename = $filename->__toString();
            $basename = basename($filename);
            if (substr($basename, -strlen($extension)) === $extension) {
                $files[] = $filename;
            }
        }

        return $files;
    }
}