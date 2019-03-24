<?php
/**
 * Copyright (c) 2018. Challstrom. All Rights Reserved.
 */

/**
 * Created by IntelliJ IDEA.
 * User: tchallst
 * Date: 20-Mar-18
 * Time: 04:09 PM
 */
/*
 * This EasyDatabase class should serve most all of your Database needs. You can of course implement additional functions to add
 * additional functionality
 */

class EasyDatabase
{
    /**
     * This var will contain a mysqli object the first time someone attempts to call a function
     * It lives until PHP is done running through all of its code, this is to save on the immense
     * processing time involved in establishing a database connection. (Imagine if you had to start
     * your car every time you wanted to accelerate)
     * @var mysqli
     */
    private static $database;
    /**
     * This var is the location of the *local* database config file relative to the current directory
     * Different create servers will have different database.json files, but they'll all be named database.json
     * This file will be automagically generated the first time this class is used
     * Make sure to edit this file and replace the default values with the credentials of the server the
     * config file is on
     * @var string
     */
    private static $databaseConfig = __DIR__ . "/database.json";

    /*
     * Basic Functions. You can use scrubQueryParam() and query() to do most everything you'll need to.
     */


    /**
     * @param string $queryParam
     * @return string
     * Removes SQL syntax from a parameter to be used in a query, see security topic on wiki
     * Don't use this on a fully formed query - just on a query parameter, as it will literally remove your query
     */
    public static function scrubQueryParam(string $queryParam): string
    {
        //real_escape_string will put escape characters (such as quotes) on and around SQL special characters and phrases
        return self::getDB()->real_escape_string($queryParam);
    }

    /**
     * @param string $query
     * @return array
     * Takes in a SQL query as a string, queries the database, returns the resulting rows as a single array or array of arrays
     */
    public static function query(string $query): array
    {
        //This line runs the query against the database
        $result = self::getDB()->query($query);
        //now we need to see if the query was successful. First, let's check if there were any outright errors
        if (self::getDB()->error) {
            //if the query failed completely, print out an error to the error_log file
            error_log("Query $query FAILED with " . self::getDB()->error);
        } else if ($result) {
            //if($result) checks to make sure $result actually exists, if it doesn't we got an error!
            //Now we check to see if a result was construct, else if a bool was returned
            //bool's are usually returned on INSERT, UPDATE, DELETE
            if ($result instanceof mysqli_result) {
                //We definitely got a result, now we need to check what kind of result it was, no rows, a single row, or many rows
                switch ($result->num_rows) {
                    case 0:
                        //no rows came back (like if the WHERE clause didn't find any results) so return an empty array
                        return [];
                        break;
                    case 1:
                        //we got a single row back, so just return that row as a single level associative array
                        return $result->fetch_assoc();
                    default:
                        //we got more than 1 row back, so we want to construct an array of arrays
                        $data = [];
                        while ($row = $result->fetch_assoc()) {
                            array_push($data, $row);
                        }
                        return $data;
                }
            } else {
                //We probably got a bool, so return an empty array
                return [];
            }
        }
        //If we got here, it means for some reason we couldn't get a result from query()
        error_log("Result could not be constructed in EasyDatabase.query() with query $query");
        return [];
    }

    /*
     * Internal functions. Don't modify these unless you know what you're doing!
     */

    /**
     * This function attempts to populate the $database variable with a mysqli object constructed using
     * the information provided by the config file $config
     */
    private static function databaseConnect()
    {
        //attempt to load the config information
        $config = self::loadDatabaseConfig();
        self::$database = new mysqli($config['hostname'], $config['username'], $config['password'], "", $config['port']);
        if (self::$database->connect_error) {
            error_log("Database Connection Failed with " . self::$database->connect_error);
        }
        self::$database->query("USE " . $config['dbName']);
    }

    /**
     * This function makes sure $database hasn't been initialized yet (databaseConnect() hasn't been called and
     * no connection has been made yet)
     * If if hasn't, it makes a connection, otherwise it returns the mysqli object *reference* (that's what the & is for)
     * @return mysqli
     */
    public static function &getDB(): mysqli
    {

        if (!self::$database) {
            self::databaseConnect();
        }
        //allows explicit casting to mysqli
        return self::$database;
    }

    /**
     * This function checks to see if $config exists
     * If it does it returns an associative array with the configuration information
     * If it doesn't it creates a default config file and returns an empty array (which will throw errors, this
     * is the intended behaviour)
     * @return array
     */
    private static function loadDatabaseConfig(): array
    {
        $databaseConfig = self::$databaseConfig;
        if (file_exists($databaseConfig)) {
            $config = json_decode(file_get_contents($databaseConfig), true);
            if (!isset($config['hostname'])) {
                error_log("hostname not set in $databaseConfig!");
            }
            if (!isset($config['username'])) {
                error_log("username not set in $databaseConfig!");
            }
            if (!isset($config['password'])) {
                error_log("password not set in $databaseConfig!");
            }
            if (!isset($config['port'])) {
                error_log("port not set in $databaseConfig!");
            }
            if (!isset($config['dbName'])) {
                error_log("dbName not set in $databaseConfig!");
            }
            return $config;
        } else {
            file_put_contents($databaseConfig, '{
  "hostname": "localhost",
  "username": "user",
  "password": "password",
  "port": "3306",
  "dbName": "database"
}');
            error_log("Database config file not found! A default config file has been created at $databaseConfig!");
        }
        return [];
    }
}
