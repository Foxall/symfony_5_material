<?php

namespace App\Controller;

use FPDF;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Materials;

class MaterialController extends AbstractController
{
    /**
     * @Route("/", name="material")
     */
    public function index(Request $request, ObjectManager $manager)
    {
        $repo = $this->getDoctrine()->getRepository(Materials::class, );

        $materials = $repo->findAll();



        return $this->render('material/index.html.twig', [
            'controller_name' => 'MaterialController',
            'materials' => $materials,
        ]);
    }

    /**
     * @Route("/details/{id}", name="detail-material")
     */
    public function details($id)
    {
        $repo = $this->getDoctrine()->getRepository(Materials::class);
        $materials = $repo->find($id);

        return $this->render('material/details.html.twig', [
            'materials' => $materials,
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="modifier-material")
     */
    public function modifier($id, Request $request, ObjectManager $manager)
    {
        $repo = $this->getDoctrine()->getRepository(Materials::class);
        $materials = $repo->find($id);

        $form = $this->createFormBuilder($materials)
                     ->add('Nom')
                     ->add('Prix')
                     ->add('Nombre')
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($materials);
            $manager->flush();

            return $this->redirectToRoute('material');
        }

        return $this->render('material/modifier.html.twig', [
            'materials' => $materials,
            'formMaterial' => $form->createView(),
        ]);
    }

    /**
     * @Route("/generate/{id}", name="pdf_generate")
     */
    public function generatePdfAction($id)
        {
            $repo = $this->getDoctrine()->getRepository(Materials::class);
            $material = $repo->find($id);
            $pdf = new FPDF();
            // Utilisez les méthodes de la classe FPDF pour ajouter du contenu à votre PDF
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'nom du materiel :','0',1);
            $pdf->Cell(0, 10, $material->getNom(),'0',1);
            $pdf->Cell(0, 10, 'Nombre de materiel disponible :','0',1);
            $pdf->Cell(0, 10, $material->getNombre(),'0',1);
            $pdf->Cell(0, 10, 'prix du materiel :','0',1);
            $pdf->Cell(0, 10, $material->getPrix(),'0',0);
            // Retournez le contenu du PDF
            return new Response($pdf->Output(), 200, array(
                'Content-Type' => 'application/pdf'
            ));
        }
}