<?php
    class Database {
        // data base info is in config file because the data can be changed easily that way
        public function __construct() {
            $config = parse_ini_file('../config/config.ini', true);

            $type = $config['db']['type'];
            $host = $config['db']['host'];
            $name = $config['db']['name'];
            $user = $config['db']['user'];
            $password = $config['db']['password'];

            $this->init($type, $host, $name, $user, $password);
        }

        private function init($type, $host, $name, $user, $password) {
            try {
                $this->connection = new PDO("$type:host=$host;dbname=$name", $user, $password,
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            } catch(PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }

        public function get_connection()
        {
            return $this->connection;
        }

        // close the connection to the DB
        function __destruct() {
            $this->connection = null;
        }
    }
?>