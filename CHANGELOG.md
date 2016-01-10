## v160110

- Config file can now be named `.ws.json` or `.websharks.json` (either is fine).
- Config file may now contain the primary key `cli_tools` instead of `config` (either is fine).
- The `shorten` sub-command now outputs a plain-text URL suitable for piping to `pbcopy`.
- The `shorten` sub-command now reads a long URL from the clipboard automatically whenever there is no argument and no STDIN.
- The `shorten` sub-command now automatically copies the short URL to your clipboard. Pass `--no-copy` to avoid the new default behavior.
