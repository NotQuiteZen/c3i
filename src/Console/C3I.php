<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Console;

if ( ! defined('STDIN')) {
    define('STDIN', fopen('php://stdin', 'r'));
}

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Composer\IO\IOInterface;
use Composer\Script\Event;
use Exception;

class C3I {

    /**
     * Does some routine installation tasks so people don't have to.
     *
     * @param \Composer\Script\Event $event The composer event object.
     *
     * @throws \Exception Exception raised by validator.
     * @return mixed
     */
    public static function setupDatabase(Event $event) {

        $io = $event->getIO();

        # No interactive run? stop
        if ( ! $io->isInteractive()) {
            return static::_returnSleep();
        }

        # Get the root directory
        $rootDir = dirname(dirname(__DIR__));

        #
        $appConfigFile = $rootDir . '/config/app.php';

        $configContents = file_get_contents($appConfigFile);

        if (strpos($configContents, '\'Datasources\' => [],') === false) {
            $io->write('<comment>Skipping database setup</comment>: No Datasources key found in ' . $appConfigFile);

            return static::_returnSleep();
        }

        # Ask if we want to setup a database
        $startDatabaseSequence = $io->askAndValidate(
            '<info>Do you want to setup a database in your config? (Default to N)</info> [<comment>y,N</comment>]? ',
            static::_yesNoValidator(),
            10,
            'N'
        );

        if ($startDatabaseSequence === 'n') {
            return static::_returnSleep();
        }

        # Get the database user
        $databaseUser = $io->askAndValidate(
            '<info>Database username</info>: ',
            static::_notEmptyValidator(),
            10
        );

        # Get the database password
        $databasePassword = $io->askAndValidate(
            '<info>Database password <comment>(will not be hidden!)</comment></info>: ',
            static::_notEmptyValidator(),
            10
        );

        # Get the database name
        $databaseName = $io->askAndValidate(
            '<info>Database name</info>: ',
            static::_notEmptyValidator(),
            10
        );

        #
        # Ask if we're happy with this
        $happyWithDatabaseInput = $io->askAndValidate(
            '<info>Are you happy with your input for the database configuration? (Default to Y)</info> [<comment>Y,n</comment>]?',
            static::_yesNoValidator(),
            10,
            'Y'
        );

        # Not happy, lets stop
        if (in_array($happyWithDatabaseInput, ['N', 'n'])) {
            $io->write('Cancelling database setup.');

            return static::_returnSleep();
        }

        # Write to app.php
        $template = "'Datasources' => [\n        'default' => [\n            'className' => 'Cake\Database\Connection',\n            'driver' => 'Cake\Database\Driver\Mysql',\n            'persistent' => false,\n            'host' => 'localhost',\n            'username' => '$databaseUser',\n            'password' => '$databasePassword',\n            'database' => '$databaseName',\n            'timezone' => 'UTC',\n            'flags' => [],\n            'cacheMetadata' => true,\n            'log' => false,\n            'quoteIdentifiers' => false,\n            'url' => env('DATABASE_URL', null),\n        ],\n    ],";
        static::_replaceTagInFile($io, '\'Datasources\' => [],', $template, $appConfigFile);

        return static::_returnSleep();
    }

