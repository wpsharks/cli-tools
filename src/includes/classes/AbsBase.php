<?php
namespace WebSharks\CliTools;

use WebSharks\Core\CliTools\Classes;

/**
 * Base Abstraction.
 *
 * @since 15xxxx Initial release.
 */
abstract class AbsBase extends Classes\AbsBase
{
    /**
     * @type Ws Instance.
     *
     * @since 15xxxx Initial release.
     */
    protected $ws;

    /**
     * Constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();

        $this->ws = &$GLOBALS[GLOBAL_NS];
    }
}
