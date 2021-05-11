<?php

namespace Phobrv\BrvConfigs\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Phobrv\BrvCore\Repositories\OptionRepository;
use Phobrv\BrvCore\Repositories\TermRepository;
use Phobrv\BrvCore\Services\UnitServices;

class ConfigController extends Controller {
	protected $optionRepository;
	protected $termRepository;
	protected $unitService;
	protected $app;
	public function __construct(
		OptionRepository $optionRepository,
		TermRepository $termRepository,
		Application $app,
		UnitServices $unitService
	) {
		$this->app = $app;
		$this->optionRepository = $optionRepository;
		$this->termRepository = $termRepository;
		$this->unitService = $unitService;
	}

	public function system() {
		try {
			$data['breadcrumbs'] = $this->unitService->generateBreadcrumbs(
				[
					['text' => 'Config website', 'href' => ''],
				]
			);
			$data['configs'] = $this->optionRepository->handleOptionToArray($this->optionRepository->all());
			$data['configs']['robots_txt'] = $this->unitService->readFile(config('option.robots_file'));
			$data['configs']['customize_css'] = $this->unitService->readFile(config('option.customize_css_file'));
			$data['configs']['htaccess'] = $this->unitService->readFile(config('option.htaccess_file'));
			$data['configs']['maintenance'] = ($this->app->isDownForMaintenance()) ? true : false;
			if ($data['configs']['maintenance'] && isset($data['configs']['secret_key'])) {
				$data['configs']['link_private'] = env('APP_URL') . "/" . $data['configs']['secret_key'];
			}
			return view('phobrv::config.system')
				->with('data', $data);
		} catch (Exception $e) {
			return back()->with('alert_danger', $e->getMessage());
		}
	}

	public function website() {
		try {
			$data['breadcrumbs'] = $this->unitService->generateBreadcrumbs(
				[
					['text' => 'Config website', 'href' => ''],
				]
			);

			$data['configs'] = $this->optionRepository->handleOptionToArray($this->optionRepository->all());

			return view('phobrv::config.website')
				->with('data', $data);
		} catch (Exception $e) {
			return back()->with('alert_danger', $e->getMessage());
		}
	}

	public function widget() {
		try {
			//Breadcrumbs
			$data['breadcrumbs'] = $this->unitService->generateBreadcrumbs(
				[
					['text' => 'Config widgets', 'href' => ''],
				]
			);
			$data['configs'] = $this->optionRepository->handleOptionToArray($this->optionRepository->all());
			return view('phobrv::config.widget')
				->with('data', $data);
		} catch (Exception $e) {
			return back()->with('alert_danger', $e->getMessage());
		}
	}

	public function update(Request $request) {

		try {
			$this->optionRepository->updateOption($request->all());

			if ($request->type == 'web') {
				return redirect()->route('config.website')->with('alert_success', 'Update config success.');
			} else {
				return redirect()->route('config.widget')->with('alert_success', 'Update widget success.');
			}

		} catch (Exception $e) {
			return back()->with('alert_danger', $e->getMessage());
		}
	}

	public function showIcon() {
		//Breadcrumbs
		$data['breadcrumbs'] = $this->unitService->generateBreadcrumbs(
			[
				['text' => 'List icon', 'href' => ''],
			]
		);
		$body = '<div class="row">';
		$files = \File::allFiles(resource_path('views/svg'));
		foreach ($files as $f) {
			$body .= ' <div class="col-md-4"> ';
			$myfile = fopen((string) $f, "r") or die("Unable to open file!");
			$body .= fread($myfile, filesize((string) $f));
			$body .= $this->getName((string) $f);
			fclose($myfile);
			$body .= ' </div> ';

		}
		$body .= ' </div> ';
		$data['body'] = $body;
		return view('phobrv::config.showIcon')->with('data', $data);
	}

	public function getName($string) {
		$array = explode("/", $string);
		return $array[count($array) - 1];
	}

	public function sidebar() {
		$data['configs'] = $this->optionRepository->handleOptionToArray($this->optionRepository->all());

		$data['sidebar_disable'] = isset($data['configs']['sidebar_disable']) ? json_decode($data['configs']['sidebar_disable'], true) : [];
		return view('phobrv::config.sidebar', ['data' => $data]);
	}
	public function updateSidebarConfig(Request $request) {
		$data = $request->all();
		$this->optionRepository->updateOption(
			[
				'sidebar_disable' => json_encode($data['disable']),
			]
		);
		return redirect()->route('config.sidebar');
	}

}
