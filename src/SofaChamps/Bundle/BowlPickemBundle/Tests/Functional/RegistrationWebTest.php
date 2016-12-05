<?php

namespace SofaChamps\Bundle\BowlPickemBundle\Tests\Functional;

use SofaChamps\Bundle\CoreBundle\Tests\SofaChampsWebTest;
use SofaChamps\Bundle\BowlPickemBundle\DataFixtures\ORM\Test\LoadTestingData;

/**
 * @group functional
 */
class RegistrationWebTest extends SofaChampsWebTest
{
    public function testRedirect()
    {
        $client = static::createClient();
        $this->loadFixtures([
            LoadTestingData::class,
        ]);

        $crawler = $client->request('GET', '/bowl-pickem/2013');
        var_dump($crawler->getContent());

        $link = $crawler->selectLink("Signup")->link();
        $crawler = $client->click($link);
        $crawler = $client->followRedirect();

        $form = $crawler->filter('form[action*="/register/"][method="POST"] button[type="submit"]')->form();
        $password = uniqid();
        $form['fos_user_registration_form[username]'] = uniqid();
        $form['fos_user_registration_form[email]'] = $this->getFaker()->email();
        $form['fos_user_registration_form[plainPassword][first]'] = $password;
        $form['fos_user_registration_form[plainPassword][second]'] = $password;

        $client->submit($form);

        $response = $client->getResponse();
        $this->assertEquals('Symfony\Component\HttpFoundation\RedirectResponse', get_class($response));
    }
}
