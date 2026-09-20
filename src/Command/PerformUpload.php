<?php namespace Anomaly\UploadFieldType\Command;

use Anomaly\FilesModule\File\Contract\FileInterface;
use Anomaly\FilesModule\File\Contract\FileRepositoryInterface;
use Anomaly\FilesModule\File\FileUploader;
use Anomaly\FilesModule\Folder\Command\GetFolder;
use Anomaly\UploadFieldType\UploadFieldType;
use Illuminate\Http\Request;

/**
 * Class PerformUpload
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class PerformUpload
{

    /**
     * The field type instance.
     *
     * @var UploadFieldType
     */
    protected $fieldType;

    /**
     * Create a new PerformUpload instance.
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
     * @param FileRepositoryInterface $files
     * @param FileUploader            $uploader
     * @param Request                 $request
     *
     * @return null|FileInterface
     */
    public function handle(FileRepositoryInterface $files, FileUploader $uploader, Request $request)
    {
        $upload = $request->file($this->fieldType->getInputName());
        $value  = $request->get($this->fieldType->getInputName() . '_id');

        /**
         * Make sure we have at least
         * some kind of input.
         */
        if ($upload == null) {

            if (!$value) {
                return null;
            }

            /**
             * The _id input exists only to preserve the file already
             * stored on this field across a save that does not upload a
             * new one. Honour it only when it matches the current value;
             * any other id is not something this field can select.
             */
            $entry   = $this->fieldType->getEntry();
            $current = $entry ? $entry->{$this->fieldType->getColumnName()} : null;

            /* @var FileInterface $file */
            if ($current && $value == $current && $file = $files->find($current)) {
                return $file;
            }

            return null;
        }

        // Make sure we have a valid upload folder. First by slug.
        if (!$folder = dispatch_sync(new GetFolder($this->fieldType->config('folder')))) {
            return null;
        }

        /**
         * Upload through the Files module so the folder's allowed types
         * and the file's contents are validated before anything is written.
         * The uploader throws when the type is not allowed; treat that as
         * no usable upload rather than letting it surface as a 500.
         */
        try {
            return $uploader->upload($upload, $folder);
        } catch (\Exception $e) {
            return null;
        }
    }
}
