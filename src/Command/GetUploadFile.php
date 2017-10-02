<?php namespace Anomaly\UploadFieldType\Command;

use Anomaly\UploadFieldType\UploadFieldType;
use Illuminate\Http\Request;

/**
 * Class GetUploadFile
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
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
