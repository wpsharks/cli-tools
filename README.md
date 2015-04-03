## Command line tools for WebSharks™

A collection of scripts that power command-line tools used by WebSharks, Inc.

---

### Depends On `~/.websharks.json`

```json
{
    "config": {
        "user": {
            "name": "jaswsinc",
            "projectsDir": "~/projects"
        },
        "github": {
            "username": "jaswsinc",
            "apiKey": "xxxxxxxxxxxxxxxxxxxxxx"
        },
        "slack": {
            "username": "jaswsinc",
            "apiKey": "xxxxxxxxxxxxxxxxxxxxxx"
        },
        "idonethis": {
            "username": "jaswsinc",
            "apiKey": "xxxxxxxxxxxxxxxxxxxxxx"
        }
    }
}
```

---

### Contributing (How-To)

```bash
$ git clone --recurse-submodules https://github.com/websharks/cli-tools
$ git checkout 000000-dev
```

- Add a new `scripts/[file.(php|bash|etc)]`.

- If your script has dependencies, try to use a submodule.
  ```bash
  $ git submodule add [URL to Git repo] submodules/[package name]
  ```
  Or, you can use a Homebrew package as a dependency; if that is easier.

- If you need configuration data, please obtain those values from `~/.websharks.json`. Your script should trigger an exception if data is missing; i.e., you should alert the user instead of trying to fail gracefully.
  
- Submit a Pull Request so that your script can get merged into this repo. Raam/Jason will review.
 - In your PR, please be sure to include a list of any Homebrew packages that your script requires. Those will need to be added to our Homebrew Formula for these CLI Tools.
 - If your script requires configuration options; i.e., data from `~/.websharks.json`, please include the config. keys needed to use the script effectively. We will need to update the README file so it mentions that new configuration option.
