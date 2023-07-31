<?php

class Student extends User {
    private $id;
    private $name;
    private $age;
    private $class;
    private $username;
    private $password;

    public function __construct($name, $age, $class, $username, $password) {
        parent::__construct($name, $age, $class, $username, $password);
    }
}

?>
