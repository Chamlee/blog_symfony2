<?php

namespace Chamlee\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
	   //Test fonctionnel de la page about
     public function testAbout()
     {
         $client = static::createClient();

         $crawler = $client->request('GET', '/about');

         $this->assertCount(1, $crawler->filter('h1'));
     }

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        // Vérifie qu'il y a des articles dans la page
        $this->assertGreaterThan(0, $crawler->filter('article.blog')->count());
    }

    //test de formulaire !!! important
    public function testContact()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        $this->assertEquals(1, $crawler->filter('h1:contains("Contact symblog")')->count());

        // Sélection basée sur la valeur, l'id ou le nom des boutons
        $form = $crawler->selectButton('Submit')->form();

        $form['contact[name]']       = 'name';
        $form['contact[email]']      = 'email@email.com';
        $form['contact[subject]']    = 'Subject';
        $form['contact[body]']       = 'On écrit ce que l on veut :p';

        $crawler = $client->submit($form);

        //Attention follow à ne pas oublier dans la réponse
        //Penser à activer l'envoie d'email dans la config de test également
        $crawler = $client->followRedirect();

        //$this->assertEquals(1, $crawler->filter('.blogger-notice:contains("Your contact enquiry was successfully sent. Thank you!")')->count());
    }
}