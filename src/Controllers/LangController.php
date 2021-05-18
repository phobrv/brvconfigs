<?php

namespace Phobrv\BrvConfigs\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Phobrv\BrvConfigs\Services\ConfigLangService;
use Phobrv\BrvCore\Repositories\OptionRepository;
use Phobrv\BrvCore\Repositories\PostRepository;
use Phobrv\BrvCore\Repositories\TermRepository;
use Phobrv\BrvCore\Services\UnitServices;

class LangController extends Controller {
	protected $optionRepository;
	protected $unitService;
	protected $configLangService;
	protected $postRepository;
	protected $termRepository;

	public function __construct(
		TermRepository $termRepository,
		OptionRepository $optionRepository,
		ConfigLangService $configLangService,
		PostRepository $postRepository,
		UnitServices $unitService
	) {
		$this->termRepository = $termRepository;
		$this->optionRepository = $optionRepository;
		$this->postRepository = $postRepository;
		$this->unitService = $unitService;
		$this->configLangService = $configLangService;

	}

	public function index() {
		try {
			$data['breadcrumbs'] = $this->unitService->generateBreadcrumbs(
				[
					['text' => 'Config Lang', 'href' => ''],
				]
			);
			$data['langArray'] = $this->configLangService->getArrayLangConfig();
			// dd($data['langArray']);
			return view('phobrv::config.lang', ['data' => $data]);
		} catch (Exception $e) {
			return back()->with('alert_danger', $e->getMessage());
		}
	}
	public function store(Request $request) {
		$data = $request->all();
		$langArray = $this->configLangService->getArrayLangConfig();
		if (!in_array($data['lang'], $langArray)) {
			$langArray = array_merge($langArray, ['0' => $data['lang']]);
		}
		$this->optionRepository->updateOption([
			'langArray' => json_encode($langArray),
		]);
		return redirect()->route('configlang.index');
	}

	public function createTranslatePost($post_id, $lang) {
		$post = $this->postRepository->with('terms')->find($post_id);
		$arrayTermID = $this->termRepository->getArrayTermID($post->terms);
		$title = $post->title . "-" . $lang;
		$tranPost = $this->postRepository->create(
			[
				'user_id' => Auth::id(),
				'title' => $title,
				'slug' => $this->unitService->renderSlug($title),
				'lang' => $lang,
				'thumb' => $post->thumb,
				'type' => $post->type,
			]
		);

		$this->postRepository->updateTagAndCategory($tranPost, [], $arrayTermID);

		return redirect()->route('post.edit', ['post' => $tranPost->id]);
	}

	public function removeLang(Request $request, $lang) {
		$langArray = $this->configLangService->getArrayLangConfig();
		if (($key = array_search($lang, $langArray)) !== false) {
			unset($langArray[$key]);
		}
		$this->optionRepository->updateOption([
			'langArray' => json_encode($langArray),
		]);
		return redirect()->route('configlang.index');
	}

	public function changeMainLang(Request $request, $lang) {
		$langArray = $this->configLangService->getArrayLangConfig();
		if (($key = array_search($lang, $langArray)) !== false) {
			unset($langArray[$key]);
			array_unshift($langArray, $lang);
		}
		$this->optionRepository->updateOption([
			'langArray' => json_encode($langArray),
		]);
		return redirect()->route('configlang.index');
	}
}
