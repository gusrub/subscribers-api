# Subscribers API

This is just a simple API app for subscribers of a simulated email list that handles subscriber and fields resources.

## Configuration

The app uses `dotenv` so you can just rename the included `env.example` to `.env.development` and `.env.test` for local and test environments respectively. Change the settings in said files if your configuration is different.

You also need to have `composer` package manager installed and install the app dependencies with:

```bash
composer install
```

To create the database schemas there are two scripts included:

 - `schema.sql` for development and
 - `schema.test.sql` for test

They are essentially the same thing it just will create a different schema name so you don't mess with your dev data. There is also a MySql Workbench diagram included with all the definitions.

## Running locally

Just run the included script, pass the port parameter optionally:

```bash
./serve.sh -p 8080
```

## Testing

Execute the included script:

```bash
./test.sh
```

There is also an included _Postman_ collection that you can load up with all the requests. Its online documentation is also available [here](https://documenter.getpostman.com/view/92245/SzKWswcD).

## Generating docs

Execute the included script:

```bash
./docs.sh
```

That's all for now!