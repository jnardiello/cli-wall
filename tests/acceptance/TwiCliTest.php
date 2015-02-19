<?php

namespace TwiCli;

class TwiCliTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->twiCli = new TwiCli();
    }

    public function testUserCanPostMultipleMessagesOnHisWall()
    {
        $this->twiCli->process('Alice -> This is test message 1');
        $this->twiCli->process('Alice -> This is test message 2');

        // We expect one user (Alice) and two messages for Alice
        $this->assertUsersEquals(1);
        $this->assertMessagesPerUserEquals(2, 'Alice');
    }

    public function testMultipleUserCanPostOnRespectiveWall()
    {
        $this->twiCli->process('Alice -> This is test message');
        $this->twiCli->process('Bob -> This is test message');

        // We expect two users with one message each
        $this->assertUsersEquals(2);
        $this->assertMessagesPerUserEquals(1, 'Alice');
        $this->assertMessagesPerUserEquals(1, 'Bob');
    }

    public function testCanReadAUserTimeline()
    {
        $this->twiCli->process('Alice -> Hello World');
        $this->twiCli->process('Alice -> Yesterday the weather was really nice');
        $this->twiCli->process('Bob -> Yesterday evening it was amazing!');

        $this->twiCli->process('Alice');
        $this->expectOutputString(
            "Hello World\n" .
            "Yesterday the weather was really nice\n"
        );
    }

    private function assertUsersEquals($number)
    {
        $users = $this->twiCli->getUsers();
        $this->assertEquals($number, count($users));
    }

    private function assertMessagesPerUserEquals($numMessages, $user)
    {
        $users = $this->twiCli->getUsers();
        $user = $users[$user];
        $userMessages = $user->getMessages();
        $this->assertEquals($numMessages, count($userMessages));
    }
}
