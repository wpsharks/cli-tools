<?php
namespace WebSharks\CliTools;

use WebSharks\Core\CliTools\Interfaces;
use WebSharks\Core\CliTools\Classes;
use WebSharks\Core\CliTools\Traits;

/**
 * Base Abstraction.
 *
 * @since 15xxxx Initial release.
 */
abstract class AbsBase extends Classes\AbsBase
{
    /*
     * Properties
     */

    /**
     * @type array Instance cache.
     *
     * @since 15xxxx Initial release.
     */
    protected $tool;

    /*
     * Constructor
     */

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();
    }
}
