<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientsType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IngredientController extends AbstractController
{
    /**
     * This function display all ingradients 
     *
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'app_ingredient', methods:['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $ingredients = $repository->findAll();
        $pagination = $paginator->paginate(
            $ingredients, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            10 /* limit per page */
        );
        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $pagination,
        ]);
    }


    /**
     * This function create a new ingredient
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/new',name:'ingredient.new',methods:['GET','POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
        ): Response 
        {
        $ingredient = new Ingredient();

        $form = $this->createForm(IngredientsType::class, $ingredient);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 

            $ingredient = $form->getData(); 
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'succes',
                'Votre ingredient a ete ajoute !'
            );
            return $this->redirectToRoute('app_ingredient');
        }
        return $this->render('pages/ingredient/new.html.twig',[
            'form'=> $form->createView()
        ]);
    }
/**
 *   #[Route('ingredient/edit/{id}',name:'ingredient.edit', methods:['GET','POST'])]
 *  public function edit(IngredientRepository $repository, int $id):Response 
 *  {
 *     $ingredient = $repository->findOneBy(['id'=>$id]);
 * 
 *    $form = $this->createForm(IngredientsType::class, $ingredient)
 *    return $this->render('pages/ingredient/edit.html.twig',[
 *         'form'=>$form->createView()
 *     ]);
 *   }
 */

 #[Route('ingredient/edit/{id}',name:'ingredient.edit', methods:['GET','POST'])]
 public function edit(
     Ingredient $ingredient,
     Request $request,
     EntityManagerInterface $manager,
     ):Response 
 {
     $form = $this->createForm(IngredientsType::class, $ingredient);
     $form->handleRequest($request);
     if($form->isSubmitted() && $form->isValid()){
        $ingredient = $form->getData(); 
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'succes',
                'Votre ingredient a ete modifee !'
            );
            return $this->redirectToRoute('app_ingredient');
     }
     return $this->render('pages/ingredient/edit.html.twig',[
         'form'=>$form->createView()
     ]);
 }
 #[Route('/ingredient/delete/{id}', name:'ingredient.delete', methods:['POST','GET'])]
 public function delete(Ingredient $ingredient, EntityManagerInterface $manager): Response {
    if(!$ingredient)
        return $this->redirectToRoute('app_ingredient');
    $manager->remove($ingredient);
    $manager->flush();


    return $this-> redirectToRoute('app_ingredient');
 }
}
