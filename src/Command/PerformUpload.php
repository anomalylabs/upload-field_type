<?php namespace Anomaly\UploadFieldType\Command;

use Anomaly\FilesModule\File\Contract\FileInterface;
use Anomaly\FilesModule\File\Contract\FileRepositoryInterface;
use Anomaly\FilesModule\Folder\Command\GetFolder;
use Anomaly\UploadFieldType\UploadFieldType;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use League\Flysystem\MountManager;

/**
 * Class PerformUpload
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\UploadFieldType\Command
 */
class PerformUpload
{

    use DispatchesJobs;

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
     * @param MountManager            $manager
     * @param Request                 $request
     *
     * @return null|FileInterface
     */
    public function handle(FileRepositoryInterface $files, MountManager $manager, Request $request)
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
        if (!$folder = $this->dispatch(new GetFolder($this->fieldType->config('folder')))) {
            return null;
        }

        // Get a unique filename just in case there is one already in the filesystem.
        $filename = $this->dispatch(new GetUniqueFilename($manager, $folder, $upload));

        // Write the file.
        $file = $manager->putStream(
            $folder->path($filename),
            fopen($upload->getRealPath(), 'r+')
        );

        return $file;
    }
}
