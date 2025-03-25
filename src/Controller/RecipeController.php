<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class RecipeController extends AbstractController
{
    /**
     * This controller display all recipes
     *
     * @param PaginatorInterface $paginator
     * @param RecipeRepository $repository
     * @param Request $request
     * @return Response
     */
    #[Route('/recipe', name: 'recipe.index', methods:['GET'])]
    public function index(
        PaginatorInterface $paginator,
        RecipeRepository $repository, 
        Request $request): Response

    {
        $recipes = $repository->findAll();
        $pagination = $paginator->paginate(
            $recipes, 
            $request->query->getInt('page', 1), /* page number */
            10 /* limit per page */
        );
        return $this->render('pages/recipe/index.html.twig', [
            'recettes' => $pagination,
        ]);
    }

    #[Route('recipe/new/', name:'recipe.new',methods:['GET','POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
        ): Response 
        {
        $recipe = new Recipe();

        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 

            $ingredient = $form->getData(); 
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'succes',
                'Votre ingredient a ete ajoute !'
            );
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render('pages/recipe/new.html.twig',[
            'form'=> $form->createView()
        ]);
    }

    #[Route('recette/edit/{id}',name:'recette.edit', methods:['GET','POST'])]
 public function edit(
     Recipe $recipe,
     Request $request,
     EntityManagerInterface $manager,
     ):Response 
 {
     $form = $this->createForm(RecipeType::class, $recipe);
     $form->handleRequest($request);
     if($form->isSubmitted() && $form->isValid()){
        $recipe = $form->getData(); 
            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash(
                'succes',
                'Votre recette a ete modifee !'
            );
            return $this->redirectToRoute('recipe.index');
     }
     return $this->render('pages/recipe/edit.html.twig',[
         'form'=>$form->createView()
     ]);
 }
 #[Route('recette/delete/{id}', name:'recette.delete', methods:['POST','GET'])]
 public function delete(Recipe $recipe, EntityManagerInterface $manager): Response {
    if(!$recipe)
        return $this->redirectToRoute('recipe.index');
    $manager->remove($recipe);
    $manager->flush();


    return $this-> redirectToRoute('recipe.index');
 }
}
