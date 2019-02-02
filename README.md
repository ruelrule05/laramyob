# Laramyob - MYOB in Laravel, made easy.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/creativecurtis/laramyob.svg?style=flat-square)](https://packagist.org/packages/creativecurtis/laramyob)
[![Build Status](https://img.shields.io/travis/creativecurtis/laramyob/master.svg?style=flat-square)](https://travis-ci.org/creativecurtis/laramyob)
[![Quality Score](https://img.shields.io/scrutinizer/g/creativecurtis/laramyob.svg?style=flat-square)](https://scrutinizer-ci.com/g/creativecurtis/laramyob)
[![Total Downloads](https://img.shields.io/packagist/dt/creativecurtis/laramyob.svg?style=flat-square)](https://packagist.org/packages/creativecurtis/laramyob)

A handy Laravel wrapper around MYOB AccountRight v2. This is still in alpha stage and will include breaking changes regularily. Full Readme in progress.

## Installation

You can install the package via composer:

```bash
composer require creativecurtis/laramyob
```

## Usage
ENV requirements:

```
MYOB_CLIENT_ID=
MYOB_CLIENT_SECRET=
MYOB_REDIRECT_URI=myob/login
MYOB_GRANT_TYPE=authorization_code
MYOB_SCOPE=CompanyFile
```

``` php
$laramyob = new Creativecurtis\Laramyob\Laramyob;
echo $laramyob->authenticate()->getCode();
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email lukesimoncurtis@gmail.com instead of using the issue tracker.

## Credits

- [Luke Curtis](https://github.com/lukecurtis93)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).