    /**
     * @param Event $event
     *
     * @return bool|int
     * @throws Exception
     */
    public static function databaseSessions(Event $event) {

        $io = $event->getIO();

        $rootDir = dirname(dirname(__DIR__));

        # Bootstrap Cake
        /** @noinspection PhpIncludeInspection */
        require $rootDir . '/vendor/autoload.php';
        /** @noinspection PhpIncludeInspection */
        require $rootDir . '/config/bootstrap.php';

        $appConfigFile = $rootDir . '/config/app.php';
        $configContents = file_get_contents($appConfigFile);
        if (strpos($configContents, '\'Datasources\' => []') !== false) {
            $io->write('<comment>Skipping database sessions setup</comment>: No Datasources configured');

            return static::_returnSleep();
        }

        if (Configure::read('Session.defaults') !== 'php') {
            $io->write('<comment>Skipping database sessions</comment>: Seems like you\'re not using default php sessions');

            return static::_returnSleep();
        }

        # Ask if we want to use database sessions
        $doWeWantDatabaseSessions = $io->askAndValidate(
            "<info>Do you want to setup database sessions? (Default to N)</info> [<comment>y,N</comment>]?",
            static::_yesNoValidator(),
            10,
            'N'
        );

        # If we dont want database sessions, just return, no hard feelings
        if ($doWeWantDatabaseSessions === 'n') {
            return static::_returnSleep();
        }

        # Get the sessions query from the file cake provided us with
        $sessionTableQuery = file_get_contents($rootDir . '/config/schema/sessions.sql');

        try {

            # Get the db connection
            $conn = ConnectionManager::get('default');

            # Execute the query
            $conn->execute($sessionTableQuery);

        } catch (Exception $e) {
            $io->write('<error>Something went wrong creationg the sessions table, perhaps the table already exists?</error>');

            return static::_returnSleep();
        }

        # Set session config to 'database' instead of php
        static::_replaceTagInFile($io, "'defaults' => 'php',", "'defaults' => 'database',", $rootDir . '/config/app.php');

        # If all went well, and get here, we have no errors
        $io->write('<info>Succesfully setup database sessions</info>');

        return static::_returnSleep();
    }

    /**
     * @param Event $event
     *
     * @return bool|int
     */
    public static function logo(Event $event) {

        $io = $event->getIO();

        $io->write('');
        $io->write('<info> ██████╗██████╗ ██╗    ███████╗███████╗████████╗██╗   ██╗██████╗</info>');
        $io->write('<info>██╔════╝╚════██╗██║    ██╔════╝██╔════╝╚══██╔══╝██║   ██║██╔══██╗</info>');
        $io->write('<info>██║      █████╔╝██║    ███████╗█████╗     ██║   ██║   ██║██████╔╝</info>');
        $io->write('<info>██║      ╚═══██╗██║    ╚════██║██╔══╝     ██║   ██║   ██║██╔═══╝</info>');
        $io->write('<info>╚██████╗██████╔╝██║    ███████║███████╗   ██║   ╚██████╔╝██║</info>');
        $io->write('<info> ╚═════╝╚═════╝ ╚═╝    ╚══════╝╚══════╝   ╚═╝    ╚═════╝ ╚═╝</info>');
        $io->write('');

        return static::_returnSleep();
    }

    /**
     * @param Event $event
     */
    public static function notifyYarnTime(Event $event) {

        $io = $event->getIO();

        $io->write('');
        $io->write('Installing Node dependencies for our front-end and running a first-time build, this may take a while...');
        $io->write('');
    }

    /**
     * @param IOInterface $io
     * @param             $tag
     * @param             $replaceWith
     * @param             $file
     *
     * @return bool|int
     */
    private static function _replaceTagInFile(IOInterface $io, $tag, $replaceWith, $file) {
        $content = file_get_contents($file);
        $content = str_replace($tag, $replaceWith, $content, $count);

        if ($count == 0) {
            $io->write('No ' . $tag . ' placeholder to replace.');

            return static::_returnSleep();
        }

        $result = file_put_contents($file, $content);
        if ($result) {
            $io->write('Updated ' . $tag . ' value in ' . $file);

            return static::_returnSleep();
        }
        $io->write('Unable to update ' . $tag . ' value.');

        return true;
    }

    /**
     * @return \Closure
     */
    private static function _yesNoValidator() {
        return function($arg) {
            $arg = strtolower($arg);
            if (in_array($arg, ['y', 'n'])) {
                return $arg;
            }
            throw new Exception('This is not a valid answer. Please choose Y, y, N or n.');
        };
    }

    /**
     * @return \Closure
     */
    private static function _notEmptyValidator() {
        return function($arg) {
            if ( ! empty($arg)) {
                return $arg;
            }
            throw new Exception('Invalid input received, please insert a non-empty string');
        };
    }

    /**
     * @return int|bool
     */
    private static function _returnSleep() {
        return sleep(1);
    }
}
