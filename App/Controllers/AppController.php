<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

 public function timeline(){
     

      $this->validaAutenticacao();

      // recuperar tweets
      $tweet = Container::getModel('tweet');
      $tweet->__set('id_usuario', $_SESSION['id']);

      $tweets = $tweet->getAll();
      
      $usuario = Container::getModel('Usuario');
      $usuario->__set('id', $_SESSION['id']);

      $this->view->info_usuario = $usuario->getInfoUsuario();
      $this->view->total_twt = $usuario->getTotalTweet();
      $this->view->total_seg = $usuario->getTotalSeguidores();
      $this->view->total_segd = $usuario->getTotalSeguindo();

      $this->view->tweets = $tweets;
      $this->render('timeline');

     
 }

   public function tweet(){
      

      $this->validaAutenticacao();

      $tweet = Container::getModel('tweet');

      $tweet->__set('tweet', $_POST['tweet']);
      $tweet->__set('id_usuario', $_SESSION['id']);

      $tweet->salvar();

      header("Location: /timeline");
     
      

   }

   public function validaAutenticacao(){
      session_start();

      if (!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == ''){ 
         header('Location: /?login=erro');
      }
      
  }


  public function quem_seguir(){

      $this->validaAutenticacao();

      $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';     
   
      $usuarios = array();
      if ($pesquisarPor != '') {

         $usuario = Container::getModel('Usuario');
         $usuario->__set('nome', $pesquisarPor);
         $usuario->__set('id', $_SESSION['id']);
         $usuarios = $usuario->getAll();

      }

      $usuario = Container::getModel('Usuario');
      $usuario->__set('id', $_SESSION['id']);

      $this->view->info_usuario = $usuario->getInfoUsuario();
      $this->view->total_twt = $usuario->getTotalTweet();
      $this->view->total_seg = $usuario->getTotalSeguidores();
      $this->view->total_segd = $usuario->getTotalSeguindo();
         // para usar na view
      $this->view->usuarios = $usuarios;

      $this->render('quemSeguir');
  
   }


   public function acao(){

      $this->validaAutenticacao();
      
      $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
      $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

      $usuario = Container::getModel('Usuario');
      $usuario->__set('id', $_SESSION['id']);

      if ($acao == "seguir") {
         $usuario->seguirUsuario($id_usuario_seguindo);
      }else if ($acao == "deixar_de_seguir") {
         $usuario->deixarSeguirUsuario($id_usuario_seguindo);
      }

      header('Location: /quem_seguir');
   }

   public function remove_tweet(){

      $this->validaAutenticacao();

      $id_tweet = isset($_GET['id_tweet']) ? $_GET['id_tweet'] : '';

      $usuario = Container::getModel('tweet');
      $usuario->remover_tweet($id_tweet);

      header('Location: /timeline');
   }


}



?>