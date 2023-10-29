<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{

    public $authors = array(
        array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );

    #[Route('/showauthor/{name}', name: 'app_showauthor')]
    public function showauthor(
        $name
    ): Response
    {
        return $this->render('author/show.html.twig', [
            'name'=>$name
        ]);
    }
  


#[Route('/showtableauthor', name: 'app_showtableauthor')]
    public function showtableauthor(): Response
    {
    
    
    return $this->render('author/list.html.twig', [
        'author'=>$this->authors


    ]);
}

#[Route('showbyidauthor/{id}', name: 'showbyidauthor')]
    public function showbyidauthor($id): Response
    {
        //var_dump($id). die();

        $author=null;
        foreach($this->authors as $authorD){
            if($authorD['id']==$id){
                $author=$authorD;
            }
        }
        var_dump($author).die();
        return $this->render('author/showbyidauthor.html.twig', [
            'author'=>$author
        ]);
    }
  
    #[Route('/showdbauthor', name: 'showdbauthor')]
    public function showdbauthor(AuthorRepository $authorRepository , Request $req): Response
    {
    
    

    $author=$authorRepository->findAll();
    /*********************************** */
    //$form=$this->createForm(SearchType::class);
    /************************************ */
    $form=$this->createForm(MinmaxType::class);
    $form->handleRequest($req);
    if  ($form->isSubmitted()){
        /*************************** */
       $datainput=$form->get('username')->getData();
        /************************************ */

        $min= $form->get('min')->getData();
        $max= $form->get('max')->getData();
        //var_dump($datainput).die();
        
  $author=$authorRepository->orderbyusername();
   $author=$authorRepository->searchByalphabet();
$authors =$authorRepository->searchbyusername($datainput);
$authors =$authorRepository->minmax($min,$max);
    return $this->render('author/showdbauthor.html.twig', [
        'author'=>$authors,
         'f'=>$form


    ]);
}

return $this->render('author/showdbauthor.html.twig', [
    'author'=>$author,
     'f'=>$form


]);
}

#[Route('/addauthor', name: 'addauthor')]
    public function addauthor(ManagerRegistry $managerRegistry): Response
    {
        $em=$managerRegistry->getManager();
        $author=new Author();
        $author->setUsername("3A58");
        $author->setEmail("3A58@esprit.tn");
        $em->persist($author);
        $em->flush();
        return new Response("great add");

}

#[Route('/addformauthor', name: 'addformauthor')]
public function addformauthor(ManagerRegistry $managerRegistry, Request $req): Response
{
    $em=$managerRegistry->getManager();
    $author=new Author();
    $form=$this->createForm(AuthorType::class,$author);
    $form->handleRequest($req);
    if($form->isSubmitted() and $form->isValid()){

        $em->persist($author);
        $em->flush();
        return $this->redirectToRoute('showdbuthor');
    }

return $this->renderForm('author/addformauthor.html.twig', [
    'f'=>$form
]);
}

#[Route('/editauthor/{id}', name: 'editauthor')]
public function editauthor($id, AuthorRepository $authorRepository,Request $req, ManagerRegistry $managerRegistry): Response
    {
    $em= $managerRegistry->getManager();
    //var_dump($id).die();
    $dataid=$authorRepository->find($id);
    //var_dump($dataid).die();
    $form=$this->createForm(AuthorType::class,$dataid);
    $form->handleRequest($req);
    if($form->isSubmitted() and $form->isValid()){
        $em->persist($dataid);
        $em->flush(); 
        return $this->redirectToRoute('showdbuthor');
    }

    return $this->renderForm('author/editauthor.html.twig', [
      'x'=>$form  

    ]);
}
#[Route('/deleteauthor/{id}', name: 'deleteauthor')]
public function deleteauthor($id, AuthorRepository $authorRepository, ManagerRegistry $managerRegistry): Response
    {
    $em= $managerRegistry->getManager();
    //var_dump($id).die();
    $dataid=$authorRepository->find($id);
    //var_dump($dataid).die();
    $em->remove($dataid);
    $em->flush();

    return $this->redirectToRoute('showdbuthor');


}
#[Route('/test', name: 'test')]
    public function test(AuthorRepository $authorRepository): Response
    {
        $test=$authorRepository->showAllAuthorByUsername();
        var_dump($test).die();
        return $this->render('author/showdbauthor.html.twig', [
            'authors' => $authorRepository->showAllAuthorByUsername(),
        ]);
    }

    public function listAuthorsOrderedByEmail(AuthorRepository $authorRepository)
{
    $authors = $authorRepository->findAuthorsOrderedByEmail();

    return $this->render('author/showdbauthor.html.twig', [
        'authors' => $authors,
    ]);
}
    
}