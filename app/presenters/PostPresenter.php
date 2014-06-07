<?php

namespace App\Presenters;

use Nette,
	App\Model;


/**
* Post Presenter
*/
class PostPresenter extends BasePresenter
{
	/** @var Nette\Database\Context */
	private $database;

	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function renderShow($postId)
	{
	  $post = $this->database->table('posts')->get($postId);

	  if(!$post) {
	  	$this->error('Stránka nebyla nalezena');
	  }

	  $this->template->post = $post;
	  $this->template->comments = $post->related('comments')->order('created_at');
	}

	public function createComponentCommentForm()
	{
		$form = new Nette\Application\UI\Form;

		$form->addText('name', 'Jméno:')
			->setRequired();

		$form->addText('email', 'Email:');

		$form->addTextArea('content', 'Komentář:')
			->setRequired();

		$form->addSubmit('send', 'Odeslat komentář:');

		$form->onSuccess[] = $this->commentFormSucceeded;

		return $form;
	}

	public function commentFormSucceeded($form)
	{
		$values = $form->getValues();
		$postId = $this->getParameter('postId');

		$this->database->table('comments')->insert(array(
			'post_id' => $postId,
			'name' => $values->name,
			'email' => $values->email,
			'content' => $values->content
		));

		$this->flashMessage('Děkuji za komentář.','success');
		$this->redirect('this');
	}
}
