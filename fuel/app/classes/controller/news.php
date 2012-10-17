<?php
class Controller_News extends Controller_Template 
{

	public function action_index()
	{
		if(!Auth::check()){
			Session::set_flash('warning', 'You don\'t have the right to read a news');
			Response::redirect('admin');
		}
		$data['news'] = Model_News::find('all');
		$this->template->title = "News";
		$this->template->content = View::forge('news/index', $data);

	}

	public function action_view($id = null)
	{	
		if(!Auth::check()){
			Session::set_flash('warning', 'You don\'t have the right to read the news');
			Response::redirect('admin');
		}
		$data['news'] = Model_News::find($id);
		is_null($id) and Response::redirect('News');

		$this->template->title = "News";
		$this->template->content = View::forge('news/view', $data);

	}

	public function action_create()
	{
		if(!Auth::has_access('news.create')){
			Session::set_flash('warning', 'You don\'t have the right to create a news');
			Response::redirect('admin');
		}

		if (Input::method() == 'POST')
		{
			$val = Model_News::validate('create');
			
			if ($val->run())
			{
				$news = Model_News::forge(array(
					'title' => Input::post('title'),
					'body' => Input::post('body'),
				));

				if ($news and $news->save())
				{
					Session::set_flash('success', 'Added news #'.$news->id.'.');

					Response::redirect('news');
				}

				else
				{
					Session::set_flash('error', 'Could not save news.');
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "News";
		$this->template->content = View::forge('news/create');

	}

	public function action_edit($id = null)
	{
		if(!Auth::has_access('news.edit')){
			Session::set_flash('warning', 'You don\'t have the right to edit a news');
			Response::redirect('admin');
		}
		is_null($id) and Response::redirect('News');

		$news = Model_News::find($id);

		$val = Model_News::validate('edit');

		if ($val->run())
		{
			$news->title = Input::post('title');
			$news->body = Input::post('body');

			if ($news->save())
			{
				Session::set_flash('success', 'Updated news #' . $id);

				Response::redirect('news');
			}

			else
			{
				Session::set_flash('error', 'Could not update news #' . $id);
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$news->title = $val->validated('title');
				$news->body = $val->validated('body');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('news', $news, false);
		}

		$this->template->title = "News";
		$this->template->content = View::forge('news/edit');

	}

	public function action_delete($id = null)
	{
		if(!Auth::has_access('news.delete')){
			Session::set_flash('warning', 'You don\'t have the right to delete a news');
			Response::redirect('admin');
		}
		if ($news = Model_News::find($id))
		{
			$news->delete();

			Session::set_flash('success', 'Deleted news #'.$id);
		}

		else
		{
			Session::set_flash('error', 'Could not delete news #'.$id);
		}

		Response::redirect('news');

	}


}