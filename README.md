# Status

A __WordPress__ plugin to turn your tweets into WordPress posts.

![Status](assets/screenshot.png)

## Installation

The plugin uses the most popular [PHP library for the Twitter OAuth REST API](https://github.com/abraham/twitteroauth).

```
composer require abraham/twitteroauth
```
Locate the `config-sample.php`, fill it with your personal informations and then saving it as `config.php`.

1. Upload the folder `status` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

## References

- [Import Tweets as Posts](https://github.com/chandanonline4u/import-tweets-as-posts)