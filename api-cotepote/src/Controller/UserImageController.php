<?php

namespace App\Controller;

use App\Entity\User;
use DateTime;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;

class UserImageController
{

    public function __invoke(Request $request)
    {
        $user = $request->attributes->get('data');
        if(!($user instanceof User)){
            throw new RuntimeException('utilisateurs attendu');
        }
        $file = $request->files->get('file');
        $user->setFile($request->files->get('file'));
         $user->SetUpdatedAt(new DateTime());
        // dd($file, $user);
        return $user;
    }

}
