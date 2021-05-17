<?php
namespace Phobrv\BrvConfigs\Services;
use Phobrv\BrvCore\Repositories\OptionRepository;
use Phobrv\BrvCore\Services\UnitServices;

class ConfigService {
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
}