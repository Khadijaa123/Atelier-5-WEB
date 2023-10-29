<?php

namespace App\Controller;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/getAllBooks', name: 'getAllBooks')]
    public function getAllBooks(BookRepository $repo): Response
    {
        $list = $repo->findAll();
        return $this->render('book/listDB.html.twig', [
            'books' => $list
        ]);
}
    #[Route('/getOneBook/{ref}', name: 'getOneBook')]
    public function getOneBook(BookRepository $repo, $ref): Response
    {
    
    $book = $repo->find($ref);
    return $this->render('book/detailsDB.html.twig', [
        'book' => $book
    ]);
    }
    


    #[Route('/addBook', name: 'addBook')]  
    public function addBook(Request $req, ManagerRegistry $manager):Response{
        $book = new Book();
        $book->setRef('ref');
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($req);
        $em = $manager->getManager();
        if($form->isSubmitted() and $form ->isValid()){
        $em->persist($book);
        $em->flush();
        return $this->redirectToRoute("getAllBooks");
    }
    return $this->renderForm('book/new.html.twig',[
        'b'=>$form]);}

    #[Route('/updateBook/{ref}', name: 'updateBook')]  
    public function updateBook(Request $req, ManagerRegistry $manager, BookRepository $repo, $ref):Response{
        $book = $repo->find($ref);
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($req);
        $em = $manager->getManager();
        if($form->isSubmitted()){
        $em->persist($book);
        $em->flush();
        return $this->redirectToRoute("book_listDB");
    }
    return $this->renderForm('book/new.html.twig',['b'=>$form]);}

    #[Route('/deleteBook/{ref}',name:'deleteBook')]
    public function deleteBook ( $ref , ManagerRegistry $manager, BookRepository $repo):Response{
        $book=$repo->find($ref) ;  
        $em= $manager->getManager();
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute("book_listDB");
    }


    public function searchByRef(Request $request)
{
    $form = $this->createForm(SearchBookFormType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $ref = $form->get('ref')->getData();

        $books = $this->getDoctrine()->getRepository(Book::class)->findBy(['ref' => $ref]);
    } else {
        
        $books = $this->getDoctrine()->getRepository(Book::class)->findAll();
    }

    return $this->render('book/search.html.twig', [
        'form' => $form->createView(),
        'books' => $books,
    ]);

}

public function listBooksOrderedByAuthor(BookRepository $bookRepository)
{
    $books = $bookRepository->findBooksOrderedByAuthor();

    return $this->render('book/list_ordered_by_author.html.twig', [
        'books' => $books,
    ]);
}

public function listBooksBeforeYearWithAuthorMoreThan35Books(BookRepository $bookRepository)
{
    $books = $bookRepository->findBooksBeforeYearWithAuthorMoreThan35Books();

    return $this->render('book/list_books_before_year_with_author_more_than_35.html.twig', [
        'books' => $books,
    ]);
}

public function updateBooksCategory(EntityManagerInterface $entityManager, BookRepository $bookRepository)
{
    $authorName = "William Shakespeare";
    $newCategory = "Romance";

    $books = $bookRepository->findBooksByAuthor($authorName);

    foreach ($books as $book) {
        $book->setCategory($newCategory);
        $entityManager->persist($book);
    }

    $entityManager->flush();

    return $this->redirectToRoute('listDB.html.twig');
}



$em = $this->getDoctrine()->getManager();

$dql = "SELECT SUM(b.quantity) 
        FROM App\Entity\Book b
        WHERE b.category = 'Science Fiction'";

$query = $em->createQuery($dql);

$result = $query->getSingleScalarResult();

return $this->render('book/sum_science_fiction.html.twig', [
    'totalQuantity' => $result,
]);





$em = $this->getDoctrine()->getManager();

$startDate = new \DateTime("2014-01-01");
$endDate = new \DateTime("2018-12-31");

$dql = "SELECT b
        FROM App\Entity\Book b
        WHERE b.publicationDate >= :startDate
        AND b.publicationDate <= :endDate";

$query = $em->createQuery($dql)
    ->setParameter('startDate', $startDate)
    ->setParameter('endDate', $endDate);

$books = $query->getResult();

return $this->render('book/list_between_dates.html.twig', [
    'books' => $books,
]);



public function listAuthorsByBookCountRange(AuthorRepository $authorRepository, Request $request)
{
    $minBooks = $request->query->get('min_books', 0); // Valeur minimale par défaut
    $maxBooks = $request->query->get('max_books', 100); // Valeur maximale par défaut

    $authors = $authorRepository->findAuthorsByBookCountRange($minBooks, $maxBooks);

    return $this->render('author/list_by_book_count_range.html.twig', [
        'authors' => $authors,
        'minBooks' => $minBooks,
        'maxBooks' => $maxBooks,
    ]);
}


$em = $this->getDoctrine()->getManager();

$dql = "DELETE FROM App\Entity\Author a
        WHERE (
            SELECT COUNT(b) FROM App\Entity\Book b
            WHERE b.author = a
        ) = 0";

$query = $em->createQuery($dql);

$deletedAuthors = $query->execute();
return $this->render('author/deleted_authors.html.twig', [
    'deletedAuthors' => $deletedAuthors,
]);

}
