<?php
/**
 * Created by PhpStorm.
 * User: ydomenjoud
 * Date: 12/10/16
 * Time: 15:41
 */

namespace MiniBlog\Content;


class Parser
{


    /**
     * Parser constructor.
     */
    public function __construct(){
        $this -> parser = new \Mni\FrontYAML\Parser();
    }

    /**
     * parse frontyaml and markdown from a file
     * @param $file
     * @return array
     */
    public function parse( $uri ){
        $file = CONTENT_DIR . DIRECTORY_SEPARATOR . $uri . CONTENT_EXTENSION;
        $parsed_informations = $this -> parser -> parse( file_get_contents($file) );

        $informations = [
            'content' => $parsed_informations -> getContent(),
            'parameters' => $this -> cleanParameters( $parsed_informations -> getYAML() )
        ];

        return $informations;
    }

    /**
     * clean parameters before use
     * @param $parameters
     * @return mixed
     */
    private function cleanParameters($parameters){
        // clean date
//        $parameters = array_map(function(&$item,$key){
//            echo "$item => $key";
//        }, $parameters);

        return $parameters;
    }
}