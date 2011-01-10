## Introduction
**li3_ruckusing_migration** is a [Lithium][0] command plugin acts as a wrapper for ruckusing-migrations by making the commands available as part of the li3 command set.

## Installation/Configuration
1. Clone the plugin to your app's libraries directory:

        /path/to/li3_app/libraries$ git clone https://github.com/nashape/li3_ruckusing_migrations.git

2. Clone [nashape/ruckusing-migration][1] (The modified fork) to the same directory:

        /path/to/li3_app/libraries$ git clone https://github.com/nashape/ruckusing-migrations.git

3. Add the plugin in your ``libraries.php (/path/to/li3_app/config/bootstrap/libraries.php)``:

        Libraries::add('li3_migration', array('path' => LITHIUM_APP_PATH . '/libraries/li3_ruckusing_migrations'));

4. Create ``db`` and ``migrate`` directories:

        /path/to/li3_app/config$ mkdir db && cd db && mkdir migrate

5. Specify your default db connection by changing the value of DEFAULT_ENV in ``/path/to/li3_app/libraries/li3_ruckusing_migrations/config/bootstrap.php``.

##Usage
**Note** refer to the [original ruckusing-migrations' documentation][2] for detailed usage instructions.

In order to be able to use the plugin, you need to have ``/path/to/lithium/libraries/lithium/console`` in your PATH.

* Generate a new migration file (migration files are stored in li3_app/db/migrate/):

        /path/to/li3_app$ li3 migration generate create_users_table

* Edit the resulting file, refer to the documentation linked in the note above.

* Run the migration:

        /path/to/li3_app$ li3 migration db:migrate

  The above will run using the connection set by DEFAULT_ENV. To run the migration using a different app connection, specify ``ENV``:

        /path/to/li3_app$ li3 migration db:migrate ENV=your-connection

[0]:http://lithify.me/
[1]:https://github.com/nashape/ruckusing-migrations
[2]:https://github.com/ruckus/ruckusing-migrations/wiki/_pages
