<?php
namespace WebSharks\CliTools\Ws;

use WebSharks\Core\CliTools\Classes as CoreClasses;

/**
 * Command base abstraction.
 *
 * @since 15xxxx Initial release.
 */
abstract class AbsBase extends CoreClasses\AbsCliSubCmdBase
{
    protected $Os;
    protected $FsDir;
    protected $CliUrl;
    protected $UrlQuery;
    protected $UrlRemote;

    /**
     * Initialize/config.
     *
     * @since 15xxxx Initial release.
     */
    protected function initConfig()
    {
        $this->Os        = $this->Primary->Dicer->get(CoreClasses\Os::class);
        $this->FsDir     = $this->Primary->Dicer->get(CoreClasses\FsDir::class);
        $this->CliUrl    = $this->Primary->Dicer->get(CoreClasses\CliUrl::class);
        $this->UrlQuery  = $this->Primary->Dicer->get(CoreClasses\UrlQuery::class);
        $this->UrlRemote = $this->Primary->Dicer->get(CoreClasses\UrlRemote::class);
    }
}
