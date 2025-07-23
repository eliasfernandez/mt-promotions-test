<?php

namespace Features\Bootstrap;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Step\Given;
use Behat\Step\Then;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\Common\DataFixtures\Loader;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private ?Response $response;

    public function __construct(
        private KernelInterface $kernel,
        private EntityManagerInterface $em
    )
    {
    }
    #[Given('I load the large data fixtures')]
    public function iLoadTheLargeDataFixtures(): void
    {
        $this->setupDatabase();
        $this->loadFixtures();
    }


    #[Given('I call :path')]
    public function iCall($path): void
    {
        $this->response = $this->kernel->handle(Request::create($path, 'GET'));
    }

    #[Then('I should have the response content:')]
    public function iShouldHaveTheResponseContent(PyStringNode $pyString): void
    {
        Assert::assertSame($pyString->getRaw(), json_encode(json_decode($this->response->getContent(), true), JSON_PRETTY_PRINT));
    }

    #[Then('the response status code is :code')]
    public function theResponseStatusCodeIs(int $code): void
    {
        Assert::assertSame($code, $this->response->getStatusCode());
    }


    private function setupDatabase()
    {
        $this->createDatabaseIfItDoesntExists($this->em->getConnection());

        $metaData = $this->em->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropDatabase();

        if (!empty($metaData)) {
            $schemaTool->createSchema($metaData);
        }
    }

    private function createDatabaseIfItDoesntExists(Connection $connection): void
    {
        $process = new Process(['php', 'bin/console', 'doctrine:database:create', '--env=test', '--no-interaction']);
        $process->run();
    }


    private function loadFixtures()
    {
        $loader = new Loader();
        $loader->loadFromFile(__DIR__ . '/../../src/Infrastructure/DataFixtures/AppFixtures.php');
        $executor = new ORMExecutor($this->em);
        $executor->execute($loader->getFixtures(), true);
    }
}
