<?php

use Ytake\TrinoClient\ClientSession;
use Ytake\TrinoClient\Session\PreparedStatement;
use Ytake\TrinoClient\Session\Property;

/**
 * Class ClientSessionTest
 *
 * @see ClientSession
 */
class ClientSessionTest extends \PHPUnit\Framework\TestCase
{
    public function testShouldReturnDefaultSession()
    {
        $session = new ClientSession('http://localhost', 'testing');
        $this->assertSame('testing', $session->getCatalog());
        $this->assertSame('http://localhost', $session->getHost());
        $this->assertSame('TrinoClient', $session->getSource());
        $this->assertSame('default', $session->getSchema());
        $this->assertSame('trino', $session->getUser());
        $this->assertNull($session->getTransactionId());
        $this->assertCount(0, $session->getProperty());
        $this->assertCount(0, $session->getPreparedStatement());
    }

    public function testShouldReturnChangedSession()
    {
        $session = new ClientSession('http://localhost', 'testing');
        $session->setSchema('testing');
        $this->assertSame('testing', $session->getSchema());
        $session->setSource('testingTrino');
        $this->assertSame('testingTrino', $session->getSource());
        $uuid = \Ramsey\Uuid\Uuid::uuid4();
        $session->setTransactionId($uuid);
        $this->assertSame($uuid, $session->getTransactionId());
        $session->setUser('testing');
        $this->assertSame('testing', $session->getUser());
        $session->setProperty(new Property('testing', '1'));
        $this->assertCount(1, $session->getProperty());
        $this->assertSame('testing', $session->getProperty()[0]->getKey());
        $this->assertSame('1', $session->getProperty()[0]->getValue());
        $session->setPreparedStatement(new PreparedStatement('1', '1'));
        $this->assertCount(1, $session->getPreparedStatement());
        $this->assertSame('1', $session->getPreparedStatement()[0]->getKey());
        $this->assertSame('1', $session->getPreparedStatement()[0]->getValue());
    }
}
