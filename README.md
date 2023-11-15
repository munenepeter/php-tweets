# php-tweets
A simple application that gets a users tweets given a url and saves the post in a database & the tweet's media on disk

## Note

> You have to pay the X's API in order for the application to work

## Installation

Clone the project

```sh
git clone https://github.com/munenepeter/php-tweets.git
```

Install composer's requirements using [composer](https://getcomposer.org):

```sh
composer install
```
Then copy the `.env.example` to `.env` and edit the `.env` to match your credentials

```sh
cp .env.example .env
```

lastly run your development server

```sh
php -S localhost:8088
```
Finally visit `http://localhost:8088/?url=https://twitter.com/<any-username>` to see get and save the `<username>`'s tweets


## Known Issues
 1. I have not written tests for the application, will do so in the near future
 2. The whole application misbehaves when the `url` is not provided

 For other issues, bugs or feature suggestion feel to reach out to the dev
## License

MIT

