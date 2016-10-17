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
        $this -> parser = new \Mni\FrontYAML\Parser(
            new BridgeParser()
        );
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
        if( empty($parameters) ){
            return [];
        }
        foreach($parameters AS $key=>$param){
            // manage date
            if( $param instanceof \DateTime ){
                $parameters[$key] = new DateStore($param);
            }

        }

        return $parameters;
    }
}

class DateStore {
    public $object, $timestamp, $formatted;
    public function __construct(\DateTime $object){
        $this -> object = $object;
        $this -> timestamp = $object->getTimestamp();
        $this -> formatted = strftime('%b %d %G %H:%M', $this -> timestamp);
        $this -> rss = date('r', $this -> timestamp);
    }
    public function __toString(){
        return $this -> timestamp."";
    }
}