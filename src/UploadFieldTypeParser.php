<?php namespace Anomaly\UploadFieldType;

use Anomaly\Streams\Platform\Support\Parser;

/**
 * Class UploadFieldTypeParser
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\UploadFieldType
 */
class UploadFieldTypeParser
{

    /**
     * The parser utility.
     *
     * @var Parser
     */
    protected $parser;

    /**
     * Create a new UploadFieldTypeParser instance.
     *
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Return the parsed target.
     *
     * @param               $target
     * @param UploadFieldType $fieldType
     * @return mixed
     */
    public function parse($target, UploadFieldType $fieldType)
    {
        $entry = $fieldType->getEntry();

        return $this->parser->parse($target, compact('entry'));
    }
}
