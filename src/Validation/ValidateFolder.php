<?php namespace Anomaly\UploadFieldType\Validation;

use Anomaly\FilesModule\Folder\Contract\FolderRepositoryInterface;
use Anomaly\Streams\Platform\Ui\Form\FormBuilder;

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
     * @param FormBuilder               $builder
     * @param FolderRepositoryInterface $folders
     * @param                           $attribute
     * @return bool
     */
    public function handle(FormBuilder $builder, FolderRepositoryInterface $folders, $attribute)
    {
        $fieldType = $builder->getFormField($attribute);

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
