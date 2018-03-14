<?php
/**
 * Created by PhpStorm.
 * User: f.ratsimbazafy
 * Date: 13/03/2018
 * Time: 15:54
 */

namespace TutoBundle\Listener;

use TutoBundle\Event\ObjetEvent;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Observe;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;

/**
 * @Service
 */
class MailListener
{

    private $mailer;
    private $twig;
    /**
     *@InjectParams({
     * "mailer" = @Inject("mailer"),
     *  "twig" = @Inject("twig")
    })
     */
    public function __construct($mailer,$twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @Observe("sendMailEvent", priority = 255)
     */

    public function sendMail()
    {
        $mail =(new\Swift_Message('test'))
            ->setFrom('noreply@etechconsulting-mg.com')
            ->setTo('finaritra.etech@gmail.com')
            ->setContentType('text/html')
            ->setCharset('UTF-8')
            ->setBody($this->twig->render('TutoBundle:Default:mail.html.twig'));
        $this->mailer->send($mail);
    }
}