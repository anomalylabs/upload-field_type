<?php namespace Anomaly\UploadFieldType\Command;

use Anomaly\UploadFieldType\UploadFieldType;
use Illuminate\Http\Request;

/**
 * Class GetUploadFile
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\UploadFieldType\Command
 */
class GetUploadFile
{

    /**
     * The field type instance.
     *
     * @var UploadFieldType
     */
    protected $fieldType;

    /**
     * Create a new GetUploadFile instance.
     *
     * @param UploadFieldType $fieldType
     */
    public function __construct(UploadFieldType $fieldType)
    {
        $this->fieldType = $fieldType;
    }

    /**
     * Handle the command.
     *
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function handle(Request $request)
    {
        return $request->file($this->fieldType->getInputName());
    }
}
