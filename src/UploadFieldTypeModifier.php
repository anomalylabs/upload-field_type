<?php namespace Anomaly\UploadFieldType;

use Anomaly\FilesModule\File\Contract\FileInterface;
use Anomaly\FilesModule\File\Contract\FileRepositoryInterface;
use Anomaly\Streams\Platform\Addon\FieldType\FieldTypeModifier;

/**
 * Class UploadFieldTypeModifier
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
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
     * @return int|null
     */
    public function modify($value)
    {
        if ($value instanceof FileInterface) {
            return $value->getId();
        }

        if ($value && $file = $this->files->find($value)) {
            return $file->getId();
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

        /* @var FileInterface $file */
        if ($value && $file = $this->files->find($value)) {
            return $file;
        }

        return null;
    }
}
