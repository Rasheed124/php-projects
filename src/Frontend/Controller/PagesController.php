<?php
namespace App\Frontend\Controller;

use App\Admin\Support\AdminSupport;
use App\Frontend\Controller\AbstractFrontendController;
use App\Repository\Admin\PostsRepository;
use App\Repository\PagesRepository;

//
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class PagesController extends AbstractFrontendController
{
    public function __construct(
        PagesRepository $pagesRepository,
        AdminSupport $sessionController, protected PostsRepository $postsRepository) {
        parent::__construct($pagesRepository, $sessionController);
    }

    private function getSidebarData(): array
    {
        return [
            'recentPosts' => $this->postsRepository->all(['limit' => 3]),
            'categories'  => $this->postsRepository->getActiveCategories(),
            'tags'        => $this->postsRepository->getActiveTags(),
        ];
    }

    public function showPage($slug)
    {
        $page = $this->pagesRepository->fetchBySlug($slug);
        if (! $page) {
            $this->error404();
            return;
        }

        $data = array_merge(['page' => $page], $this->getSidebarData());

        if ($slug === 'index') {
            $data['featuredPosts'] = $this->postsRepository->all(['limit' => 3]);
            $data['latestPosts']   = $this->postsRepository->all(['limit' => 6]);
            $view                  = 'pages/index';
        } elseif ($slug === 'contact-us') {
            $view = 'pages/contact';

        } elseif ($slug === 'blog') {

            $limit       = 2;
            $currentPage = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
            $offset      = ($currentPage - 1) * $limit;

            $totalPosts = $this->postsRepository->getTotalCount();

            $data['posts'] = $this->postsRepository->all([
                'limit'  => $limit,
                'offset' => $offset,
            ]);

            $data['pagination'] = [
                'current'     => $currentPage,
                'total_pages' => ceil($totalPosts / $limit),
                'has_next'    => $currentPage < ceil($totalPosts / $limit),
                'has_prev'    => $currentPage > 1,
            ];

            $data['title'] = "Our Blog";
            $view          = 'pages/blog';
        } else {
            $view = 'pages/show';
        }

        $this->render($view, $data);
    }

    public function showSinglePost($slug)
    {
        $post = $this->postsRepository->fetchBySlug($slug);
        if (! $post) {
            $this->error404();
            return;
        }

                                                                // 1. Get identifiers for the current visitor
        $currentUserId = $this->sessionController->getUserID(); // null if guest
        $guestEmail    = $_SESSION['guest_email'] ?? null;      // email stored in store()

        // 2. Fetch comments with "Persistence" logic
        // This allows the user to see their own unapproved comments after a refresh
        $comments = $this->postsRepository->getCommentsByPost($post['id'], $currentUserId, $guestEmail);

        $data = array_merge([
            'post'          => $post,
            'page'          => (object) ['slug' => 'blog'],
            'comments'      => $comments,
            'currentUserId' => $currentUserId,

        ], $this->getSidebarData());

        $this->render('pages/post', $data);
    }

    public function showByCategory($slug)
    {
        $limit       = 2;
        $currentPage = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $offset      = ($currentPage - 1) * $limit;

        $filters    = ['category_slug' => $slug];
        $totalPosts = $this->postsRepository->getCountByFilter($filters);

        $posts = $this->postsRepository->allPostByTagAndCat(array_merge($filters, [
            'limit'  => $limit,
            'offset' => $offset,
        ]));

        $data = array_merge([
            'posts'      => $posts,
            'title'      => 'Category: ' . str_replace('-', ' ', $slug),
            'page'       => (object) ['slug' => 'blog'],
            'pagination' => [
                'current'     => $currentPage,
                'total_pages' => ceil($totalPosts / $limit),
                'has_next'    => $currentPage < ceil($totalPosts / $limit),
                'has_prev'    => $currentPage > 1,
            ],
        ], $this->getSidebarData());

        $this->render('pages/post-list', $data);
    }

    public function showByTag($slug)
    {
        $limit       = 2;
        $currentPage = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $offset      = ($currentPage - 1) * $limit;

        $filters    = ['tag_slug' => $slug];
        $totalPosts = $this->postsRepository->getCountByFilter($filters);

        $posts = $this->postsRepository->allPostByTagAndCat(array_merge($filters, [
            'limit'  => $limit,
            'offset' => $offset,
        ]));

        $data = array_merge([
            'posts'      => $posts,
            'title'      => 'Tag: ' . str_replace('-', ' ', $slug),
            'page'       => (object) ['slug' => 'blog'],
            'pagination' => [
                'current'     => $currentPage,
                'total_pages' => ceil($totalPosts / $limit),
                'has_next'    => $currentPage < ceil($totalPosts / $limit),
                'has_prev'    => $currentPage > 1,
            ],
        ], $this->getSidebarData());

        $this->render('pages/post-list', $data);
    }

    public function search()
    {
        $query = isset($_GET['q']) ? trim($_GET['q']) : '';

        // If query is empty, just redirect to blog or show all
        if (empty($query)) {
            header('Location: ' . url('blog'));
            exit;
        }

        $limit       = 10; // You can adjust this
        $currentPage = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $offset      = ($currentPage - 1) * $limit;

        // Get filtered posts
        $posts = $this->postsRepository->all([
            'search' => $query,
            'limit'  => $limit,
            'offset' => $offset,
        ]);

        // Get total count for search results specifically
        $totalPosts = $this->postsRepository->getTotalCount(['search' => $query]);

        $data = array_merge([
            'posts'      => $posts,
            'title'      => 'Search Results for: ' . htmlspecialchars($query),
            'page'       => (object) ['slug' => 'search'],
            'pagination' => [
                'current'     => $currentPage,
                'total_pages' => ceil($totalPosts / $limit),
                'has_next'    => $currentPage < ceil($totalPosts / $limit),
                'has_prev'    => $currentPage > 1,
            ],
        ], $this->getSidebarData());

        $this->render('pages/blog', $data);
    }

    public function handleContact()
    {
        // 1. Security Check (Honeypot)
        if (! empty($_POST['website_url'])) {
            die("Bot detected.");
        }

        // 2. Input Validation
        $name    = trim($_POST['name'] ?? '');
        $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $subject = trim($_POST['subject'] ?? '');
        $message = nl2br(htmlspecialchars(trim($_POST['message'] ?? '')));

        if (! $name || ! $email || ! $subject || ! $message) {
            $this->sessionController->setFlash('error', 'All fields are required.');
            header('Location: ' . url('contact-us'));
            exit;
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'rashdevatrealglobe@gmail.com';
            $mail->Password   = 'whrikixugghwginh';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('rashdevatrealglobe@gmail.com', 'Tech Design Space Contact');
            $mail->addAddress('techdesignspace01@gmail.com');
            $mail->addReplyTo($email, $name);

            $mail->isHTML(true);
            $mail->Subject = "New Inquiry: " . $subject;
            $mail->Body    = "
            <div style='font-family: Arial, sans-serif; border: 1px solid #eee; padding: 20px;'>
                <h3 style='color: #f48840;'>New Message Received</h3>
                <p><strong>Name:</strong> {$name}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Subject:</strong> {$subject}</p>
                <hr style='border: 0; border-top: 1px solid #eee;'>
                <p><strong>Message:</strong><br>{$message}</p>
            </div>
        ";

            $mail->send();

                                 
            $mail->clearAddresses(); 
            $mail->clearReplyTos();
            $mail->addAddress($email, $name); 

            $mail->Subject = "Thank you for contacting Tech Design Space";
            $mail->Body    = "
            <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                <h2 style='color: #f48840;'>Hello {$name},</h2>
                <p>Thank you for reaching out to <strong>Tech Design Space</strong>! We have received your inquiry regarding <em>'{$subject}'</em>.</p>
                <p>Our team is currently reviewing your message and will get back to you within 24-48 business hours.</p>
                <div style='background: #f9f9f9; padding: 15px; border-left: 4px solid #f48840; margin: 20px 0;'>
                    <strong>Your Message Summary:</strong><br>
                    <span style='font-style: italic; color: #666;'>\"{$subject}\"</span>
                </div>
                <p>In the meantime, feel free to visit our <a href='" . url('blog') . "'>blog</a> for the latest updates in tech and design.</p>
                <br>
                <p>Best regards,<br>
                <strong>The Tech Design Space Team</strong></p>
                <hr style='border: 0; border-top: 1px solid #eee;'>
                <small style='color: #999;'>This is an automated response. Please do not reply directly to this email.</small>
            </div>
        ";

            $mail->send();

            $this->sessionController->setFlash('success', 'Your message has been sent successfully! Check your email for confirmation.');

        } catch (Exception $e) {
            $this->sessionController->setFlash('error', "Message could not be sent. Please try again.");
        }

        header('Location: ' . url('contact-us'));
        exit;
    }
}
