# Remote

A simple way to copy files between different Laravel Storage disks

## Requirements

- PHP 5.6+
- Laravel 5.0+

## Installing

Use Composer to install it:

```
composer require filippo-toso/remote
```

## Using It

```
use FilippoToso\Remote\Remote;

// Copy from S3 to local
Remote::copy('s3://folder/file.dat', 'local:://another/folder/output.dot');

// Move from local to S3
Remote::move('local://folder/file.dat', 's3:://another/folder/output.dot');
```
