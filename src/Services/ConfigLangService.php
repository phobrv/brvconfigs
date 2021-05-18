<?php
namespace Phobrv\BrvConfigs\Services;
use Phobrv\BrvCore\Repositories\OptionRepository;
use Phobrv\BrvCore\Repositories\PostRepository;
use Phobrv\BrvCore\Repositories\TermRepository;
use Phobrv\BrvCore\Services\UnitServices;

class ConfigLangService {
	protected $optionRepository;
	protected $postRepository;
	protected $unitService;
	protected $termRepository;

	public function __construct(
		TermRepository $termRepository,
		PostRepository $postRepository,
		OptionRepository $optionRepository,
		UnitServices $unitService
	) {
		$this->postRepository = $postRepository;
		$this->optionRepository = $optionRepository;
		$this->unitService = $unitService;
		$this->termRepository = $termRepository;
	}
	public function getArrayLangConfig() {
		$lang = $this->optionRepository->findWhere(['name' => 'langArray'])->first();
		$langArray = (!empty($lang)) ? json_decode($lang->value, true) : [];
		return $langArray;
	}

	public function genLangTranslateBox($post) {
		$langArray = $this->getArrayLangConfig();
		if (count($langArray) < 2) {
			return '';
		}
		if (($key = array_search($post->lang, $langArray)) !== false) {
			unset($langArray[$key]);
		}
		$out = '<a href="#"> <strong>CurLang:</strong> ' . strtoupper($post->lang) . ' </a> | <a href="#"><strong>Translate To:</strong> </a>';
		$out .= $this->genLangButton($post->id, $langArray);
		return $out;
	}

	public function genLangButton($post_id, $langArray) {
		$out = '';
		$termLang = $this->postRepository->find($post_id)->terms()->where('taxonomy', config('option.taxonomy.lang'))->first();
		$posts = $this->termRepository->with('posts')->find($termLang->id)->posts;

		foreach ($langArray as $value) {
			$post = $posts->where('lang', $value)->first();
			if (empty($post)) {
				$out .= '<a href="' . route('configlang.createTranslatePost', ['source_id' => $post_id, 'lang' => $value]) . '"><button class="btn-default btn"> ' . strtoupper($value) . ' </button></a>&nbsp;&nbsp;&nbsp;';
			} else {
				$out .= '<a href="' . route('post.edit', ['post' => $post->id]) . '"><button class="btn-primary btn"> ' . strtoupper($value) . ' </button></a>&nbsp;&nbsp;&nbsp;';
			}
		}
		return $out;
	}

	public function getMainLang() {
		$langArray = $this->getArrayLangConfig();
		return (empty($langArray)) ? 'vi' : $langArray[0];
	}

	public function createTermLang($post) {
		$termName = "lang-group-" . $post->id;

		$term = $this->termRepository->create([
			'name' => $termName,
			'slug' => $this->unitService->renderSlug($termName),
			'taxonomy' => config('option.taxonomy.lang'),
		]);
		$term->posts()->attach($post->id);
	}

	public function syncPostTagAndCategory($post, $tag, $category) {
		$term = $post->terms->where('taxonomy', config('option.taxonomy.lang'))->first();
		$posts = $this->termRepository->find($term->id)->posts;
		foreach ($posts as $post) {
			$this->postRepository->updateTagAndCategory($post, $tag, $category);
		}
	}
}
