<?php
namespace Phobrv\BrvConfigs\Services;
use Phobrv\BrvCore\Repositories\OptionRepository;
use Phobrv\BrvCore\Services\UnitServices;

class ConfigLangService {
	protected $optionRepository;
	protected $unitService;

	public function __construct(
		OptionRepository $optionRepository,
		UnitServices $unitService
	) {
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
		$out = '<a href="#"> <strong>CurLang:</strong> ' . strtoupper($post->lang) . ' </a> |' .
			'<a href="#"><strong>Translate To:</strong> </a>';
		foreach ($langArray as $value) {
			$out .= '<a href="#"><button class="btn-primary btn"> ' . strtoupper($value) . ' </button></a>';
		}
		return $out;
	}

	public function getMainLang() {
		$langArray = $this->getArrayLangConfig();
		return (empty($langArray)) ? 'vi' : $langArray[0];
	}
}