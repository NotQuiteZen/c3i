<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
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
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Security;
use Composer\IO\IOInterface;
use Composer\Script\Event;
use Exception;

/**
 * Provides installation hooks for when this application is installed via
 * composer. Customize this class to suit your needs.
 */
class Installer {

    /**
     * An array of directories to be made writable
     */
    const WRITABLE_DIRS = [
        'logs',
        'tmp',
        'tmp/cache',
        'tmp/cache/models',
        'tmp/cache/persistent',
        'tmp/cache/views',
        'tmp/sessions',
        'tmp/tests',
    ];

    /**
     * Does some routine installation tasks so people don't have to.
     *
     * @param \Composer\Script\Event $event The composer event object.
     *
     * @throws \Exception Exception raised by validator.
     * @return void
     */
    public static function postInstall(Event $event) {
        $io = $event->getIO();

        $rootDir = dirname(dirname(__DIR__));

        # Output c3iLogo
        static::c3iLogo($io);

        # Create Config
        static::createAppConfig($rootDir, $io);

        # Create writable dirs
        static::createWritableDirectories($rootDir, $io);

        # Ask if the permissions should be changed
        if ($io->isInteractive()) {


            $yesNoValidator = function($arg) {
                if (in_array($arg, ['Y', 'y', 'N', 'n'])) {
                    return $arg;
                }
                throw new Exception('This is not a valid answer. Please choose Y, y, N or n.');
            };

            $setFolderPermissions = $io->askAndValidate(
                '<info>Set Folder Permissions ? (Default to Y)</info> [<comment>Y,n</comment>]? ',
                $yesNoValidator,
                10,
                'Y'
            );

            if (in_array($setFolderPermissions, ['Y', 'y'])) {
                static::setFolderPermissions($rootDir, $io);
            }

            $startDatabaseSequence = $io->askAndValidate(
                '<info>Do you want to setup a database in your config? (Default to N)</info> [<comment>N,y</comment>]? ',
                $yesNoValidator,
                10,
                'Y'
            );

            if (in_array($startDatabaseSequence, ['Y', 'y'])) {
                static::startDatabaseSequence($rootDir, $io);
            }
        } else {
            # Set folder permissing
            static::setFolderPermissions($rootDir, $io);
        }

        # Set Security Salt
        static::setSecuritySalt($rootDir, $io);

        if (class_exists('\Cake\Codeception\Console\Installer')) {
            \Cake\Codeception\Console\Installer::customizeCodeceptionBinary($event);
        }
    }

    /**
     * Create the config/app.php file if it does not exist.
     *
     * @param string                   $dir The application's root directory.
     * @param \Composer\IO\IOInterface $io  IO interface to write to console.
     *
     * @return void
     */
    public static function createAppConfig($dir, $io) {
        $appConfig = $dir . '/config/app.php';
        $defaultConfig = $dir . '/config/app.default.php';
        if ( ! file_exists($appConfig)) {
            copy($defaultConfig, $appConfig);
            $io->write('Created `config/app.php` file');
        }
    }

    /**
     * Create the `logs` and `tmp` directories.
     *
     * @param string                   $dir The application's root directory.
     * @param \Composer\IO\IOInterface $io  IO interface to write to console.
     *
     * @return void
     */
    public static function createWritableDirectories($dir, $io) {
        foreach (static::WRITABLE_DIRS as $path) {
            $path = $dir . '/' . $path;
            if ( ! file_exists($path)) {
                mkdir($path);
                $io->write('Created `' . $path . '` directory');
            }
        }
    }

    /**
     * Set globally writable permissions on the "tmp" and "logs" directory.
     *
     * This is not the most secure default, but it gets people up and running quickly.
     *
     * @param string                   $dir The application's root directory.
     * @param \Composer\IO\IOInterface $io  IO interface to write to console.
     *
     * @return void
     */
    public static function setFolderPermissions($dir, $io) {
        // Change the permissions on a path and output the results.
        $changePerms = function($path, $perms, $io) {
            // Get permission bits from stat(2) result.
            $currentPerms = fileperms($path)&0777;
            if (($currentPerms&$perms) == $perms) {
                return;
            }

            $res = chmod($path, $currentPerms|$perms);
            if ($res) {
                $io->write('Permissions set on ' . $path);
            } else {
                $io->write('Failed to set permissions on ' . $path);
            }
        };

        $walker = function($dir, $perms, $io) use (&$walker, $changePerms) {
            $files = array_diff(scandir($dir), ['.', '..']);
            foreach ($files as $file) {
                $path = $dir . '/' . $file;

                if ( ! is_dir($path)) {
                    continue;
                }

                $changePerms($path, $perms, $io);
                $walker($path, $perms, $io);
            }
        };

        $worldWritable = bindec('0000000111');
        $walker($dir . '/tmp', $worldWritable, $io);
        $changePerms($dir . '/tmp', $worldWritable, $io);
        $changePerms($dir . '/logs', $worldWritable, $io);
    }

