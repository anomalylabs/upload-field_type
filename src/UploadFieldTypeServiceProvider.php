<?php namespace Anomaly\UploadFieldType;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;

/**
 * Class UploadFieldTypeServiceProvider
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class UploadFieldTypeServiceProvider extends AddonServiceProvider
{

    /**
     * The singleton bindings.
     *
     * @var array
     */
    protected $singletons = [
        UploadFieldTypeModifier::class => UploadFieldTypeModifier::class,
    ];

}
