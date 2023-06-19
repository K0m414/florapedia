<?php

namespace App\Controller;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\CategoryRepository;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    #[Route('/', name: 'app_front')]
    public function index(ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {
        $articles = $articleRepository->findLatest(3);
        $categories = $categoryRepository->findAll();
    
        return $this->render('front/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }
    
    #[Route('/all-articles', name: 'app_front_all_articles')]
    public function showAllArticle(Request $request, PaginatorInterface $paginator, ArticleRepository $articleRepository): Response
    {
        $query = $articleRepository->createQueryBuilder('a')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3 // Nombre d'articles par page
        );

        return $this->render('front/all-articles.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    
    #[Route('/article/{id}', name: 'app_front_show')]
public function showArticle(Article $article, $id, Request $request, CommentRepository $commentRepository, ArticleRepository $articleRepository): Response
{
    $comment = new Comment();
    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);
    $user = $this->getUser();
    $articles = $articleRepository->findOneBy(['id' => $id]);

    if ($form->isSubmitted() && $form->isValid()) {
        $comment->setCommentBy($user)->setArticle($article); // Assurez-vous d'associer le commentaire à l'article actuel
        $commentRepository->save($comment, true);
        return $this->redirectToRoute('app_front_show', ['id' => $id], Response::HTTP_SEE_OTHER);
    }

    return $this->render('front/article.html.twig', [
        'article' => $article,
        'form' => $form->createView(),
    ]);
}
#[Route('/report-comment/{id}', name: 'app_front_report_comment', methods: ['POST'])]
public function reportComment(Comment $comment, CommentRepository $commentRepository): Response
{
    // Set the reported flag for the comment
    $comment->setReported(true);
    $commentRepository->save($comment);

    // Redirect back to the article page
    return $this->redirectToRoute('app_front_article', ['id' => $comment->getArticle()->getId()]);
}

public function about(): Response
{
    return $this->render('front/about.html.twig');
}
#[Route('/contact', name: 'app_front_contact')]
    public function contact(Request $request): Response
    {
        return $this->render('front/contact.html.twig');
    }


}
