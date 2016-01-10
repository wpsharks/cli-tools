<?php
namespace WebSharks\CliTools\Ws;

/**
 * URL shortener.
 *
 * @since 15xxxx Initial release.
 */
class Shorten extends AbsBase
{
    /**
     * @type string Long URL.
     *
     * @since 15xxxx Initial release.
     */
    protected $long_url = '';

    /**
     * @type string Short URL.
     *
     * @since 15xxxx Initial release.
     */
    protected $short_url = '';

    /**
     * @type bool Open?
     *
     * @since 15xxxx Initial release.
     */
    protected $open = false;

    /**
     * @type bool No copy?
     *
     * @since 15xxxx Initial release.
     */
    protected $no_copy = false;

    /**
     * Option specs.
     *
     * @since 15xxxx Initial release.
     *
     * @return array An array of opt. specs.
     */
    protected function optSpecs()
    {
        return [
            'o|open' => [
                'desc' => 'Flag opens the response URL; i.e., navigate to shortlink?',
            ],
            'no-copy' => [
                'desc' => 'Flag forces a no-copy operation; i.e., do not copy short URL to clipboard?',
            ],
        ];
    }

    /**
     * Help output/display.
     *
     * @since 15xxxx Initial release.
     */
    protected function showHelpExit()
    {
        $ws = $this->command->slug;
        $sc = $this->sub_command->slug;

        $help = '**- NAME -**'."\n\n";
        $help .= '`'.$ws.' '.$sc.'`'."\n\n";

        $help .= '**- SYNOPSIS -**'."\n\n";
        $help .= '$ `'.$ws.' '.$sc.' [options] [URL]`'."\n";
        $help .= 'Shorten a URL using w/ wsharks.com.'."\n\n";

        $help .= '**- BASIC USAGE EXAMPLE -**'."\n\n";
        $help .= '$ `'.$ws.' '.$sc.' \'http://example.com/really/long/request/uri/\'`'."\n\n";

        $help .= '**- ALTERNATE USAGE EXAMPLES -**'."\n\n";
        $help .= '$ `echo [URL] | '.$ws.' '.$sc.'` _*(i.e., pipe [URL] as STDIN)*_'."\n";
        $help .= '$ `cat [file containing URL] | '.$ws.' '.$sc.'` _*(i.e., pipe file contents as STDIN)*_'."\n";
        $help .= '$ `'.$ws.' '.$sc.'` _*(i.e., can detect URL in clipboard on a Mac)*_'."\n\n";

        $help .= '**- OPTIONS FOR THIS SUB-COMMAND -**'."\n\n";
        $help .= $this->CliOpts->specs();

        $this->CliStream->out($help);

        exit(0); // All done here.
    }

    /**
     * Command runner.
     *
     * @since 15xxxx Initial release.
     */
    protected function runOutputExit()
    {
        if (!empty($this->stdin)) {
            $this->long_url = $this->stdin;
        } elseif (!empty($this->args[1])) {
            $this->long_url = $this->args[1];
        } elseif ($this->Os->isMac() && filter_var($_pbpaste = trim(`pbpaste`), FILTER_VALIDATE_URL)) {
            $this->long_url = $_pbpaste;
        } // unset($_pbpaste);

        if (!($this->long_url = trim($this->long_url))) {
            throw new \Exception('Input URL (or URL in clipboard) is required.');
        }
        $this->open      = $this->opts->open;
        $this->no_copy   = $this->opts->no_copy;
        $this->short_url = $this->shorten();

        if ($this->Os->isMac() && !$this->no_copy) {
            `printf '%s' '$this->short_url' | pbcopy`;
        }
        if ($this->open) {
            $this->CliUrl->open($this->short_url);
        }
        $this->CliStream->out($this->short_url);

        exit(0); // All done here.
    }

    /**
     * URL shortener.
     *
     * @since 15xxxx Initial release.
     */
    protected function shorten()
    {
        $args = [
            'format'   => 'text',
            'long_url' => $this->long_url,
        ];
        $endpoint  = 'https://www.websharks-inc.com/bitly/shortener.php';
        $endpoint  = $this->UrlQuery->addArgs($args, $endpoint);
        $short_url = $this->UrlRemote->request('GET::'.$endpoint);

        if (!$short_url) {
            throw new \Exception(
                'Unable to shorten <'.$this->long_url.'>'."\n".
                'The API call failed w/ an unknown error.'
            );
        }
        return $url = $short_url;
    }
}
