<?php

namespace Chamlee\BlogBundle\Tests\Reposirory;

use Chamlee\BlogBundle\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogRepositoryTest extends WebTestCase
{
    private $blogrepository;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->blogRepository = $kernel->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository('ChamleeBlogBundle:Blog');
    }

    public function testGetTags()
    {
        $tags = $this->blogRepository->getTags();

        $this->assertTrue(count($tags) > 1);
        $this->assertContains('symblog', $tags);
    }

    public function testGetTagWeights()
    {
        $tagsWeight = $this->blogRepository->getTagWeights( array(
            'php', 'code', 'code', 'symblog', 'blog'
        ));

        $this->assertTrue(count($tagsWeight) > 1);

        //Si le poids est supérieur à 5
        $tagsWeight = $this->blogRepository->getTagWeights(
            array_fill(0, 10, 'php')
        );

        $this->assertTrue(count($tagsWeight) >= 1);

        // Cas où il y a plusieurs éléments dont le poids est supérieur à 5
        $tagsWeight = $this->blogRepository->getTagWeights(
            array_merge(array_fill(0, 10, 'php'), array_fill(0, 2, 'html'), array_fill(0, 6, 'js'))
        );

        $this->assertEquals(5, $tagsWeight['php']);
        $this->assertEquals(3, $tagsWeight['js']);
        $this->assertEquals(1, $tagsWeight['html']);

        // Cas vide
        $tagsWeight = $this->blogRepository->getTagWeights(array());

        $this->assertEmpty($tagsWeight);
    }
}