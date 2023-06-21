<?php

namespace App\Test\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ClientRepository $repository;
    private string $path = '/client/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Client::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(302);
//        self::assertPageTitleContains('Client index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'client[nomClient]' => 'Testing',
            'client[prenomClient]' => 'Testing',
            'client[telFixe]' => 'Testing',
            'client[telPortable1]' => 'Testing',
            'client[telPortable2]' => 'Testing',
            'client[refClient]' => 'Testing',
            'client[etat]' => 'Testing',
        ]);

        self::assertResponseRedirects('/client/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Client();
        $fixture->setNomClient('My Title');
        $fixture->setPrenomClient('My Title');
        $fixture->setTelFixe('My Title');
        $fixture->setTelPortable1('My Title');
        $fixture->setTelPortable2('My Title');
        $fixture->setRefClient('My Title');
        $fixture->setEtat('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Client');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Client();
        $fixture->setNomClient('My Title');
        $fixture->setPrenomClient('My Title');
        $fixture->setTelFixe('My Title');
        $fixture->setTelPortable1('My Title');
        $fixture->setTelPortable2('My Title');
        $fixture->setRefClient('My Title');
        $fixture->setEtat('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'client[nomClient]' => 'Something New',
            'client[prenomClient]' => 'Something New',
            'client[telFixe]' => 'Something New',
            'client[telPortable1]' => 'Something New',
            'client[telPortable2]' => 'Something New',
            'client[refClient]' => 'Something New',
            'client[etat]' => 'Something New',
        ]);

        self::assertResponseRedirects('/client/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNomClient());
        self::assertSame('Something New', $fixture[0]->getPrenomClient());
        self::assertSame('Something New', $fixture[0]->getTelFixe());
        self::assertSame('Something New', $fixture[0]->getTelPortable1());
        self::assertSame('Something New', $fixture[0]->getTelPortable2());
        self::assertSame('Something New', $fixture[0]->getRefClient());
        self::assertSame('Something New', $fixture[0]->getEtat());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Client();
        $fixture->setNomClient('My Title');
        $fixture->setPrenomClient('My Title');
        $fixture->setTelFixe('My Title');
        $fixture->setTelPortable1('My Title');
        $fixture->setTelPortable2('My Title');
        $fixture->setRefClient('My Title');
        $fixture->setEtat('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/client/');
    }
}
