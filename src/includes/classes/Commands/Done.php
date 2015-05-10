<?php
namespace WebSharks\CliTools\Commands;

use WebSharks\Core\CliTools\Traits;

/**
 * Done command.
 *
 * @since 15xxxx Initial release.
 */
class Done extends \WebSharks\CliTools\CmdBase
{
    use Traits\EnvUtils;
    use Traits\FsSizeUtils;
    use Traits\UrlQueryUtils;
    use Traits\UrlCurrentUtils;
    use Traits\UrlRemoteUtils;

    /**
     * Entry value.
     *
     * @since 15xxxx Initial release.
     *
     * @type string Entry/message.
     */
    protected $entry = '';

    /**
     * Entry type.
     *
     * @since 15xxxx Initial release.
     *
     * @type string Entry type.
     */
    protected $type = '';

    /**
     * Constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct(
            'et:',
            [
                'entry:',
                'type:',
            ]
        );
        if (!empty($this->opts->entry)) {
            $this->entry = $this->opts->entry;
        } elseif (!empty($this->opts->e)) {
            $this->entry = $this->opts->e;
        } else {
            throw new \Exception(
                'One of `-e` or `--entry` is required.'
            );
        }
        if (!empty($this->opts->type)) {
            $this->type = strtolower($this->opts->type);
        } elseif (!empty($this->opts->t)) {
            $this->type = strtolower($this->opts->t);
        }
        if (!in_aray($this->type, ['done', 'todo'], true)) {
            $this->type = 'done'; // Default.
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
        $this->cliStreamOut($this->submitEntry());
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
            'meta_data' => json_encode(['via' => __NAMESPACE__]),
        ]);
        $endpoint = 'https://idonethis.com/api/v0.1/dones/';
        $response = json_decode($this->urlRemote('POST::'.$endpoint, compact('body', 'headers')));
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
