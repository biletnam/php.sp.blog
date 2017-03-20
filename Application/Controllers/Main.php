<?php

namespace Application\Controllers;

use Application\View;
use Application\Controller;
use Application\Exceptions\Core;
use Application\Exceptions\Db;
use Application\Models\User;
use Application\Models\Session;

class Main
    extends Controller
{
    protected function beforeAction()
    {

    }
    
    protected function actionIndex()
    {
        $this->view->title = "Мой сайт";
        
        $currentPage = (!empty($_GET['page'])) ? (int)$_GET['page'] : 1;
        $max = 5;
        $pages = \Application\Models\Page::findAllMain();
        $total = count($pages);
        $pagination = new \Application\Components\Pagination($currentPage, $max, $total, $pages);
        $pages = $pagination->getPages();
        $this->view->pages = $pages;
        $this->view->prev = $currentPage - 1;
        $this->view->next = $currentPage + 1;
        $this->view->pagination = $pagination;
        echo $this->view->render(__DIR__ . '/../Views/layout/main.php', __DIR__ . '/../Views/main/index.php');
    }
    
    protected function actionArticle()
    {
        if (!empty($_GET['id'])){
            $id_article = (int)$_GET['id'];
            if ($article = \Application\Models\Article::findById($id_article)){           
                $this->view->article = $article;
                echo $this->view->render(__DIR__ . '/../Views/layout/main.php', __DIR__ . '/../Views/main/article.php');
            }else{
                $this->actionError404();
            }
        }else{
            $this->actionError404();
        }
    }
    
    protected function actionPage()
    {
        if (!empty($_GET['id'])){
            $id_page = (int)$_GET['id'];
            if ($page = \Application\Models\Page::findById($id_page)){           
                $this->view->page = $page;
                echo $this->view->render(__DIR__ . '/../Views/layout/main.php', __DIR__ . '/../Views/main/page.php');
            }else{
                $this->actionError404();
            }
        }else{
            $this->actionError404();
        }
    }
    
    protected function actionCategory()
    {
        if (!empty($_GET['id'])){
            $id_category = (int)$_GET['id'];
            if ($pages = \Application\Models\Page::findByIdCategory($id_category)){           
                $this->view->pages = $pages;
                echo $this->view->render(__DIR__ . '/../Views/layout/main.php', __DIR__ . '/../Views/main/category.php');
            }else{
                $this->actionError404();
            }
        }else{
            $this->actionError404();
        }
    }

    protected function actionGallery()
    {
        $this->view->title = "Фотогалереи";
        $galleries = \Application\Models\Gallery::findAllIsActive();
        $this->view->galleries = $galleries;
        echo $this->view->render(__DIR__ . '/../Views/layout/main.php', __DIR__ . '/../Views/main/gallery.php');
    }
    
    protected function actionViewGallery(){
        if (!empty($_GET['id'])){
            $id_gallery = (int)$_GET['id'];
            $gallery = \Application\Models\Gallery::findById($id_gallery);
            if ($images = \Application\Models\Image::getImagesByGallery($id_gallery)){           
                $this->view->images = $images;
                $this->view->gallery = $gallery;
                echo $this->view->render(__DIR__ . '/../Views/layout/main.php', __DIR__ . '/../Views/main/view-gallery.php');
            }else{
                $this->actionError404();
            }
        }else{
            $this->actionError404();
        }
    }

    protected function actionAutorize()
    {
        $this->view->title = "Мой сайт";    
        $user = User::instance();
        if ($_POST['login'] && $_POST['password']){
            
            if ($user->login($_POST['login'], $_POST['password'])){
                $referer = Session::getSessionData('referer');
                if ($referer != null) {
                    header("Location: ".$referer);
                }else{
                    header("Location: /");
                }
            }else{
                echo "Не верно введены логин или пароль.<br>";
            }
        }else{
            //$dataUser = $user->getUser();            
            //echo $this->view->render(__DIR__ . '/../Views/layout/main.php', __DIR__ . '/../Views/main/autorize.php', ['user' => $dataUser]);
            $this->view->user = $user->getUser();
            echo $this->view->render(__DIR__ . '/../Views/layout/main.php', __DIR__ . '/../Views/main/autorize.php');
        }
        
        
    }
    
    protected function actionSearch(){
        $this->view->title = "Результаты поиска";
        if (!empty($_POST['search'])){
            //var_dump($_POST);
            $search = htmlspecialchars($_POST['search']);
            $pages = \Application\Models\Page::search('content', $search);
            //var_dump($pages);
            $this->view->pages = $pages;
            echo $this->view->render(__DIR__ . '/../Views/layout/main.php', __DIR__ . '/../Views/main/search.php');
        }else{
            $this->actionError404();
        }
    }

        protected function actionTest()
    {
        
        
        /*$user = User::instance();
        $dataUser = $user->getUser();
        
        $category = \Application\Models\Category::findById(8);
        $path = \Application\Models\Category::getPath(8);
        
        $pages = \Application\Models\Category::updateFullUrl(6);
        echo $path."<br>";
        echo '<pre>';
        //var_dump($dataUser);
        
       // var_dump($dataUser->isAutorize());
        
        //var_dump(get_object_vars($category));
        var_dump($category->getTree());
        var_dump($user->getTree());
        //var_dump($pages);
        
        echo '</pre>';
        //if ($dataUser->isAutorize()){
            echo "<h3>Тестовая страница</h3>";
            echo '<pre>';
            echo 'Логин: ' . $dataUser->login;
            //var_dump($dataUser);
            echo '</pre>';
            echo ('Получаем данные из сессии напрямую: ' . $_SESSION['sid'] . "<br>");
            echo '<a href="/index.php?controller=main&action=logout">Выход</a>';
       //}else{
       //     Session::setSessionData('referer', $_SERVER['REQUEST_URI']);
       //     header("Location: /index.php?controller=main&action=autorize");
       // }
        */
        
        $pages = \Application\Models\Page::search('content', 'плохим');
        var_dump($pages);
    }
    
    protected function actionError404()
    {
        echo $this->view->render(__DIR__ . '/../Views/layout/main.php', __DIR__ . '/../Views/main/error404.php');
    }


    protected function actionLogout()
    {
        $user = User::instance();
        $user->logout();
    }
}