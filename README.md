# Collegium Nostrum

The alumni database for Eötvös Collegium.

## How to start contributing

Maybe I have finally found a way that works:D at least under **Ubuntu 20.04**.

- Clone the repo and follow the usual Laravel installation instructions (I will detail these here later). Don't bother with the database; that will be solved by Sail.
- Install Docker with the help of [these instructions](https://docs.docker.com/engine/install/ubuntu/).
- Add your user to the `docker` group: `sudo groupadd docker && sudo usermod -aG docker $USER`. This will ensure you can manage Docker without `sudo` later. _(Note: this might be a bit unsafe. But this is the way it worked for me.)_
- Update `APP_URL` to `http://localhost:8080`.
- Add these lines to `.env`:
```
APP_PORT=8080
FORWARD_DB_PORT=33066
```
- Run `./vendor/bin/sail up`.
- Open another terminal and run `./vendor/bin/sail artisan migrate:fresh --seed`. (Other Artisan commands need to be executed similarly.)
- Now you can test the site at `http://localhost:8080`.
