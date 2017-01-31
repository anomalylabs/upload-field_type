<?php namespace Anomaly\UploadFieldType;

use Anomaly\FilesModule\File\Contract\FileInterface;
use Anomaly\FilesModule\File\Contract\FileRepositoryInterface;
use Anomaly\Streams\Platform\Addon\FieldType\FieldTypeModifier;

/**
 * Class UploadFieldTypeModifier
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\UploadFieldType
 */
class UploadFieldTypeModifier extends FieldTypeModifier
{

    /**
     * The files repository.
     *
     * @var FileRepositoryInterface
     */
    protected $files;

    /**
     * Create a new UploadFieldTypeModifier instance.
     *
     * @param FileRepositoryInterface $files
     */
    public function __construct(FileRepositoryInterface $files)
    {
        $this->files = $files;
    }

    /**
     * Modify the value for database storage.
     *
     * @param  $value
     * @return int
     */
    public function modify($value)
    {
        if ($value instanceof FileInterface) {
            return $value->getId();
        }

        if ($value && $file = $this->files->find($value)) {
            return $file;
        }

        return null;
    }

    /**
     * Restore the value from storage format.
     *
     * @param  $value
     * @return null|FileInterface
     */
    public function restore($value)
    {
        if ($value instanceof FileInterface) {
            return $value;
        }

        if ($value && $file = $this->files->find($value)) {
            return $file;
        }

        return null;
    }
}
