<?php
namespace WebSharks\CliTools\Ws;

/**
 * Done command.
 *
 * @since 15xxxx Initial release.
 */
class Done extends AbsBase
{
    /**
     * @type string Entry message.
     *
     * @since 15xxxx Initial release.
     */
    protected $entry = '';

    /**
     * @type string Entry type.
     *
     * @since 15xxxx Initial release.
     */
    protected $type = 'done';

    /**
     * Initialize/config.
     *
     * @since 15xxxx Initial release.
     */
    protected function initConfig()
    {
        parent::initConfig();

        if ($this->sub_command->slug === 'todo') {
            $this->type = 'todo';
        }
    }

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
            'oneline' => [
                'desc' => 'Ask for one line of STDIN instead of reading [entry] arg.',
            ],
            'multiline' => [
                'desc' => 'Ask for STDIN instead of reading [entry] arg. Accepts multiple lines.'.
                          ' Press `Ctrl-D` to close STDIN; i.e., to finish and submit entry.',
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
        $help .= '$ `'.$ws.' '.$sc.' [options] [entry]`'."\n";
        $help .= 'Post '.$td.' entries w/ the iDoneThis API.'."\n\n";

        $help .= '**- BASIC USAGE EXAMPLE -**'."\n\n";
        $help .= '$ `'.$ws.' '.$sc.' \'Message entry here.\'`'."\n\n";

        $help .= '**- ALTERNATE USAGE EXAMPLES -**'."\n\n";
        $help .= '$ `echo \'Message entry here.\' | '.$ws.' '.$sc.'` _*(i.e., pipe [entry] as STDIN)*_'."\n";
        $help .= '$ `cat [file] | '.$ws.' '.$sc.'` _*(i.e., pipe file contents as STDIN)*_'."\n";
        $help .= '$ `'.$ws.' '.$sc.' --online` _*(i.e., ask for one line of STDIN)*_'."\n";
        $help .= '$ `'.$ws.' '.$sc.' --multiline` _*(i.e., ask for multiline STDIN)*_'."\n\n";

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
            $this->entry = $this->stdin;
        } elseif ($this->opts->multiline) {
            $this->entry = $this->CliStream->in();
        } elseif ($this->opts->oneline) {
            $this->entry = $this->CliStream->in(1);
        } elseif (!empty($this->args[1])) {
            $this->entry = $this->args[1];
        }
        if (!($this->entry = trim($this->entry))) {
            throw new \Exception(
                'Message [entry] required.'
            );
        }
        if (empty($this->config->idonethis->username)) {
            throw new \Exception(
                '`'.$this->config->file.'` is missing `idonethis->username`.'.
                ' Please see: <http://bit.ly/1zt2n32>'
            );
        }
        if (empty($this->config->idonethis->api_key)) {
            throw new \Exception(
                '`'.$this->config->file.'` is missing `idonethis->api_key`.'.
                ' Please see: <http://bit.ly/1zt2n32>'
            );
        }
        if ($this->type === 'todo' && strpos($this->entry, '[ ]') !== 0) {
            $this->entry = '[ ] '.$this->entry;
        }
        if ($this->type === 'todo' && stripos($this->entry, '#'.$this->config->idonethis->username) === false) {
            $this->entry .= ' #'.$this->config->idonethis->username;
        }
        $this->CliStream->out($this->submitEntry());

        exit(0); // All done here.
    }

    /**
     * Entry submission.
     *
     * @since 15xxxx Initial release.
     */
    protected function submitEntry()
    {
        $headers = [
            'Authorization: Token '.$this->config->idonethis->api_key,
            'Content-Type: application/json',
            'Accept: application/json',
        ];
        $body = json_encode([
            'team'      => 'websharks',
            'raw_text'  => $this->entry,
            'meta_data' => json_encode(['via' => __CLASS__]),
        ]);
        $endpoint = 'https://idonethis.com/api/v0.1/dones/';
        $response = json_decode($this->UrlRemote->request('POST::'.$endpoint, compact('body', 'headers')));
        $r        = &$response; // Shorter reference.

        if (!is_object($r) || empty($r->ok) || empty($r->result->permalink)) {
            if (!empty($r->detail)) {
                $error = (string) $r->detail;
            } else {
                $error = 'Unknown error; possible connection failure.';
            }
            throw new \Exception(
                'Unable to add '.strtoupper($this->type).' entry.'."\n".
                'The iDoneThis API said: `'.rtrim($error, '.').'`.'
            );
        }
        return '<'.$r->result->permalink.'>';
    }
}
