<?php namespace Anomaly\UploadFieldType;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;

/**
 * Class UploadFieldTypeServiceProvider
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\UploadFieldType
 */
class UploadFieldTypeServiceProvider extends AddonServiceProvider
{

    /**
     * The singleton bindings.
     *
     * @var array
     */
    protected $singletons = [
        'Anomaly\UploadFieldType\UploadFieldTypeModifier' => 'Anomaly\UploadFieldType\UploadFieldTypeModifier',
    ];

}
