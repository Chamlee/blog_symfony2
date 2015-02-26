<?php

namespace Chamlee\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Chamlee\BlogBundle\Entity\Enquiry;
use Chamlee\BlogBundle\Form\EnquiryType;


class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        // $blogs = $em->getRepository('ChamleeBlogBundle:Blog')->findBy( des arrays etc ... )
        // );

        // $query = $em->createQuery(
        //     'SELECT p
        //     FROM ChamleeBlogBundle:Blog p
        //     ORDER BY p.created DESC'
        // );
        //->setParameter('price', '19.99')
        //faisable 

        //$blogs = $query->getResult();

        //AUTRE MEHODE AVEC LE QUERY BUILDER
        //
        // $query = $repository->createQueryBuilder('p')
        //     ->where('p.price > :price')
        //     ->setParameter('price', '19.99')
        //     ->orderBy('p.price', 'ASC')
        //     ->getQuery();

        // $products = $query->getResult();

        //AVEC LE REPOSIOR LA POUR CA :
        $blogs = $em->getRepository('ChamleeBlogBundle:Blog')->getLatestBlogs();

        return $this->render('ChamleeBlogBundle:Page:index.html.twig', array(
            'blogs' => $blogs
        ));
    }

    public function aboutAction()
    {
        return $this->render('ChamleeBlogBundle:Page:about.html.twig');
    }

    public function contactAction()
    {
        $enquiry = new Enquiry();
        $form = $this->createForm(new EnquiryType(), $enquiry);

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                //PAS FONCTIONNEL (parameters.yml incomplete)
                // $message = \Swift_Message::newInstance()
                //     ->setSubject('Contact enquiry from symblog')
                //     ->setFrom('arnaud8ruckebusch@gmail.com')
                //     ->setTo('$this->conainer->getParameter('chamleeblog.emails.contact_email')')
                //     ->setBody($this->renderView('ChamleeBlogBundle:Page:contactEmail.html.twig', array('enquiry' => $enquiry)));

                // $this->get('mailer')->send($message);

                $this->get('session')->getFlashBag()->add('notice', 'Your contact enquiry was successfully sent. Thank you!');

                return $this->redirect($this->generateUrl('chamlee_blog_contact', array(
                    'form'  =>  $form->createView(),
                )));
            }
        }

        return $this->render('ChamleeBlogBundle:Page:contact.html.twig', array(
            'form'  =>  $form->createView(),
        ));
    }

    public function showAction($id, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $blog = $em->getRepository('ChamleeBlogBundle:Blog')->find($id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post');
        }

        $comments = $em->getRepository('ChamleeBlogBundle:Comment')->getCommentsForBlog($blog->getId());

        return $this->render('ChamleeBlogBundle:Page:show.html.twig', array(
            'blog'      => $blog,
            'comments'  => $comments,
        ));
    }

    public function sidebarAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tags = $em->getRepository('ChamleeBlogBundle:Blog')->getTags();

        $tagWeights = $em->getRepository('ChamleeBlogBundle:Blog')->getTagWeights($tags);

        $commentLimit = $this->container->getParameter('chamlee_blog.comments.latest_comment_limit');
        $latestComments = $em->getRepository('ChamleeBlogBundle:Comment')->getLatestComments($commentLimit);

        return $this->render('ChamleeBlogBundle:Page:sidebar.html.twig', array(
            'latestComments'    => $latestComments,
            'tags'              => $tagWeights
        ));
    }
}
