<?php

namespace SofaChamps\Bundle\SuperBowlChallengeBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * @group functional
 */
class PickControllerTest extends WebTestCase
{
    public function testPickActionRequiresAuthentication()
    {
        $this->markTestIncomplete('TODO');
        $client = static::createClient();
        $this->loadFixtures(array());

        $crawler = $client->request('GET', '/superbowl-challenge/2014/pick');

        $response = $client->getResponse();
        $this->assertEquals('Symfony\Component\HttpFoundation\RedirectResponse', get_class($response));
    }

    public function testPickAction()
    {
        $this->markTestIncomplete('TODO');
        $this->loadFixtures(array(
            'SofaChamps\Bundle\CoreBundle\DataFixtures\ORM\TestUsers',
        ));

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'userpass',
        ));

        $crawler = $client->request('GET', '/superbowl-challenge/2014/pick');

        $response = $client->getResponse();
        $this->assertEquals('Symfony\Component\HttpFoundation\Response', get_class($response));
    }
}
