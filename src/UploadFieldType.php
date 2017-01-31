<?php namespace Anomaly\UploadFieldType;

use Anomaly\UploadFieldType\Command\GetUploadFile;
use Anomaly\UploadFieldType\Command\PerformUpload;
use Anomaly\UploadFieldType\Validation\ValidateDisk;
use Anomaly\FilesModule\File\Contract\FileInterface;
use Anomaly\Streams\Platform\Addon\FieldType\FieldType;
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
    protected $inputView = 'anomaly.field_type.file::input';

    /**
     * The field type rules.
     *
     * @var array
     */
    protected $rules = [
        'valid_disk'
    ];

    /**
     * The field type validators.
     *
     * @var array
     */
    protected $validators = [
        'valid_disk' => [
            'handler' => ValidateDisk::class,
            'message' => 'anomaly.field_type.file::validation.valid_disk'
        ]
    ];

    /**
     * The field type config.
     *
     * @var array
     */
    protected $config = [
        'disk' => 'uploads'
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

        $post = str_replace('M', '', ini_get('post_max_size'));
        $file = str_replace('M', '', ini_get('upload_max_filesize'));

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
     * Get the database column name.
     *
     * @return null|string
     */
    public function getColumnName()
    {
        return parent::getColumnName() . '_id';
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
}
