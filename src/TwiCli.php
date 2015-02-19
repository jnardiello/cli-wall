<?php

namespace TwiCli;

/**
 * Class TwiCli
 * @author Jacopo Nardiello
 */
class TwiCli
{
    private $users = [];

    public function process($input)
    {
        $input = explode(' ', $input);
        $name = $input[0];
        $cmd = (isset($input[1])) ? $input[1] : '';

        if ($cmd == '->') {
            $message = implode(' ', array_slice($input, 2));

            $user = $this->findUser($name);
            $user->post($message);
        } else if ($cmd == '') {
            $user = $this->findUser($name);
            foreach ($user->getMessages() as $message) {
                echo $message->getValue() . "\n";
            }
        }
    }

    private function findUser($currentName)
    {
        // If user already exist let's just return it
        foreach ($this->users as $name => $user) {
            if ($currentName == $name) {
                return $user;
            }
        }

        // Otherwise we add it and return it
        $newUser = new User($currentName);
        $this->users[$newUser->getName()] = $newUser;
        return $newUser;
    }

    public function getUsers()
    {
        return $this->users;
    }
}
