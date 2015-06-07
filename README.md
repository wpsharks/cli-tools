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

Please create this config. file and fill in your username and API keys.

_**Note:** Not all team members will have access to all of these credentials. For instance, a `websharks->api_key` is available to Jason/Raam only, and is required for access to special sub-commands (e.g., `$ ws sales`). Please fill in what you do have, and don't worry too much. If you forget something vital the CLI Tools will let you know that a specific config. option is required to complete the requested action—this may happen whenever you try to run a particular sub-command._

```json
{
    "config": {
        "user": {
            "name": "jaswsinc",
            "projects_dir": "~/projects"
        },
        "github": {
            "username": "jaswsinc",
            "api_key": "xxxxxxxxxxxxxxxxxxxxxx"
        },
        "slack": {
            "username": "jaswsinc",
            "api_key": "xxxxxxxxxxxxxxxxxxxxxx"
        },
        "idonethis": {
            "username": "jaswsinc",
            "api_key": "xxxxxxxxxxxxxxxxxxxxxx"
        },
        "websharks": {
          "username": "jaswsinc",
          "api_key": "xxxxxxxxxxxxxxxxxxxxxx"
        }
    }
}
```

---

## Getting Started w/ CLI Tools

Type the following for a list of all available sub-commands:

```shell
$ ws --help
```

![2015-06-06_21-51-04](https://cloud.githubusercontent.com/assets/1563559/8022785/2993e470-0c96-11e5-89d0-67c8834f07a7.png)

---

## Getting Help For A Specific Sub-Command

```shell
$ ws [sub-command] --help
```

Example: `$ ws done --help` will give you instructions for the `done` sub-command.

![2015-06-06_21-52-14](https://cloud.githubusercontent.com/assets/1563559/8022792/530cc222-0c96-11e5-84df-f6dd878d65ec.png)

---

## Staying Up-To-Date

```shell
$ brew update --all && brew upgrade --all;
```

_This will update all of your Homebrew formulas; including the `websharks-cli-tools`._
