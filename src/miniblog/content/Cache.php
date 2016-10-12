<?php
/**
 * Created by PhpStorm.
 * User: ydomenjoud
 * Date: 12/10/16
 * Time: 15:39
 */

namespace MiniBlog\Content;


class Cache
{

    private $directory;

    public function __construct(){
        $this -> directory = CACHE_DIR. DIRECTORY_SEPARATOR;
    }

    /**
     * get a file from cache
     * @param $filename
     * @return string
     */
    public function get($filename){
        $file = $this -> directory  . $filename ;
        return $this -> exists($filename) ? file_get_contents($file) : '';
    }

    /**
     * check if a file exists in cache
     * @param $filename
     * @return bool
     */
    public function exists($filename){
        $file = $this -> directory  . $filename ;
        return file_exists($file);
    }

    /**
     * Write file in a cache
     * @param $filename
     * @param $content
     */
    public function write($filename, $content){
        $file_path = $this -> directory . $filename;

        // if directory do not exists, create it
        $dirname = dirname($file_path);
        if( !is_dir($dirname) ){
            mkdir($dirname, 0777, true);
        }

        file_put_contents($file_path, $content);
    }

    /**
     * clean caches files
     * @param array $files
     */
    public function clean($files = []){

        if( count($files) == 0 ){
            $files = File::browse( $this -> directory, CACHE_EXTENSION);
        }

        foreach($files as $file){
            unlink($file);
        }
    }

}