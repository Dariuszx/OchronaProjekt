<?php

    class DataPreferences {

        const maxNicknameLength = 20;
        const maxPasswordLength = 20;

        const minNicknameLength = 5;
        const minPasswordLength = 5;

        const numberOfHash = 128;

        //Function counts entropy for input string
        protected function entropy($string) {

            $h=0;
            $size = strlen($string);

            foreach (count_chars($string, 1) as $v) {
                $p = $v/$size;
                $h -= $p*log($p)/log(2);
            }
            return $h;
        }

        //Function returns hash and salt from password
        protected function saltPassword($password) {

            $salt = openssl_random_pseudo_bytes(64);
            $salt = bin2hex($salt);

            $hash = $password.$salt;

            foreach(range(0,self::numberOfHash) as $i) {
                $hash = hash('sha512', $hash);
            }

            return array($hash, $salt);
        }

        //Function validate data from HTML form
        public function validateInputData(&$data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
        }

        protected function validateEmail(&$email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Wrong email format!");
            }
        }

        protected function validateNickname(&$nickname) {
            $regex  = '/[^a-zA-Z0-9_-]|admin|root|administrator/';

            if (preg_match($regex, $nickname)) {
                throw new Exception("Nickname format is wrong, retype your nickname again!");
            }
        }

        protected function hashPassword($form_password, $salt) {

            $hash = $form_password.$salt;

            foreach(range(0,self::numberOfHash) as $i) {
                $hash = hash('sha512', $hash);
            }

            return $hash;
        }
    }

    class User extends DataPreferences {

        protected $nickname = "";
        protected $password = "";
        protected $email = "";
        protected $hash;
        protected $salt;

        public function getNickname()
        {
            return $this->nickname;
        }

        public function setNickname($nickname)
        {
            $this->nickname = $nickname;
        }

        public function getPassword()
        {
            return $this->password;
        }

        public function setPassword($password)
        {
            $this->password = $password;
        }

        public function getEmail()
        {
            return $this->email;
        }

        public function setEmail($email)
        {
            $this->email = $email;
        }

        public function getHash()
        {
            return $this->hash;
        }

        public function setHash($hash)
        {
            $this->hash = $hash;
        }

        public function getSalt()
        {
            return $this->salt;
        }

        public function setSalt($salt)
        {
            $this->salt = $salt;
        }


    }

    class UserData extends User {

        //Function is preparing data to put it into database
        private function finalizeData() {

            if( strlen($this->nickname) > self::maxNicknameLength || strlen($this->nickname) < self::minNicknameLength) {
                throw new Exception("Wrong length of nickname input field!");
            }

            if( strlen($this->password) > self::maxPasswordLength || strlen($this->password) < self::minPasswordLength) {
                throw new Exception("Wrong length of password input field!");
            }

            $this->validateInputData($this->nickname);
            $this->validateInputData($this->email);
            $this->validateInputData($this->password);

            $this->validateEmail($this->email);
            $this->validateNickname($this->nickname);

            if($this->entropy($this->password) <= 2.5) throw new Exception("Your password is too easy!");

            list($this->hash, $this->salt) = $this->saltPassword($this->password);

            if( empty($this->nickname) or empty($this->password) or empty($this->email) ) {
                throw new Exception("You have to set every field of form!");
            }
        }

        public function getData() {

            $this->finalizeData();

            $array = array(
                "nickname" => $this->nickname,
                "email" => $this->email,
                "password" => $this->hash,
                "salt" => $this->salt
            );
            return $array;
        }

        public function finalizeLoginData() {

            if( empty($this->nickname) or empty($this->password) )
                throw new Exception("Wrong nickname or password!");

            $this->validateNickname($this->nickname);

            $this->hash = $this->hashPassword($this->password, $this->salt);
        }

    }

    class Database {

        private $db;

        public function getDb()
        {
            return $this->db;
        }

        public function connect() {
            $this->db = new mysqli('localhost', 'dybkad', 'poziom9', 'dybkad_baza');

            if (mysqli_connect_errno()) {
                throw new Exception("Failed to connect to MySQL: " . mysqli_connect_error());
            }
        }

        public function disconnect() {
            $this->db->close();
        }

        public function insertUser($array) {

            $this->connect();

            $nickname = $this->db->real_escape_string($array['nickname']);
            $email = $this->db->real_escape_string($array['email']);
            $password = $array['password'];
            $salt = $array['salt'];

            if( self::isUserExist($nickname) )
                throw new Exception("Type another nickname, this one is already in use!");

            $query = "INSERT INTO users (nickname, email, salt, password) VALUES ('$nickname', '$email', '$salt', '$password')";

            $result = $this->db->query($query);

            if( $result == false) {
                throw new Exception("Problem with putting user data to database!");
            }

            $query = "SELECT user_id FROM users WHERE nickname='$nickname'";
            $result = $this->db->query($query);

            if($result->num_rows == 0) throw new Exception("Problem with database!");

            $row = $result->fetch_assoc();
            $user_id = $row['user_id'];

            self::disconnect();

            return $user_id;
        }

        public function isUserExist($nickname) {
            $query = "SELECT 1 FROM users WHERE nickname='$nickname'";
            $result = $this->db->query($query);

            if( $result->num_rows == 0 ) {
                return false;
            }
            return true;
        }

        public function userAuthentication($nickname, $hash) {

            self::connect();
            $query = "SELECT user_id FROM users WHERE nickname='$nickname' and password='$hash'";
            $result = $this->db->query($query);

            if( $result->num_rows == 0) {
                self::disconnect();
                return -1;
            }

            $row = $result->fetch_assoc();
            $user_id = $row['user_id'];

            self::disconnect();
            return $user_id;
        }

        public function getSalt($nickname) {

            self::connect();

            $query = "SELECT salt FROM users WHERE nickname='$nickname'";
            $result = $this->db->query($query);

            if( $result->num_rows == 0 ) {
                self::disconnect();
                return openssl_random_pseudo_bytes(64);
            }

            $row = $result->fetch_assoc();
            $salt = $row['salt'];
            self::disconnect();

            return $salt;
        }

    }

    class UserAccount {

        const MAX_ATTEMPTS = 3;
        const BLOCK_TIME = 900;
        const delayTime = 1;

        private $mysql;
        private $ip;

        private $attempts;

        function __construct() {
            $this->mysql = new Database();
            $this->mysql->connect();
            $this->ip = dechex(crc32($_SERVER['REMOTE_ADDR']));
        }

        private function canLogIn() {

            $db = $this->mysql->getDb();
            $result = $db->query("SELECT * FROM login WHERE ip='$this->ip'");

            //Dodaje nowy rekord do bazy
            if($result->num_rows == 0) {
                $db->query("INSERT INTO login (ip, login_attempts,last_date) VALUES ('$this->ip',0,".time().")");
                $this->attempts = 0;
                return true;
            } else { //Jeżeli jest już rekord w bazie
                $row = $result->fetch_assoc();
                $this->attempts = $row['login_attempts'];

                if($row['login_attempts'] >= self::MAX_ATTEMPTS and time() - $row['last_date'] < self::BLOCK_TIME) {
                    return false;
                } else if(time() - $row['last_date'] >= self::BLOCK_TIME) {
                    $this->attempts = 0;
                    $db->query("DELETE FROM login WHERE ip='$this->ip'");
                    return true;
                }
                return true;
            }
        }

        private function addLoginAttempt() {

            $this->attempts++;
            $db = $this->mysql->getDb();

            $time = time();
            $db->query("UPDATE login SET login_attempts='$this->attempts', last_date='$time' WHERE ip='$this->ip'");
            $this->mysql->disconnect();
        }

        public function logIn($username, $password) {

            if( $this->canLogIn() ) {
                sleep(self::delayTime);
                $user_id = $this->mysql->userAuthentication($username, $password);
                $this->mysql->connect(); //Odnawiam zerwane połączenie

                if( $user_id == -1 ) { //Wrong username
                    $this->addLoginAttempt();
                    throw new Exception("Wrong username or password!");
                } else return $user_id;

            } else {
                throw new Exception("Your ip adress has been blocked due to many attempts to login.");
            }
        }

        function __destruct() {
           // $this->mysql->disconnect();
        }
    }
