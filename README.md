## Command Line Tools for WebSharks™

A collection of scripts that power command-line tools used by WebSharks, Inc.

<img src="http://cdn.websharks-inc.com/websharks/uploads/2013/11/sharks-logo.png" width="150" align="right" />

[![](https://img.shields.io/github/license/websharks/cli-tools.svg)](https://github.com/websharks/cli-tools/blob/HEAD/LICENSE.txt)
[![](https://img.shields.io/badge/made-w%2F_100%25_pure_awesome_sauce-AB815F.svg?label=made)](http://websharks-inc.com/)
[![](https://img.shields.io/badge/by-WebSharks_Inc.-656598.svg?label=by)](http://www.websharks-inc.com/team/)
[![](https://img.shields.io/github/release/websharks/cli-tools.svg?label=latest)](https://github.com/websharks/cli-tools/releases)
[![](https://img.shields.io/github/issues/websharks/cli-tools.svg?label=issues)](https://github.com/websharks/cli-tools/issues)
[![](https://img.shields.io/github/forks/websharks/cli-tools.svg?label=forks)](https://github.com/websharks/cli-tools/network)
[![](https://img.shields.io/github/stars/websharks/cli-tools.svg?label=stars)](https://github.com/websharks/cli-tools/stargazers)
[![](https://img.shields.io/github/downloads/websharks/cli-tools/latest/total.svg?label=downloads)](https://github.com/websharks/cli-tools/releases)

---

### Installation

```bash
$ brew tap websharks/formulas
$ brew install websharks-cli-tools
```

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

#### Clone the `cli-tools` repo locally.

```bash
$ git clone https://github.com/websharks/cli-tools
$ cd cli-tools
$ git checkout 000000-dev
```

#### Build and satisfy all dependencies.

```bash
$ cd cli-tools
$ phing # This runs Composer too, automatically.
```

#### Add a new `scripts/[file.(php|bash|etc)]`

##### If your script has dependencies, add them to `composer.json`.

_Or, you can use a Homebrew package as a dependency; if that is easier._

_If you need configuration data, please obtain those values from `~/.websharks.json`. Your script should trigger an exception if configuration options are missing; i.e., you should alert the user instead of trying to fail gracefully._

#### Submit a Pull Request on GitHub

Submit a Pull Request so that your script can get merged into this repo. Raam/Jason will review.

 - In your PR, please be sure to include a list of any Homebrew packages that your script requires. Those will need to be added to our [Homebrew Formula for these CLI Tools](https://github.com/websharks/homebrew-formulas/blob/master/websharks-cli-tools.rb).

 - If your script requires configuration options; i.e., data from `~/.websharks.json`, please include the config. keys needed to use the script effectively. We will need to update the README file so it mentions that new configuration option.
