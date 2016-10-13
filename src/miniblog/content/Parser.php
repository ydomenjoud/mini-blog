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

        foreach($parameters AS $key=>&$param){

            // Date timestamp between 2000 & 2030
            if( $param > 1000000000 && $param < 1900000000 ){
                $parameters[$key.'_formatted'] = strftime('%b %d %G at %H:%M', $param);
            }
        }

        return $parameters;
    }
}