<?php namespace Anomaly\UploadFieldType\Command;

use Anomaly\FilesModule\Folder\Contract\FolderInterface;
use Illuminate\Http\UploadedFile;
use League\Flysystem\MountManager;

/**
 * Class GetUniqueFilename
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GetUniqueFilename
{
    protected $manager;
    protected $folder;
    protected $upload;

    /**
     * GetUniqueFilename constructor.
     *
     * @param MountManager    $manager
     * @param FolderInterface $folder
     * @param UploadedFile    $upload
     */
    public function __construct(MountManager $manager, FolderInterface $folder, UploadedFile $upload)
    {
        $this->manager = $manager;
        $this->folder  = $folder;
        $this->upload  = $upload;
    }

    /**
     * Get a unique filename from the mount manager.
     *
     * @return mixed|null|string
     */
    public function handle()
    {
        // Store the parts of the file we will need to make a unique filename.
        $filename       = $this->upload->getClientOriginalName();
        $fileExtension  = $this->upload->getClientOriginalExtension();
        $incrementValue = 1;

        // Increment the value until the manager says it doesn't have that path.
        while ($this->manager->has($this->folder->path($filename))) {

            // Replace the filename extension with a number before the extension.
            $filename = str_replace(
                '.' . $fileExtension,
                '-' . $incrementValue . '.' . $fileExtension,
                $this->upload->getClientOriginalName()
            );

            $incrementValue++;
        }

        return $filename;
    }
}
