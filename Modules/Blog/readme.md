# Blog module

[![Latest Version](https://img.shields.io/github/release/asgardcms/Blog.svg?style=flat-square)](https://github.com/asgardcms/Blog/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/AsgardCms/Blog/master.svg?style=flat-square)](https://travis-ci.org/AsgardCms/Blog)
[![Quality Score](https://img.shields.io/scrutinizer/g/asgardcms/Blog.svg?style=flat-square)](https://scrutinizer-ci.com/g/asgardcms/Blog)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/c54f38d9-20fb-41e8-b2be-bdeb7144ad4b.svg)](https://insight.sensiolabs.com/projects/c54f38d9-20fb-41e8-b2be-bdeb7144ad4b)
[![Total Downloads](https://img.shields.io/packagist/dt/asgardcms/Blog-module.svg?style=flat-square)](https://packagist.org/packages/asgardcms/Blog-module)
[![Slack](http://slack.asgardcms.com/badge.svg)](http://slack.asgardcms.com/)


## Installation

### Module Download

Using AsgardCMS's module download command:

``` bash
php artisan asgard:download:module asgardcms/Blog --migrations
```

This will download the module and run its migrations .

This is the recommended way if you wish to customise the fields, views, etc.

### Composer
Execute the following command in your terminal

    composer require asgardcms/Blog-module

This is if the contact module is perfect for your use-case as-is, and doesn't need any changes to fit your needs.


**Note: After installation you'll have to give you the required permissions to get to the Blog module pages in the backend.**


## Usage

- You have to create a `Blog.index` and `Blog.show` page in your front end theme.
- You can link to the Blog index page using : `route(locale() . '.Blog')`
- In the Blog index you'll have access to a `$posts` variable on which you can loop
- To create a link to a specific post: `route(locale() . '.blog.slug', [$post->slug])`
- On the Blog index and Blog show pages you'll have access to a `$latestPosts` variable containing the latest posts, this amount can be configured in the admin.
- On a post detail page, you can have access to the next and previous post by calling:
    - `$post->present()->previous`
    - `$post->present()->next`


## Resources

- [Contribute to AsgardCMS](CONTRIBUTING.md)
- [License](LICENSE.md)


## Info

All AsgardCMS modules respect [Semantic Versioning](http://semver.org/).
"# Blog" 
