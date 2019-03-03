<?php namespace Anomaly\UploadFieldType\Support\Config;

use Anomaly\FilesModule\Folder\Contract\FolderRepositoryInterface;
use Anomaly\SelectFieldType\SelectFieldType;

/**
 * Class FolderHandler
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class FolderHandler
{

    /**
     * Handle the options.
     *
     * @param SelectFieldType $fieldType
     * @param FolderRepositoryInterface $folders
     */
    public function handle(SelectFieldType $fieldType, FolderRepositoryInterface $folders)
    {
        $fieldType->setOptions(
            $folders->all()->pluck('name', 'id')->all()
        );
    }
}
