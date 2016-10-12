<?php
/**
 * Created by PhpStorm.
 * User: ydomenjoud
 * Date: 12/10/16
 * Time: 15:50
 */

namespace MiniBlog;

use MiniBlog\Content\Content;

class Blog
{
    private $content;

    public function __construct()
    {
        $this -> content = new Content();

        # get params
        $url = $_GET['url'] ?? 'index.html';

        // start
        $this -> show($url);
    }

    /**
     * show an url content
     * @param string $url
     */
    public function show(string $url) {


        // get uri for this resources
        $page = $this -> content -> getUri($url);

        if( $builtPage = $this -> content -> get($page) ){
            $content = $builtPage;
        }
        // 404
        else {
            header("HTTP/1.0 404 Not Found");
            $content = 'Error 404 : file not found';
        }
        // display
        $this -> display( $content );

    }


    /**
     * Display $content
     * @param string $content
     */
    private function display( string $content ){

        echo $content;
        die();
    }

}