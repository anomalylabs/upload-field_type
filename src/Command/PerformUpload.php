<?php namespace Anomaly\UploadFieldType\Command;

use Anomaly\UploadFieldType\UploadFieldType;
use Anomaly\UploadFieldType\UploadFieldTypeParser;
use Anomaly\FilesModule\Disk\Contract\DiskRepositoryInterface;
use Anomaly\FilesModule\File\Contract\FileInterface;
use Anomaly\FilesModule\File\Contract\FileRepositoryInterface;
use Illuminate\Contracts\Bus\SelfHandling;
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
class PerformUpload implements SelfHandling
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
     * @param DiskRepositoryInterface $disks
     * @param FileRepositoryInterface $files
     * @param UploadFieldTypeParser     $parser
     * @param Request                 $request
     * @param MountManager            $manager
     *
     * @return null|bool|FileInterface
     */
    public function handle(
        DiskRepositoryInterface $disks,
        FileRepositoryInterface $files,
        UploadFieldTypeParser $parser,
        Request $request,
        MountManager $manager
    ) {
        $path = trim(array_get($this->fieldType->getConfig(), 'path'), './');

        $file  = $request->file($this->fieldType->getInputName());
        $value = $request->get($this->fieldType->getInputName() . '_id');

        /**
         * Make sure we have at least
         * some kind of input.
         */
        if ($file === null) {

            if (!$value) {
                return null;
            }

            return $files->find($value);
        }

        // Make sure we have a valid upload disk. First by slug.
        if (!$disk = $disks->findBySlug($slug = array_get($this->fieldType->getConfig(), 'disk'))) {
            // If that fails look up by id.
            if (!$disk = $disks->find($id = array_get($this->fieldType->getConfig(), 'disk'))) {
                return null;
            }
        }

        // Make the path.
        $path = $parser->parse($path, $this->fieldType);
        $path = (!empty($path) ? $path . '/' : null) . $upload->getClientOriginalName();

        return $manager->putStream($disk->path($path), fopen($upload->getRealPath(), 'r+'));
    }
}
