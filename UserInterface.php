<?php
namespace Model;

declare(strict_types=1);

interface UserInterface
{
    public function setId(int $id) : self;
    public function getId() : int;

    public function setName(string $name) : self;
    public function getName() : string;

    public function setEmail(string $email) : self;
    public function getEmail() : string;
}

interface GravatarInterface
{
    public function getGravatar() : string;
}

interface UserCRUDInterface
{
    public function findById(int $id, User $user) : User;
    public function insert() : void;
    public function update() : void;
    public function delete() : void;
}