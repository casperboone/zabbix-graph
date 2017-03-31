# 📈 PHP Zabbix Graph

[![Latest Version on Packagist](https://img.shields.io/packagist/v/casperboone/zabbix-graph.svg?style=flat-square)](https://packagist.org/packages/casperboone/zabbix-graph)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![StyleCI](https://styleci.io/repos/86865582/shield)](https://styleci.io/repos/86865582)
[![Build Status](https://img.shields.io/travis/casperboone/zabbix-graph/master.svg?style=flat-square)](https://travis-ci.org/casperboone/zabbix-graph)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/xxxxxxxxx.svg?style=flat-square)](https://insight.sensiolabs.com/projects/xxxxxxxxx)
[![Quality Score](https://img.shields.io/scrutinizer/g/casperboone/zabbix-graph.svg?style=flat-square)](https://scrutinizer-ci.com/g/casperboone/zabbix-graph)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/casperboone/zabbix-graph/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/casperboone/pushover/?branch=master)

Get a graph from Zabbix to display on a webpage or save to a file. If you are using Laravel, then please check out [this repository](https://github.com/casperboone/laravel-zabbix-graph). 

## Installation
You can install the package via composer:

``` bash
composer require casperboone/zabbix-graph
```

Require Composer's autoload (probably already done):
```php
require __DIR__.'/../vendor/autoload.php';
```


## Usage
### Basic Usage
You can create an instance of `CasperBoone\ZabbixGraph` and pass the full URL to your Zabbix installation, the username and the password through the constructor. On this instance you can get a graph from Zabbix by calling `->find($graphId)`. Graph IDs can be found in the URL of the Zabbix UI of a certain graph.

Example:
```php
$zabbixGraph = new CasperBoone\ZabbixGraph('http://my-zabbix.com', 'username', 'passsword');

$zabbixGraph->width(500)
    ->height(300)
    ->find(54);
```

The output of find is a binary image that can be saved to a file or converted to an HTTP response.

### Available Methods
The following methods are available to adjust the parameters of the graph:

| Method                          | Description                                                |
| ------------------------------- | ---------------------------------------------------------- |
| `->width(int $width)`           | The width of the graph in pixels*                          |
| `->height(int $width)`          | The height of the graph in pixels*                         |
| `->startTime(DateTime $start)`  | The start date and time of the data displayed in the graph |
| `->endTime(DateTime $end)`      | The end date and time of the data displayed in the graph   |

_* The graph that Zabbix returns is usually slightly bigger because of added legends or labels_
### Old Zabbix versions
If you're using Zabbix 1.8 or older, then you need to set the last parameter of the constructor to `true`. 

Example:
```php
$zabbixGraph = new CasperBoone\ZabbixGraph('http://my-zabbix.com', 'username', 'passsword', true);
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```
//ADD ENV NOTE

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email mail@casperboone.nl instead of using the issue tracker.

## Credits

- [Casper Boone](https://github.com/casperboone)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
