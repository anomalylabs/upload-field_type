<?php namespace Anomaly\UploadFieldType;

use Anomaly\FilesModule\File\Command\GetFile;
use Anomaly\FilesModule\File\Contract\FileInterface;
use Anomaly\Streams\Platform\Addon\FieldType\FieldType;
use Anomaly\UploadFieldType\Command\GetUploadFile;
use Anomaly\UploadFieldType\Command\PerformUpload;
use Anomaly\UploadFieldType\Table\ValueTableBuilder;
use Anomaly\UploadFieldType\Validation\ValidateFolder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UploadFieldType
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\UploadFieldType
 */
class UploadFieldType extends FieldType
{

    /**
     * The default input class.
     *
     * @var string
     */
    protected $class = '';

    /**
     * The underlying database column type
     *
     * @var string
     */
    protected $columnType = 'integer';

    /**
     * The input view.
     *
     * @var string
     */
    protected $inputView = 'anomaly.field_type.upload::input';

    /**
     * The field type rules.
     *
     * @var array
     */
    protected $rules = [
        'valid_folder',
    ];

    /**
     * The field type validators.
     *
     * @var array
     */
    protected $validators = [
        'valid_folder' => [
            'handler' => ValidateFolder::class,
            'message' => 'anomaly.field_type.upload::validation.valid_folder',
        ],
    ];

    /**
     * The field type config.
     *
     * @var array
     */
    protected $config = [
        'disk' => 'uploads',
    ];

    /**
     * Get the relation.
     *
     * @return BelongsTo
     */
    public function getRelation()
    {
        $entry = $this->getEntry();

        return $entry->belongsTo(
            array_get($this->config, 'related', 'Anomaly\FilesModule\File\FileModel'),
            $this->getColumnName()
        );
    }

    /**
     * Get the rules.
     *
     * @return array
     */
    public function getRules()
    {
        $rules = parent::getRules();

        if ($image = array_get($this->getConfig(), 'image')) {
            $rules[] = 'image';
        }

        if ($mimes = array_get($this->getConfig(), 'mimes')) {

            if (in_array('jpg', $mimes) || in_array('jpeg', $mimes)) {
                $mimes = array_unique(array_merge($mimes, ['jpg', 'jpeg']));
            }

            $rules[] = 'mimes:' . implode(',', $mimes);
        }

        if ($max = array_get($this->getConfig(), 'max')) {
            $rules[] = 'max:' . $max * 1000;
        }

        return $rules;
    }

    /**
     * Get the config.
     *
     * @return array
     */
    public function getConfig()
    {
        $config = parent::getConfig();

        $post = $this->getSize('post_max_size') ;
        $file = $this->getSize('upload_max_filesize');

        $server = $file > $post ? $post : $file;

        if (!$max = array_get($config, 'max')) {
            $max = $server;
        }

        if ($max > $server) {
            $max = $server;
        }

        array_set($config, 'max', $max);

        return $config;
    }

    /**
     * Get the post value.
     *
     * @param null $default
     * @return null|FileInterface
     */
    public function getPostValue($default = null)
    {
        return $this->dispatch(new PerformUpload($this));
    }

    /**
     * Get the value intended for repopulating
     * field after validation has failed.
     *
     * In this case just return the ID
     * of any existing value file.
     *
     * @param  null $default
     * @return mixed
     */
    public function getRepopulateValue($default = null)
    {
        return $this->dispatch(new GetFile(array_get($_POST, $this->getInputName() . '_id', $default)));
    }

    /**
     * Get the value to validate.
     *
     * @param null $default
     * @return FileInterface
     */
    public function getValidationValue($default = null)
    {
        return $this->dispatch(new GetUploadFile($this));
    }

    /**
     * Return the required flag.
     *
     * @return bool
     */
    public function isRequired()
    {
        if ($_POST && $value = array_get($_POST, $this->getInputName() . '_id')) {
            return false;
        }

        return parent::isRequired();
    }

    /**
     * Get the database column name.
     *
     * @return null|string
     */
    public function getColumnName()
    {
        return parent::getColumnName() . '_id';
    }

    /**
     * Get php.ini value and convert it to Mb if needed.
     *
     * @param $key
     * @return float|int
     */
    public function getSize($key)
    {
        preg_match('/([0-9]*)(K|M|G)/im', ini_get($key), $matches);
        return $matches[2] === 'K' ? $matches[1] / 1024 : $matches[2] === 'G' ? $matches[1] * 1024 : $matches[1];
    }
}
