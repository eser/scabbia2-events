# Scabbia2 Events Component

[This component](https://github.com/eserozvataf/scabbia2-events) is a simple event dispatcher allows registering callbacks to some events and chain execution of them.

[![Build Status](https://travis-ci.org/eserozvataf/scabbia2-events.png?branch=master)](https://travis-ci.org/eserozvataf/scabbia2-events)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/eserozvataf/scabbia2-events/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/eserozvataf/scabbia2-events/?branch=master)
[![Total Downloads](https://poser.pugx.org/eserozvataf/scabbia2-events/downloads.png)](https://packagist.org/packages/eserozvataf/scabbia2-events)
[![Latest Stable Version](https://poser.pugx.org/eserozvataf/scabbia2-events/v/stable)](https://packagist.org/packages/eserozvataf/scabbia2-events)
[![Latest Unstable Version](https://poser.pugx.org/eserozvataf/scabbia2-events/v/unstable)](https://packagist.org/packages/eserozvataf/scabbia2-events)
[![Documentation Status](https://readthedocs.org/projects/scabbia2-documentation/badge/?version=latest)](https://readthedocs.org/projects/scabbia2-documentation)

## Usage

### Delegates

```php
use Scabbia\Events\Delegate;

$delegate = new Delegate();

$delegate->subscribe(function (...$parameters) {
    echo 'first subscriber:';
    var_dump($parameters);
});

$delegate->subscribe(function (...$parameters) {
    echo 'second subscriber:';
    echo count($parameters);
});

$delegate->invoke('a', 'b', 'c');
```

### Delegates with priorities

```php
use Scabbia\Events\Delegate;

$delegate = new Delegate();

// a subscription with priority = 300
$delegate->subscribe(function (...$parameters) {
    echo 'first subscriber:';
    var_dump($parameters);
}, null, 300);

// a subscription with priority = 1 (will be executed first)
$delegate->subscribe(function (...$parameters) {
    echo 'second subscriber, but more important:';
    echo count($parameters);
}, null, 1);

$delegate->invoke('a', 'b', 'c');
```

### Delegates with breaking

```php
use Scabbia\Events\Delegate;

$delegate = new Delegate();

$delegate->subscribe(function (...$parameters) {
    echo 'first subscriber:';
    var_dump($parameters);

    // breaks the execution
    return false;
});

$delegate->subscribe(function (...$parameters) {
    echo 'second subscriber, but not going to be executed:';
    echo count($parameters);
});

$delegate->invoke('a', 'b', 'c');
```

### Events
```php
use Scabbia\Events\Events;

$eventsManager = new Events();

$eventsManager->on('click', function (...$parameters) {
    echo "clicked on x={$parameters[0]} and y={$parameters[1]}!";
});

$eventsManager->on('double_click', function (...$parameters) {
    echo 'double clicked!';
});

$eventsManager->dispatch('click', 5, 10);
```

## Links
- [List of All Scabbia2 Components](https://github.com/eserozvataf/scabbia2)
- [Documentation](https://readthedocs.org/projects/scabbia2-documentation)
- [Twitter](https://twitter.com/eserozvataf)
- [Contributor List](contributors.md)
- [License Information](LICENSE)


## Contributing
It is publicly open for any contribution. Bugfixes, new features and extra modules are welcome. All contributions should be filed on the [eserozvataf/scabbia2-events](https://github.com/eserozvataf/scabbia2-events) repository.

* To contribute to code: Fork the repo, push your changes to your fork, and submit a pull request.
* To report a bug: If something does not work, please report it using GitHub issues.
* To support: [![Donate](https://img.shields.io/gratipay/eserozvataf.svg)](https://gratipay.com/eserozvataf/)
