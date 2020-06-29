# What is this?

It is a simple PHP application which runs with Docker from scratch.

## Requirements

* Git client
* Docker

## How to install the project?

1 - Clone the project.

```git clone https://github.com/onurdegerli/quiz.git```

2 - Change directory to your project home.

```cd ~/quiz```

3 - Under the `app` folder, copy `.env.example` as `.env` and update the configurations for your local environment.

4 - Under the `app` folder, copy `phinx.yml.example` as `phinx.yml` and update the configurations for your local environment.

5 - In your project folder, run the command below:

```bash app.sh start```

6 - Run the composer.

```cd app```

```composer update```

7 - Open the php container and run migrates/seeds.

```bash app.sh php```

```vendor/bin/phinx migrate -e development```

```vendor/bin/phinx seed:run```

## How to stop the project?

```bash app.sh stop```

## How to open the project in browser?

1 - Web: `http://127.0.0.1/`

2 - phpMyAdmin: `http://127.0.0.1:8184`

## Troubleshooting

- If you would like to install DB directly, please import MySQL dump file which is `quiz.sql` under the project folder.

- If you need to rollback all migrations, please run the command below in `quiz_php` container.

```vendor/bin/phinx rollback -t 0```

- If you encounter with any dependency problem, please run the command below in `quiz_php` container.

```rm -rf vendor```

```composer update```

- If you still encounter with any problem, feel free to contact with me.

```onurdegerli@gmail.com```