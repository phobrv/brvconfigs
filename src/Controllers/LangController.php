<?php

namespace Phobrv\BrvConfigs\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Phobrv\BrvConfigs\Services\ConfigService;
use Phobrv\BrvCore\Repositories\OptionRepository;
use Phobrv\BrvCore\Services\UnitServices;

class LangController extends Controller {
	protected $optionRepository;
	protected $unitService;
	protected $configService;

	public function __construct(
		OptionRepository $optionRepository,
		ConfigService $configService,
		UnitServices $unitService
	) {
		$this->optionRepository = $optionRepository;
		$this->unitService = $unitService;
		$this->configService = $configService;
	}

	public function index() {
		try {
			$data['breadcrumbs'] = $this->unitService->generateBreadcrumbs(
				[
					['text' => 'Config Lang', 'href' => ''],
				]
			);
			$data['langArray'] = $this->configService->getArrayLangConfig();
			// dd($data['langArray']);
			return view('phobrv::config.lang', ['data' => $data]);
		} catch (Exception $e) {
			return back()->with('alert_danger', $e->getMessage());
		}
	}
	public function store(Request $request) {
		$data = $request->all();
		$langArray = $this->configService->getArrayLangConfig();
		if (!in_array($data['lang'], $langArray)) {
			$langArray = array_merge($langArray, ['0' => $data['lang']]);
		}
		$this->optionRepository->updateOption([
			'langArray' => json_encode($langArray),
		]);
		return redirect()->route('configlang.index');
	}

	public function removeLang(Request $request, $lang) {
		$langArray = $this->configService->getArrayLangConfig();
		if (($key = array_search($lang, $langArray)) !== false) {
			unset($langArray[$key]);
		}
		$this->optionRepository->updateOption([
			'langArray' => json_encode($langArray),
		]);
		return redirect()->route('configlang.index');
	}

	public function changeMainLang(Request $request, $lang) {
		$langArray = $this->configService->getArrayLangConfig();
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
