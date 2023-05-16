[![Build Status](https://github.com/landingi/bookkeeping-bundle/actions/workflows/ci.yaml/badge.svg)](https://github.com/landingi/bookkeeping-bundle/actions/workflows/ci.yaml)
[![Latest Stable Version](https://poser.pugx.org/landingi/bookkeeping-bundle/v)](https://packagist.org/packages/landingi/bookkeeping-bundle)
[![Total Downloads](https://poser.pugx.org/landingi/bookkeeping-bundle/downloads)](https://packagist.org/packages/landingi/bookkeeping-bundle)
[![License](https://poser.pugx.org/landingi/bookkeeping-bundle/license)](https://packagist.org/packages/landingi/bookkeeping-bundle)
[![codecov](https://codecov.io/gh/landingi/bookkeeping-bundle/branch/master/graph/badge.svg?token=DAN4LKMI3S)](https://codecov.io/gh/landingi/bookkeeping-bundle)
# bookkeeping-bundle

## How to use

```
composer require landingi/bookkeeping-bundle
```

To use the wFirma implementation, configure the `WfirmaClient` service, specifically provide the necessary credentials
for the `WfirmaCredentials` class as well as the API host URL (for example `https://api2.wfirma.pl`) for the `WfirmaApiUrl` class.

### v4+
Starting with version 4.0.0 we no longer use a login/password credential schema, and instead we switched to the following:

- accessKey
- secretKey
- appKey

See the wFirma API documentation below for more details.

## Development

### Additional

* [wFirma API documentation](https://doc.wfirma.pl)
* [Documentation](docs/README.md)
