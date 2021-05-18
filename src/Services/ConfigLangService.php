<?php
namespace Phobrv\BrvConfigs\Services;
use Phobrv\BrvCore\Repositories\OptionRepository;
use Phobrv\BrvCore\Repositories\PostRepository;
use Phobrv\BrvCore\Repositories\TranslateRepository;
use Phobrv\BrvCore\Services\UnitServices;

class ConfigLangService {
	protected $translateRepository;
	protected $optionRepository;
	protected $postRepository;
	protected $unitService;

	public function __construct(
		PostRepository $postRepository,
		TranslateRepository $translateRepository,
		OptionRepository $optionRepository,
		UnitServices $unitService
	) {
		$this->postRepository = $postRepository;
		$this->translateRepository = $translateRepository;
		$this->optionRepository = $optionRepository;
		$this->unitService = $unitService;
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
		$source_id = $this->getSourceID($post);
		$out = '<a href="#"> <strong>CurLang:</strong> ' . strtoupper($post->lang) . ' </a> | <a href="#"><strong>Translate To:</strong> </a>';
		$out .= $this->genLangButton($source_id, $langArray);
		return $out;
	}

	public function genLangButton($source_id, $langArray) {
		$out = '';
		foreach ($langArray as $value) {
			$tranPost = $this->translateRepository->findWhere(['source_id' => $source_id, 'lang' => $value])->first();
			if (empty($tranPost)) {
				$out .= '<a href="' . route('configlang.createTranslatePost', ['source_id' => $source_id, 'lang' => $value]) . '"><button class="btn-default btn"> ' . strtoupper($value) . ' </button></a>&nbsp;&nbsp;&nbsp;';
			} else {
				$out .= '<a href="' . route('post.edit', ['post' => $tranPost->post_id]) . '"><button class="btn-primary btn"> ' . strtoupper($value) . ' </button></a>&nbsp;&nbsp;&nbsp;';
			}
		}
		return $out;
	}

	public function getMainLang() {
		$langArray = $this->getArrayLangConfig();
		return (empty($langArray)) ? 'vi' : $langArray[0];
	}

	public function getSourceID($post) {
		$tran = $this->translateRepository->findWhere(['post_id' => $post->id])->first();
		if (empty($tran)) {
			if (empty($post->lang)) {
				$lang = $this->getMainLang();
				$post = $this->postRepository->update(['lang' => $lang], $post->id);
			}
			$tran = $this->translateRepository->create([
				'source_id' => $post->id,
				'post_id' => $post->id,
				'lang' => $post->lang,
			]);
		}
		return $tran->source_id;
	}

	public function syncPostTransTerm($post, $arrayTagName, $arrayCategoryID) {

		$source_id = $this->getSourceID($post);
		$trans = $this->translateRepository->findWhere(['source_id' => $source_id]);
		if (count($trans)) {
			foreach ($trans as $p) {
				$pn = $this->postRepository->find($p->post_id);
				$this->postRepository->updateTagAndCategory($pn, $arrayTagName, $arrayCategoryID);
			}
		}
	}
}
