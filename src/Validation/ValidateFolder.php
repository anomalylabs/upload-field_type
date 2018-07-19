<?php namespace Anomaly\UploadFieldType\Validation;

use Anomaly\FilesModule\Folder\Contract\FolderRepositoryInterface;
use Anomaly\Streams\Platform\Ui\Form\FormBuilder;
use Anomaly\UploadFieldType\UploadFieldType;

/**
 * Class ValidateFolder
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class ValidateFolder
{

    /**
     * Handle the validation.
     *
     * @param FolderRepositoryInterface $folders
     * @param                           $attribute
     * @return bool
     */
    public function handle(FolderRepositoryInterface $folders, UploadFieldType $fieldType)
    {
        $folder = array_get($fieldType->getConfig(), 'folder');

        if (is_numeric($folder) && !$folders->find($folder)) {
            return false;
        }

        if (!is_numeric($folder) && !$folders->findBySlug($folder)) {
            return false;
        }

        return true;
    }
}
