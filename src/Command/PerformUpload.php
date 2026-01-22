<?php namespace Anomaly\UploadFieldType\Command;

use Anomaly\FilesModule\File\Contract\FileInterface;
use Anomaly\FilesModule\File\Contract\FileRepositoryInterface;
use Anomaly\FilesModule\Folder\Command\GetFolder;
use Anomaly\UploadFieldType\UploadFieldType;
use Illuminate\Filesystem\FilesystemManager;
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
     * @param FilesystemManager       $manager
     * @param Request                 $request
     *
     * @return null|FileInterface
     */
    public function handle(FileRepositoryInterface $files, FilesystemManager $manager, Request $request)
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

            /* @var FileInterface $file */
            if ($file = $files->find($value)) {
                return $file;
            }

            return null;
        }

        // Make sure we have a valid upload folder. First by slug.
        if (!$folder = dispatch_sync(new GetFolder($this->fieldType->config('folder')))) {
            return null;
        }

        // Get a unique filename just in case there is one already in the filesystem.
        $filename = dispatch_sync(new GetUniqueFilename($manager, $folder, $upload));

        // Get the disk from the folder and write the file.
        $disk = $folder->getDisk();
        $path = $folder->getSlug() . '/' . $filename;

        // Write the file using the disk's filesystem.
        $file = $manager->disk($disk->getSlug())->writeStream(
            $path,
            fopen($upload->getRealPath(), 'r+')
        );

        return $file;
    }
}
