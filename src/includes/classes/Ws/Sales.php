<?php
namespace WebSharks\CliTools\Ws;

/**
 * Sales revenue.
 *
 * @since 15xxxx Initial release.
 */
class Sales extends AbsBase
{
    /**
     * @type string Report type.
     *
     * @since 15xxxx Initial release.
     */
    protected $type = '';

    /**
     * @type int Period in days.
     *
     * @since 15xxxx Initial release.
     */
    protected $days = '';

    /**
     * @type int Period in weeks.
     *
     * @since 15xxxx Initial release.
     */
    protected $weeks = '';

    /**
     * @type int Period in months.
     *
     * @since 15xxxx Initial release.
     */
    protected $months = '';

    /**
     * @type int Period in years.
     *
     * @since 15xxxx Initial release.
     */
    protected $years = '';

    /**
     * @type string Start time.
     *
     * @since 15xxxx Initial release.
     */
    protected $start_time = '';

    /**
     * @type string Source pattern.
     *
     * @since 15xxxx Initial release.
     */
    protected $source = '';

    /**
     * @type string SKU pattern.
     *
     * @since 15xxxx Initial release.
     */
    protected $sku = '';

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
            't|type:' => [
                'type'    => 'string',
                'default' => 'gross-daily-revenue',
                'desc'    => 'One of: `gross-[daily|weekly|monthly|yearly]-revenue`',
            ],
            'd|days:' => [
                'type'    => 'string',
                'default' => '7',
                'desc'    => 'For `gross-daily-revenue`; defaults to `7`.',
            ],
            'w|weeks:' => [
                'type'    => 'string',
                'default' => '12',
                'desc'    => 'For `gross-weekly-revenue`; defaults to `12`.',
            ],
            'm|months:' => [
                'type'    => 'string',
                'default' => '12',
                'desc'    => 'For `gross-monthly-revenue`; defaults to `12`.',
            ],
            'y|years:' => [
                'type'    => 'string',
                'default' => '5',
                'desc'    => 'For `gross-yearly-revenue`; defaults to `5`.',
            ],
            's|start-time:' => [
                'type'    => 'string',
                'default' => 'now',
                'desc'    => 'Anything `strtotime()` compatible; defaults to `now`.',
            ],
            'h|source:' => [
                'type'    => 'string',
                'default' => '', // All sources.
                'desc'    => 'Host source pattern (accepts wildcards). Defaults to empty (all sources).'.
                             ' Use single-quotes around wildcards `*` to prevent shell expansion.',
            ],
            'i|sku:' => [
                'type'    => 'string',
                'default' => '', // All items/SKUs.
                'desc'    => 'Item SKU pattern (accepts wildcards). Defaults to empty (all SKUs).'.
                             ' Use single-quotes around wildcards `*` to prevent shell expansion.',
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
        $help .= '$ `'.$ws.' '.$sc.' [options]`'."\n";
        $help .= 'Retrieve sales revenue stats from websharks-inc.com.'."\n\n";

        $help .= '**- BASIC USAGE EXAMPLES -**'."\n\n";
        $help .= '$ `'.$ws.' '.$sc.'`'."\n";
        $help .= '$ `'.$ws.' '.$sc.' --source=zencache.com`'."\n";
        $help .= '$ `'.$ws.' '.$sc.' --source=\'*s2member.com\'`'."\n";
        $help .= '$ `'.$ws.' '.$sc.' --type=gross-weekly-revenue`'."\n\n";

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
        $this->type       = $this->opts->type;
        $this->days       = (integer) $this->opts->days;
        $this->weeks      = (integer) $this->opts->weeks;
        $this->months     = (integer) $this->opts->months;
        $this->years      = (integer) $this->opts->years;
        $this->start_time = $this->opts->{'start-time'};
        $this->source     = $this->opts->source;
        $this->sku        = $this->opts->sku;

        if (empty($this->config->websharks->api_key)) {
            throw new \Exception(
                '`'.$this->config->file.'` is missing `websharks->api_key`.'.
                ' Please see: <http://bit.ly/1zt2n32>'
            );
        }
        $this->CliStream->out($this->statistics());

        exit(0); // All done here.
    }

