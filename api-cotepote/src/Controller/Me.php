<?php

namespace App\Controller;

exclude: '../src/{Application/Message,Infrastructure/Repository/MySql/Migrations,Tests,Kernel.php}';

use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Me extends AbstractController {

    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }
    
    public function __invoke()
    {
        
        $user = $this->security->getUser();
        return $user;        
    }

}

?>