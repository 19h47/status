# Status

A __WordPress__ plugin to turn your tweets into WordPress posts.

![Status](assets/screenshot.png)

## Description

__Status__ allow you to turn your tweets into a custom post type Tweet.

## Installation

### Dependencies

The plugin uses the most popular [PHP library for the Twitter OAuth REST API](https://github.com/abraham/twitteroauth).

```
composer require abraham/twitteroauth
```

### Config

Locate the `config-sample.php`, fill it with your personal informations and then saving it as `config.php`.

### Plugin

- Upload the folder `status` to the `/wp-content/plugins/` directory
- Activate the plugin through the __Plugins__ menu in WordPress

## References

- [Import Tweets as Posts](https://github.com/chandanonline4u/import-tweets-as-posts)
- [WordPress plugin boilerplate](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate)
- [Twitter Developers](https://developer.twitter.com/)
- [Twitter Application Management](https://apps.twitter.com/)
- [Insert attachment from url](https://gist.github.com/m1r0/f22d5237ee93bcccb0d9)