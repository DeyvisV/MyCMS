<?php

use Cms\Section\SectionRepo;

class AdminSectionsController extends \BaseController {

	protected $rules = array(
			'name' => 'required',
			'slug_url' => 'required',
			'type' => 'required|in:page,blog',
			'menu' => 'in:1,0',
			'published' => 'in:1,0',
			'menu_order' => 'integer'
		);

	protected $sectionRepo;

	public function __construct(SectionRepo $sectionRepo)
	{
		$this->sectionRepo = $sectionRepo;
	}

	public function index()
	{
		$sections = $this->sectionRepo->search(Input::all(), \Cms\Base\BaseRepo::PAGINATE);

		return View::make('admin/sections/list', compact('sections'));
	}

	public function create()
	{
		return View::make('admin/sections/create');
	}

	public function store()
	{
		$data = Input::all();

		$rules = array(
			'name' => 'required',
			'slug_url' => 'required',
			'type' => 'required|in:page,blog',
			'menu' => 'in:1,0',
			'published' => 'in:1,0',
			'menu_order' => 'integer'
		);

		$validator = Validator::make($data, $rules);

		if ($validator->passes()) 
		{
			$section = $this->sectionRepo->create($data);
		} 
		else 
		{
			return Redirect::back()->withInput()->withErrors($validator->messages());
		}
		
		return Redirect::to('admin/sections/'. $section->id);
	}


	public function show($id)
	{
		$section = $this->sectionRepo->findOrFail($id);
		return View::make('admin/sections/show')->with('section', $section);
	}

	public function edit($id)
	{
		$section = $this->sectionRepo->findOrFail($id);
		return View::make('admin/sections/edit')->with('section', $section);
	}

	public function update($id)
	{
		$section = $this->sectionRepo->findOrFail($id);

		$data = Input::all();
		
		$validator = Validator::make($data, $this->rules);

		if ($validator->passes()) 
		{
			$section = $this->sectionRepo->update($section, $data);
			return Redirect::route('admin.sections.show', $section->id);
		} 
		else 
		{
			return Redirect::back()->withInput()->withErrors($validator->messages());
		}
	}

	public function destroy($id)
	{
		$this->sectionRepo->delete($id);

		Redirect::route('admin.sections.index');
	}


}
