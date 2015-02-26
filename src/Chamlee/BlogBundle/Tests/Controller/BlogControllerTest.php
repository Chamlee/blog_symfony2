<?php

namespace Chamlee\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase
{
    public function testAddBlogComment()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/article/1/a-day-with-symfony');

        //Assurance que l'on a bien la page
        $this->assertEquals(1, $crawler->filter('h2:contains("A day with Symfony2")')->count());

        //Select based on button value, or id or name for buttons
        $form = $crawler->selectButton('Submit')->form();

        $crawler = $client->submit($form, array(
            'chamlee_blogbundle_comment[user]'          => 'name',
            'chamlee_blogbundle_comment[comment]'       => 'comment',
        ));

        // On suit la redirection
        $crawler = $client->followRedirect();

        // On vérifie que le commentaire s'affiche, et que c'est le dernier. Cela assure que les commentaires
        // vont du plus vieux au plus récent.
        $articleCrawler = $crawler->filter('section .previous-comments article')->last();

        $this->assertEquals('name', $articleCrawler->filter('header span.highlight')->text());
        $this->assertEquals('comment', $articleCrawler->filter('p')->last()->text());

        // On vérifie que la barre latérale affiche bien 10 derniers articles.
        $this->assertEquals(4, $crawler->filter('aside.sidebar section')->last()
            ->filter('article')->count()
        );

        $this->assertEquals('name', $crawler->filter('aside.sidebar section')->last()
            ->filter('article')->first()
            ->filter('header span.highlight')->text()
        );
    }
}