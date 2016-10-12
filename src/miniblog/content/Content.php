<?php

namespace MiniBlog\Content;


class Content
{

    private $pages_list = [];
    private $parser;
    private $cache;
    private $templater;

    public function __construct(){
        $this -> cache = new Cache();
        $this -> parser = new Parser();
        $this -> templater = new Templater();

        $this -> pages_list = $this -> buildPagesList();
    }


    /**
     * check if the content page exist
     * @param string $uri
     * @return bool
     */
    public function exists( string $uri){
        $file = CONTENT_DIR. DIRECTORY_SEPARATOR . $uri . CONTENT_EXTENSION;
        return file_exists($file);
    }

    /**
     * Get a page
     * @param string $uri
     * @return string the content of page
     */
    public function get( string $uri ){

        // if content file don't exists, this is page was deleted
        if( !$this -> exists($uri) ){
            return '';
        }

        // if not cached, or if cache is outdated  , (re)build cache
        if( !$this -> cache -> exists($uri . CACHE_EXTENSION) ) {


            // parse file
            $informations = $this -> parser -> parse( $uri );

            // build template vars
            $vars = [];
            $vars['content'] = $informations['content'];
            $vars['page'] = $informations['parameters'];
            $vars['template_directory'] = TEMPLATE_DIR;
            $vars['pages_list'] = $this->pages_list;
            $vars['url'] = $uri . CACHE_EXTENSION;

            // render layout
            $layout = $uri=='index' ? 'index.pug' : 'post.pug';
            $content = $this -> templater ->render($layout, $vars);

            // cache page
            $this->cache -> write($uri. CACHE_EXTENSION, $content);
        }

        // get cache @TODO optimize by reducing file access ( content was generated above )
        $content = $this -> cache -> get( $uri . CACHE_EXTENSION );


        return $content;

    }


    /**
     * Calcul and build list of pages
     * @return array
     */
    private function buildPagesList(){

        // @TODO cache pages list

        $pages_list = [];

        // vars
        $path = CONTENT_DIR;
        $content_extension = CONTENT_EXTENSION;
        $cache_extension = CACHE_EXTENSION;

        $files_list = File::browse($path, $content_extension);

        foreach( $files_list as $filename){
            // get uri
            $uri = $this -> getUri($filename);

            // parse information
            $informations = $this -> parser -> parse( $uri );
            $parameters = $informations['parameters'];

            // if not publish date or publish date not passed, continue
            if( !$parameters
                || !array_key_exists('publish', $parameters)
                || empty($parameters['publish'])
                || $parameters['publish'] > time()
                || ( array_key_exists('page', $parameters) && $parameters['page'] == true )  ){
                continue;
            }

            // tags
            $tags = [];
            if( array_key_exists('tags', $parameters) || !empty($parameters['tags']) ){
                $tags = explode(' ', $parameters['tags'] );
            }

            // add to pages list
            $pages_list[$uri] = [
                'url' => '/'.$uri . $cache_extension,
                'publish' => $parameters['publish'],
                'title' => $parameters['title'],
                'tags' => $tags,
                'md5' => md5_file($filename )
            ];
        }

        // sort by publish date
        uasort($pages_list, function($a,$b){
            return $b['publish'] <=> $a['publish'];
        });

//        // manage cache
        $cache_file = 'pages_list';
        $data = serialize($pages_list);
//
//        // compare
        $previous_list = $this -> cache -> get( $cache_file);
        if( $data != $previous_list ){
            $this -> cache -> clean();
        } else {
        }
//
//        // cache file
//        $this -> cache -> write($cache_file, $data);

        return $pages_list;

    }


    /**
     * get Uniq resource identifier for the path
     * @param $path
     * @return string
     */
    public function getUri($path){

        $uri = str_replace(['../','./'],'', $path); // protect path

        $content_extension = CONTENT_EXTENSION;
        $cache_extension = CACHE_EXTENSION;
        $content_dir = getcwd() . DIRECTORY_SEPARATOR . CONTENT_DIR . DIRECTORY_SEPARATOR ;


        // remove directory
        if( strpos($uri, $content_dir) !== false ){
            $uri = substr($uri, strlen($content_dir) ); // remove content dir
        }

        // remove extension cache
        if( substr($uri, -strlen($cache_extension))  === $cache_extension ){
            $uri = substr($uri, 0, -strlen($cache_extension) );
        }

        // remove extension content
        if( substr($uri, -strlen($content_extension))  === $content_extension ){
            $uri = substr($uri, 0, -strlen($content_extension) );
        }


        return $uri;
    }


}