    /**
     * Set the security.salt value in the application's config file.
     *
     * @param string                   $dir The application's root directory.
     * @param \Composer\IO\IOInterface $io  IO interface to write to console.
     *
     * @return void
     */
    public static function setSecuritySalt($dir, $io) {
        $newKey = hash('sha256', Security::randomBytes(64));
        static::setSecuritySaltInFile($dir, $io, $newKey, 'app.php');
    }

    /**
     * Set the security.salt value in a given file
     *
     * @param string                   $dir    The application's root directory.
     * @param \Composer\IO\IOInterface $io     IO interface to write to console.
     * @param string                   $newKey key to set in the file
     * @param string                   $file   A path to a file relative to the application's root
     *
     * @return void
     */
    public static function setSecuritySaltInFile($dir, $io, $newKey, $file) {
        $config = $dir . '/config/' . $file;
        $content = file_get_contents($config);

        $content = str_replace('__SALT__', $newKey, $content, $count);

        if ($count == 0) {
            $io->write('No Security.salt placeholder to replace.');

            return;
        }

        $result = file_put_contents($config, $content);
        if ($result) {
            $io->write('Updated Security.salt value in config/' . $file);

            return;
        }
        $io->write('Unable to update Security.salt value.');
    }

    /**
     * Set the APP_NAME value in a given file
     *
     * @param string                   $dir     The application's root directory.
     * @param \Composer\IO\IOInterface $io      IO interface to write to console.
     * @param string                   $appName app name to set in the file
     * @param string                   $file    A path to a file relative to the application's root
     *
     * @return void
     */
    public static function setAppNameInFile($dir, $io, $appName, $file) {
        $config = $dir . '/config/' . $file;
        $content = file_get_contents($config);
        $content = str_replace('__APP_NAME__', $appName, $content, $count);

        if ($count == 0) {
            $io->write('No __APP_NAME__ placeholder to replace.');

            return;
        }

        $result = file_put_contents($config, $content);
        if ($result) {
            $io->write('Updated __APP_NAME__ value in config/' . $file);

            return;
        }
        $io->write('Unable to update __APP_NAME__ value.');
    }


    /**
     * @param $io IOInterface
     */
    public static function c3iLogo($io) {
        $io->write('');
        $io->write('<info> ██████╗██████╗ ██╗    ███████╗███████╗████████╗██╗   ██╗██████╗</info>');
        $io->write('<info>██╔════╝╚════██╗██║    ██╔════╝██╔════╝╚══██╔══╝██║   ██║██╔══██╗</info>');
        $io->write('<info>██║      █████╔╝██║    ███████╗█████╗     ██║   ██║   ██║██████╔╝</info>');
        $io->write('<info>██║      ╚═══██╗██║    ╚════██║██╔══╝     ██║   ██║   ██║██╔═══╝</info>');
        $io->write('<info>╚██████╗██████╔╝██║    ███████║███████╗   ██║   ╚██████╔╝██║</info>');
        $io->write('<info> ╚═════╝╚═════╝ ╚═╝    ╚══════╝╚══════╝   ╚═╝    ╚═════╝ ╚═╝</info>');
        $io->write('');
    }


