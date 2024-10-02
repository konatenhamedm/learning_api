<?php


namespace App\Events;

use App\Controller\Apis\Config\ApiInterface;
use App\Entity\Prestataire;
use App\Entity\UserFront;
use App\Entity\Utilisateur;
use App\Entity\UtilisateurSimple;
use App\Repository\UserFrontRepository;
use App\Repository\UtilisateurRepository;
use DateTime;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;



class AuthenticationSuccessListener
{
    private $utilisateurRepository;
   
    public function __construct(UtilisateurRepository $utilisateurRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
    }

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        //dd("");
        $data = $event->getData();
        $user = $event->getUser();
        //$response = $this->response($user);
        // dd($data["1"]);
        /*  if (!$user instanceof UserFront || !$user instanceof Utilisateur) {
            return;
        } */

        
        if ($user instanceof Utilisateur) {
            $userData = $this->utilisateurRepository->find($user->getId());
            //dd($user);

            $data['data'] =   [
                'reference' => $user->getId(),
                'username' => $userData->getEmail(),
            ];
            // dd($data)
            $event->setData($data);
        }

       
        /* else {
            $userData = $this->userFrontRepository->findOneBy(array('reference' => $user->getReference()));

            //dd($userData["reference"]);$response->getContent();

            $type = str_contains($userData->getReference(), 'PR') ? "prestataire" : "simple";


            $data['user'] =   [
                'id' =>    $userData->getReference(),
                'name' =>    $user->getDenominationSociale(),
                "type" => $type,
                "email" => $userData->getEmail(),
                'image' => 'http://localhost:8000/uploads/' . $userData->getPhoto()->getPath() . '/' . $userData->getPhoto()->getAlt()
            ];
            $event->setData($data);
        } */

        /*if($user instanceof Utilisateur ){
            $data['data'] = array(
                'id'=>$user->getId(),
                'nom'=>$user->getNomComplet(),

            );
            $event->setData($data);

        }*/
    }
}
