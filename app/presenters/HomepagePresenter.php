<?php

namespace App\Presenters;

use Nette,
	App\Model;


/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{
	/** @var Nette\Database\Context */
	private $database;

	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function renderDefault()
	{
		$posts = $this->database->query('select
			posts.*, count(comments.id) as count_of_comments
			from posts
				left join comments on posts.id = comments.post_id
			group by posts.id
			order by created_at desc');

		$this->template->posts = $posts;
	}

}
