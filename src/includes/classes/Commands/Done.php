<?php
namespace WebSharks\CliTools\Commands;

/**
 * Done command.
 *
 * @since 15xxxx Initial release.
 */
class Done extends \WebSharks\CliTools\Command
{
    public function __construct()
    {
        $this->opts = getopt(
            'abc',
            [
                'alpha',
                'beta',
                'charlie',
            ]
        );
        print_r($this->opts);
    }
}
