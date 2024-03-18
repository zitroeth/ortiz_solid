<?php

namespace Model;
use LibraryDatabaseDatabaseAdapterInterface;
declare(strict_types=1);

class User implements UserInterface, GravatarInterface, UserCRUDInterface
{
    private int $id;
    private string $name;
    private string $email;
    private DatabaseAdapterInterface $db;
    private string $table = "users";

    public function __construct(DatabaseAdapterInterface $db) {
        $this->db = $db;
    }

    public function setId(int $id) : self {
        if ($this->id !== null) {
            throw new BadMethodCallException(
                "The user ID has been set already.");
        }
        if (!is_int($id) || $id < 1) {
            throw new InvalidArgumentException(
                "The user ID is invalid.");
        }
        $this->id = $id;
        return $this;
    }
    
    public function getId() : int {
        return $this->id;
    }
    
    public function setName(string $name) : self {
        if (strlen($name) < 2 || strlen($name) > 30) {
            throw new InvalidArgumentException(
                "The user name is invalid.");
        }
        $this->name = $name;
        return $this;
    }
    
    public function getName() : string {
        if ($this->name === null) {
            throw new UnexpectedValueException(
                "The user name has not been set.");
        }
        return $this->name;
    }

    public function setEmail(string $email) : self {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                "The user email is invalid.");
        }
        $this->email = $email;
        return $this;
    }
    
    public function getEmail() : string {
        if ($this->email === null) {
            throw new UnexpectedValueException(
                "The user email has not been set.");
        }
        return $this->email;
    }
    
    public function getGravatar(int $size = 70, string $default = "monsterid") : string {
        return "http://www.gravatar.com/avatar/" .
            md5(strtolower($this->getEmail())) .
            "?s=" . (integer) $size .
            "&d=" . urlencode($default) .
            "&r=G";
    }
    
    public function findById(int $id, User $user) : User {
        $this->db->select($this->table,
            ["id" => $id]);
        if (!$row = $this->db->fetch()) {
            return null;
        }
        $user->setId($row["id"])
             ->setName($row["name"])
             ->setEmail($row["email"]);
        return $user;
    }
    
    public function insert() : void {
        $this->db->insert($this->table, [
            "name"  => $this->getName(), 
            "email" => $this->getEmail()
        ]);
    }
    
    public function update() : void {
        $this->db->update($this->table, [
                "name"  => $this->getName(), 
                "email" => $this->getEmail()], 
            "id = {$this->id}");
    }

    public function delete() : void {
        $this->db->delete($this->table,
            "id = {$this->id}");
    }
}
