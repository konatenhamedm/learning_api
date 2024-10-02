<?php


namespace App\Controller;


use App\Controller\FileTrait;
use App\Service\Menu;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    use FileTrait;

    protected const UPLOAD_PATH = 'media_entreprise';
    protected $em;
    protected $security;
    protected $menu;
    protected $hasher;
    protected $workflow;
    protected $entreprise;
    protected $notificationService;


    #[Route(path: '/print-iframe', name: 'default_print_iframe', methods: ["DELETE", "GET"], condition: "request.query.get('r')", options: ["expose" => true])]
    public function defaultPrintIframe(Request $request, UrlGeneratorInterface $urlGenerator)
    {
        $all = $request->query->all();
        //print-iframe?r=foo_bar_foo&params[']
        $routeName = $request->query->get('r');
        $title = $request->query->get('title');
        $params = $all['params'] ?? [];
        $stacked = $params['stacked'] ?? false;
        $redirect = isset($params['redirect']) ? $urlGenerator->generate($params['redirect'], $params) : '';
        $iframeUrl = $urlGenerator->generate($routeName, $params);

        $isFacture = isset($params['mode']) && $params['mode'] == 'facture' && $routeName == 'facturation_facture_print';

        return $this->render('home/iframe.html.twig', [
            'iframe_url' => $iframeUrl,
            'id' => $params['id'] ?? null,
            'stacked' => $stacked,
            'redirect' => $redirect,
            'title' => $title,
            'facture' => 0/*$isFacture*/
        ]);
    }
}
