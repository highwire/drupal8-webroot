# Theme Compiler
The theme compiler is a generic cli command runner for custom themes and is intended to be run as part of a composer
hook.

To use, follow these steps;

-  Add add your theme path(s) to the composer config
```json
    "config": {
        "..."
        "theme-directories": ["web/themes/highwire/site_theme"]
    },
```
- Add command to composer command hooks
```json
    "scripts": {
        "post-install-cmd": [
            "..."
            "FreebirdComposer\\Theme\\Compiler::execute"
        ],
        "post-update-cmd": [
            "..."
            "FreebirdComposer\\Theme\\Compiler::execute"
        ]
    }
```
- Copy the `example.compile.yml` file to your theme root directory as `compile.yml`
```bash
cp vendor/highwire/freebird-scaffold/src/Theme/example.compile.yml web/themes/highwire/site_theme
``` 

- Edit `compile.yml` to contain the commands required to compile your theme.