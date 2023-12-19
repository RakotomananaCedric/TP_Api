<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("liste_des_régions", name="listeRegions")
     */
    public function listeRegion(SerializerInterface $serializer)
    {
        $listeRegions=file_get_contents('https://geo.api.gouv.fr/regions');
        // $tableauRegion=$serializer->decode($listeRegions,'json');
        // $objetRegion=$serializer->denormalize($tableauRegion,'App\Entity\Region[]');
        $objetRegion=$serializer->deserialize($listeRegions,'App\Entity\Region[]','json');
        return $this->render('api/listeRegions.html.twig',[
            'listeRegions'=>$objetRegion
        ]);
    }

    /**
     * @Route("liste_des_départements_par_région", name="listeDepartements")
     */
    public function listeDepartementParRegion(SerializerInterface $serializer, Request $request)
    {
        $codeRegion=$request->query->get('region');
        $listeRegions=file_get_contents('https://geo.api.gouv.fr/regions');
        $objetRegion=$serializer->deserialize($listeRegions,'App\Entity\Region[]','json');
        if($codeRegion == null || $codeRegion == "Toutes")
        {
            $listeDepartements=file_get_contents('https://geo.api.gouv.fr/departements');
        }
        else
        {
            $listeDepartements=file_get_contents('https://geo.api.gouv.fr/regions/'.$codeRegion.'/departements');
        }
        $listeDepartements=$serializer->decode($listeDepartements,'json');
        return $this->render('api/listeDepartParRegion.html.twig',[
            'listeRegions'=>$objetRegion,
            'listeDepartements'=>$listeDepartements
        ]);
    }

    /**
     * @Route("liste_des_départements_par_région", name="listeDepartements")
     */
    public function listeCommuneParDepartement(SerializerInterface $serializer, Request $request)
    {
        $codeRegion=$request->query->get('region');
        $codeDepartement=$request->query->get('departement');
        $listeRegions=file_get_contents('https://geo.api.gouv.fr/regions');
        $objetRegion=$serializer->deserialize($listeRegions,'App\Entity\Region[]','json');
        if($codeRegion == null || $codeRegion == "Toutes")
        {
            $listeDepartements=file_get_contents('https://geo.api.gouv.fr/departements');
        }
        else
        {
            $listeDepartements=file_get_contents('https://geo.api.gouv.fr/regions/'.$codeRegion.'/departements');
        }
        if($codeDepartement == null || $codeDepartement == "Toutes")
        {
            $listeCommunes=file_get_contents('https://geo.api.gouv.fr/commune');
        }
        else
        {
            $listeCommunes=file_get_contents('https://geo.api.gouv.fr/departement/'.$codeDepartement.'/commune');
        }
        $listeDepartements=$serializer->decode($listeDepartements,'json');
        $listeCommunes=$serializer->decode($listeCommunes,'json');
        return $this->render('api/listeCommuneParDepart.html.twig',[
            'listeRegions'=>$objetRegion,
            'listeDepartements'=>$listeDepartements,
            'listeCommune'=>$listeCommunes
        ]);
    }
}
