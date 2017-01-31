<?php namespace Anomaly\UploadFieldType\Validation;

use Anomaly\FilesModule\Disk\Contract\DiskRepositoryInterface;
use Anomaly\Streams\Platform\Ui\Form\FormBuilder;
use Illuminate\Contracts\Bus\SelfHandling;

/**
 * Class ValidateDisk
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\UploadFieldType\Validation
 */
class ValidateDisk implements SelfHandling
{

    /**
     * Handle the validation.
     *
     * @param FormBuilder             $builder
     * @param DiskRepositoryInterface $disks
     * @param                         $attribute
     * @return bool
     */
    public function handle(FormBuilder $builder, DiskRepositoryInterface $disks, $attribute)
    {
        $fieldType = $builder->getFormField($attribute);

        $disk = array_get($fieldType->getConfig(), 'disk');

        if (is_numeric($disk) && !$disks->find($disk)) {
            return false;
        }

        if (!is_numeric($disk) && !$disks->findBySlug($disk)) {
            return false;
        }

        return true;
    }
}
