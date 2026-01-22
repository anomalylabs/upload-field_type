<?php namespace Anomaly\UploadFieldType\Command;

use Anomaly\FilesModule\File\FileSanitizer;
use Anomaly\FilesModule\Folder\Contract\FolderInterface;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;

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
     * @param FilesystemManager $manager
     * @param FolderInterface $folder
     * @param UploadedFile $upload
     */
    public function __construct(FilesystemManager $manager, FolderInterface $folder, UploadedFile $upload)
    {
        $this->manager = $manager;
        $this->folder  = $folder;
        $this->upload  = $upload;
    }

    /**
     * Get a unique filename from the filesystem manager.
     *
     * @return mixed|null|string
     */
    public function handle()
    {
        // Store the parts of the file we will need to make a unique filename.
        $filename       = $this->upload->getClientOriginalName();
        $fileExtension  = $this->upload->getClientOriginalExtension();
        $incrementValue = 1;

        // Protect against dangerous file names.
        $filename = FileSanitizer::clean($filename);

        // Get the disk from the folder.
        $disk = $this->folder->getDisk();
        $filesystem = $this->manager->disk($disk->getSlug());

        // Increment the value until the filesystem says it doesn't have that path.
        while ($filesystem->fileExists($this->folder->getSlug() . '/' . $filename)) {

            // Replace the filename extension with a number before the extension.
            $filename = str_replace(
                '.' . $fileExtension,
                '-' . $incrementValue . '.' . $fileExtension,
                $filename
            );

            $incrementValue++;
        }

        return $filename;
    }
}
