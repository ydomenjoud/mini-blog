<?php
/**
 * Created by PhpStorm.
 * User: ydomenjoud
 * Date: 13/10/16
 * Time: 13:44
 */

namespace miniblog\content;


use Mni\FrontYAML\YAML\YAMLParser;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Yaml;

/**
 * Bridge to the Symfony YAML parser
 * adding date converting to yaml parser
 *
 */
class BridgeParser implements YAMLParser
{
    /**
     * @var Parser
     */
    private $parser;

    public function __construct()
    {
        $this->parser = new Parser();
    }

    /**
     * {@inheritdoc}
     */
    public function parse($yaml)
    {
        return $this->parser->parse($yaml, Yaml::PARSE_DATETIME);
    }
}
