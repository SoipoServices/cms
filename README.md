# This package is a very simple cms package, it contains Pages, Posts and Metatags

[![Latest Version on Packagist](https://img.shields.io/packagist/v/soiposervices/cms.svg?style=flat-square)](https://packagist.org/packages/soiposervices/cms)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/soiposervices/cms/run-tests?label=tests)](https://github.com/soiposervices/cms/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/soiposervices/cms/Check%20&%20fix%20styling?label=code%20style)](https://github.com/soiposervices/cms/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/soiposervices/cms.svg?style=flat-square)](https://packagist.org/packages/soiposervices/cms)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/cms.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/cms)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require soiposervices/cms
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="cms-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="cms-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="cms-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$cms = new SoipoServices\Cms();
echo $cms->echoPhrase('Hello, SoipoServices!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Luigi Laezza](https://github.com/SoipoServices)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

If you enjoy this, please consider supporting SoipoServices:

<a href="https://www.buymeacoffee.com/SoipoServices" target="_blank"><img src="https://cdn.buymeacoffee.com/buttons/v2/default-yellow.png" alt="Buy Me A Coffee" width="217" height="60" /></a>
