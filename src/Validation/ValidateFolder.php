<?php namespace Anomaly\UploadFieldType\Validation;

use Anomaly\FilesModule\Folder\Contract\FolderRepositoryInterface;
use Anomaly\Streams\Platform\Ui\Form\FormBuilder;
use Illuminate\Contracts\Bus\SelfHandling;

/**
 * Class ValidateFolder
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\UploadFieldType\Validation
 */
class ValidateFolder implements SelfHandling
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
