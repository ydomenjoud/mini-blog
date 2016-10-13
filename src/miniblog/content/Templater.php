<?php
/**
 * Created by PhpStorm.
 * User: ydomenjoud
 * Date: 12/10/16
 * Time: 16:29
 */

namespace MiniBlog\Content;


use Pug\Pug;

class Templater
{

    private $engine;
    private $directory;

    /**
     * Templater constructor.
     */
    public function __construct()
    {
        $this -> directory = TEMPLATE_DIR . DIRECTORY_SEPARATOR;
        $this -> engine = new Pug();

    }


    /**
     * transpile a template file
     * @param $layout
     * @param array $parameters
     * @return html compiled
     */
    public function render($layout, $parameters = []){

        $layout_file = $this->directory. $layout;
//
//        var_dump($layout_file, $parameters);
//        die();

        return  $this -> engine -> render($layout_file, $parameters);
    }


}