    /**
     * Retrieve/format statistics.
     *
     * @since 15xxxx Initial release.
     */
    protected function statistics()
    {
        $args = [
            'type'       => $this->type,
            'days'       => $this->days,
            'weeks'      => $this->weeks,
            'months'     => $this->months,
            'years'      => $this->years,
            'start_time' => $this->start_time,
            'source'     => $this->source,
            'sku'        => $this->sku,
            'key'        => $this->config->websharks->api_key,
        ];
        $endpoint = 'https://www.websharks-inc.com/sales/api.php';
        $endpoint = $this->UrlQuery->addArgs($args, $endpoint);
        $response = json_decode($this->UrlRemote->request('GET::'.$endpoint));

        if (!is_object($response) || !empty($response->error)) {
            if (!empty($response->error)) {
                throw new \Exception(
                    'Unable to retrieve statistics.'."\n".
                    'The API said: `'.$response->error.'`'
                );
            }
            throw new \Exception(
                'Unable to retrieve statistics.'."\n".
                'The API call failed w/ an unknown error.'
            );
        }
        $statistics = ''; // Initialize.

        $table                 = new \Console_Table(CONSOLE_TABLE_ALIGN_LEFT, CONSOLE_TABLE_BORDER_ASCII, 1, 'UTF-8', true);
        $table_color           = new \Console_Color2(); // Coloration class.
        $table_colorize_amount = function ($amount) use ($table_color) {
            return $table_color->convert('%G'.$amount.'%n');
        };
        $table_colorize_from = function ($from) use ($table_color) {
            return $table_color->convert('%b'.$from.'%n');
        };
        $table_colorize_to = function ($to) use ($table_color) {
            return $table_color->convert('%b'.$to.'%n');
        };
        $table->setHeaders(['From', 'To', 'Amount']);
        $table->addFilter(0, $table_colorize_from);
        $table->addFilter(1, $table_colorize_to);
        $table->addFilter(2, $table_colorize_amount);

        $chart            = ''; // Initialize.
        $chart_data       = ''; // Initialize.
        $chart_gnuplot    = []; // Initialize.
        $chart_first_from = $chart_last_to = ''; // Initialize.
        $chart_data_file  = $this->FsDir->tmp().'/'.md5(__CLASS__.'gnuplot').'.dat';

        date_default_timezone_set('UTC'); // UTC timezone.

        $statistics .= '**'.$response->title.'**';
        if ($response->caption) { // Caption also?
            $statistics .= ' _*'.$response->caption.'*_';
        }
        $statistics .= "\n"; // Additional line break.

        foreach ($response->results as $_result) {
            $_from = date('D M d, Y @ H:i a T', strtotime($_result->from));
            $_to   = date('D M d, Y @ H:i a T', strtotime($_result->to));
            $table->addRow([$_from, $_to, '$'.$_result->amount]);
        }
        unset($_result); // Housekeeping.

        $statistics .= $table->getTable(); // Append table.

        foreach ($response->results as $_result) {
            if (empty($chart_first_from)) {
                $chart_first_from = date('D M d, Y @ H:i a T', strtotime($_result->from));
            }
            $chart_last_to = date('D M d, Y @ H:i a T', strtotime($_result->to));

            $chart_data .= '"" '.$_result->amount."\n";
        }
        $chart_data = trim($chart_data);
        file_put_contents($chart_data_file, $chart_data);
        unset($_result); // Housekeeping.

        $chart_gnuplot[] = 'gnuplot';
        $chart_gnuplot[] = '-e'; // Eval the following.
        $chart_gnuplot[] = 'set terminal dumb size 83, 20;'.
        ' set yrange [0:*]; set ylabel "Gross Revenue (in USD)";'.
        ' set xlabel "'.$chart_first_from.' - '.$chart_last_to.'";'.
        ' plot "'.str_replace('"', '', $chart_data_file).'" using 2:xtic(1) notitle with histograms';

        exec(implode(' ', array_map('escapeshellarg', $chart_gnuplot)), $chart, $chart_status);

        if ($chart_status === 0) {
            $statistics .= implode("\n", $chart); // Append chart.
        }
        return $statistics;
    }
}
