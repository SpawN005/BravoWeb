<?php

namespace App\Test\Controller;

use App\Entity\Donater;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DonaterControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/donater/conntroller/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Donater::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Donater index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'donater[amount]' => 'Testing',
            'donater[idDonation]' => 'Testing',
            'donater[idUser]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Donater();
        $fixture->setAmount('My Title');
        $fixture->setIdDonation('My Title');
        $fixture->setIdUser('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Donater');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Donater();
        $fixture->setAmount('Value');
        $fixture->setIdDonation('Value');
        $fixture->setIdUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'donater[amount]' => 'Something New',
            'donater[idDonation]' => 'Something New',
            'donater[idUser]' => 'Something New',
        ]);

        self::assertResponseRedirects('/donater/conntroller/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getAmount());
        self::assertSame('Something New', $fixture[0]->getIdDonation());
        self::assertSame('Something New', $fixture[0]->getIdUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Donater();
        $fixture->setAmount('Value');
        $fixture->setIdDonation('Value');
        $fixture->setIdUser('Value');

        $$this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/donater/conntroller/');
        self::assertSame(0, $this->repository->count([]));
    }
}
