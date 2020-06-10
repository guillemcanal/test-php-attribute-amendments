# Testing PHP RFC: Attribute Amendments

[This RFC](https://wiki.php.net/rfc/attribute_amendments) discusses a few amendments to the original Attributes RFC that was accepted for PHP 8. 

## Usage

1. Build the Docker image

    ```shell
    docker build --build-arg REPO=koolkode --build-arg BRANCH=AmendmentsRFC -t test/php:koolkode-amendments-rfc .
    ```
2. Execute the test script
    ```shell
    alias php="docker run --rm -it -v $HOME:$HOME -w $(pwd) test/php:koolkode-amendments-rfc php"
    php test-php-attribute-amendments.php
    unalias php
    ```