    /**
     * @param $dir
     * @param $io IOInterface
     *
     * @throws Exception
     */
    public static function startDatabaseSequence($dir, $io) {

        #
        $appConfigFile = $dir . '/config/app.php';

        $configContents = file_get_contents($appConfigFile);

        if (strpos($configContents, '__DATABASE_USER__') === false) {
            $io->write('No database tags found in ' . $appConfigFile . ', skipping database setup');

        } else {


            # Not empty validation function
            $notEmptyValidator = function($arg) {
                if ( ! empty($arg)) {
                    return $arg;
                }
                throw new Exception('Invalid input received, please insert a non-empty string');
            };

            # Get the database user
            $databaseUser = $io->askAndValidate(
                '<info>Database username</info>: ',
                $notEmptyValidator,
                10
            );

            # Get the database password
            $databasePassword = $io->askAndValidate(
                '<info>Database password <comment>(will not be hidden!)</comment></info>: ',
                $notEmptyValidator,
                10
            );

            # Get the database name
            $databaseName = $io->askAndValidate(
                '<info>Database name</info>: ',
                $notEmptyValidator,
                10
            );

            #
            $yesNoValidator = function($arg) {
                if (in_array($arg, ['Y', 'y', 'N', 'n'])) {
                    return $arg;
                }
                throw new Exception('This is not a valid answer. Please choose Y, y, N or n.');
            };

            # Ask if we're happy with this
            $happyWithDatabaseInput = $io->askAndValidate(
                '<info>Are you happy with your input for the database configuration? (Default to Y)</info> [<comment>Y,n</comment>]?',
                $yesNoValidator,
                10,
                'Y'
            );

            # Restart if needed
            if (in_array($happyWithDatabaseInput, ['N', 'n'])) {

                # Ask if we want to stop setting up a database
                $cancelDatabaseSequence = $io->askAndValidate(
                    "<info>Do you want to cancel setting up a database? (N = Re-input database credentials, default to N)</info> [<comment>y,N</comment>]?",
                    $yesNoValidator,
                    10,
                    'N'
                );

                # Do we not wanna setup a database?
                if (in_array($cancelDatabaseSequence, ['Y', 'y'])) {
                    return;
                }

                $io->write('<info>Please input your database credentials</info>');

                # Start the database sequence again
                static::startDatabaseSequence($dir, $io);

                return;
            }

            # Replace the user, password and name in the app.php
            static::_replaceTagInFile($io, '__DATABASE_USER__', $databaseUser, $appConfigFile);
            static::_replaceTagInFile($io, '__DATABASE_PASSWORD__', $databasePassword, $appConfigFile);
            static::_replaceTagInFile($io, '__DATABASE_NAME__', $databaseName, $appConfigFile);

        }

        # Start database sessions sequence

        static::databaseSessions($dir, $io);
    }

    /**
     * @param $dir
     * @param $io IOInterface
     *
     * @throws Exception
     */
    public static function databaseSessions($dir, $io) {

        $yesNoValidator = function($arg) {
            if (in_array($arg, ['Y', 'y', 'N', 'n'])) {
                return $arg;
            }
            throw new Exception('This is not a valid answer. Please choose Y, y, N or n.');
        };

        $io->write('');

        # Ask if we want to use database sessions
        $doWeWantDatabaseSessions = $io->askAndValidate(
            "<info>Do you want to setup database sessions? (Default to Y)</info> [<comment>Y,n</comment>]?",
            $yesNoValidator,
            10,
            'Y'
        );

        # If we dont want database sessions, just return, no hard feelings
        if (in_array($doWeWantDatabaseSessions, ['N', 'n'])) {
            return;
        }

        $appConfigFile = $dir . '/config/app.php';

        $configFile = file_get_contents($appConfigFile);

        # TODO: Make this a better check, there must be a way
        # TODO: Idea: Cake fetches the app.php as an array I think (seeing as the first line is a return statement)
        # TODO: So maybe we can too and check it that way

        if (strpos($configFile, "'defaults' => 'php',") === false) {

            $io->write('Seems like you\'re not using php sessions, skipping database session setup');

            return;
        }

        # TODO: Figure out of this is the proper way to do this
        # TODO: Because it feels a bit weird to boot up Cake here
        # TODO: We need it now though, because we're going to execute an sql file using the (newly) set database config in app.php

        # Bootstrap Cake
        /** @noinspection PhpIncludeInspection */
        require $dir . '/vendor/autoload.php';
        /** @noinspection PhpIncludeInspection */
        require $dir . '/config/bootstrap.php';

        # Get the sessions query
        $sessionTableQuery = file_get_contents($dir . '/config/schema/sessions.sql');


        # TODO: Add check if the table already exists
        try {

            # Get the db connection
            $conn = ConnectionManager::get('default');

            # Execute the query
            $conn->execute($sessionTableQuery);
        } catch (Exception $e) {
            $io->write('<error>Something went wrong creationg the sessions table, perhaps the table already exists?</error>');

            return;
        }

        # Set session config to 'database' instead of php
        static::_replaceTagInFile($io, "'defaults' => 'php',", "'defaults' => 'database',", $appConfigFile);

        # If all went well, and get here, we have no errors
        $io->write('<info>Succesfully setup database sessions</info>');
    }

    /**
     * @param $io IOInterface
     * @param $tag
     * @param $replaceWith
     * @param $file
     */
    private static function _replaceTagInFile($io, $tag, $replaceWith, $file) {
        $content = file_get_contents($file);
        $content = str_replace($tag, $replaceWith, $content, $count);

        if ($count == 0) {
            $io->write('No ' . $tag . ' placeholder to replace.');

            return;
        }

        $result = file_put_contents($file, $content);
        if ($result) {
            $io->write('Updated ' . $tag . ' value in ' . $file);

            return;
        }
        $io->write('Unable to update ' . $tag . ' value.');
    }
}
