<?php
namespace WebSharks\CliTools\Ws;

/**
 * GitHub issue to KBA.
 *
 * @since 15xxxx Initial release.
 */
class I2Kba extends AbsBase
{
    /**
     * @type string Issue.
     *
     * @since 15xxxx Initial release.
     */
    protected $issue = '';

    /**
     * @type string Branch.
     *
     * @since 15xxxx Initial release.
     */
    protected $branch = '';

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
            'b|branch:' => [
                'type'    => 'string',
                'default' => 'master',
                'desc'    => 'Branch; defaults to `master`.',
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
        $help .= '$ `'.$ws.' '.$sc.' [options] [issue]`'."\n";
        $help .= 'Convert an Issue to a pending KB article.'."\n\n";

        $help .= '**- BASIC USAGE EXAMPLES -**'."\n\n";
        $help .= '$ `'.$ws.' '.$sc.' \'owner/repo#123\'`'."\n";
        $help .= '$ `'.$ws.' '.$sc.' \'https://github.com/owner/repo/issues/123\'`'."\n\n";

        $help .= '**- ALTERNATE USAGE EXAMPLES -**'."\n\n";
        $help .= '$ `echo [issue] | '.$ws.' '.$sc.'` _*(i.e., pipe [issue] as STDIN)*_'."\n";
        $help .= '$ `cat [file containing issue] | '.$ws.' '.$sc.'` _*(i.e., pipe file contents as STDIN)*_'."\n\n";

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
            $this->issue = $this->stdin;
        } elseif (!empty($this->args[1])) {
            $this->issue = $this->args[1];
        }
        if (!($this->issue = trim($this->issue))) {
            throw new \Exception(
                'Issue required.'
            );
        }
        if (!empty($this->opts->branch)) {
            $this->branch = $this->opts->branch;
        }
        if (!($this->branch = trim($this->branch))) {
            throw new \Exception(
                'Branch required.'
            );
        }
        if (empty($this->config->github->api_key)) {
            throw new \Exception(
                '`'.$this->config->file.'` is missing `github->api_key`.'.
                ' Please see: <http://bit.ly/1zt2n32>'
            );
        }
        $this->CliStream->out($this->convert());

        exit(0); // All done here.
    }

    /**
     * Converter.
     *
     * @since 15xxxx Initial release.
     */
    protected function convert()
    {
        $args = [
            'issue'  => $this->issue,
            'branch' => $this->branch,
            'format' => 'new_articles_list_url',
            'token'  => $this->config->github->api_key,
        ];
        $endpoint = 'http://www.websharks-inc.com/github/i2kba-convert.php';
        $endpoint = $this->UrlQuery->addArgs($args, $endpoint);
        $response = trim($this->UrlRemote->request('GET::'.$endpoint));

        if (!$response || !preg_match('/^https?\:\/\//i', $response)) {
            throw new \Exception(
                'Unable to convert `'.$this->issue.'`'."\n".
                'The API said: '.$response
            );
        }
        return '<'.$response.'>';
    }
}
