<?php

use App\Imports\TestCasesImport;
use App\Models\ApiService;
use App\Models\Component;
use App\Models\ComponentPath;
use App\Models\Scenario;
use App\Models\TestCase;
use App\Models\TestRequestScript;
use App\Models\TestRequestSetup;
use App\Models\TestResponseScript;
use App\Models\TestResponseSetup;
use App\Models\TestStep;
use App\Models\UseCase;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Symfony\Component\Yaml\Yaml;

class ScenariosTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $import = new TestCasesImport(UseCase::first());
        $data = $import->import(Yaml::parseFile(database_path('seeds/data/authorized-transaction.yaml')));

        dd($data);

        $data = Yaml::parseFile(database_path('seeds/data/scenarios.yaml'), Yaml::PARSE_CUSTOM_TAGS);

        foreach ($data as $item) {
            $scenario = Scenario::create(Arr::only($item, Scenario::make()->getFillable()));

            $componentsData = Arr::get($item, 'components', []);
            $scenario->components()->createMany(collect($componentsData)->map(function ($item) {
                return array_merge(Arr::only($item, Component::make()->getFillable()), [
                    'api_service_id' => ApiService::whereRaw('CONCAT(name, " ", version) = ?', [Arr::get($item, 'api_service_id')])->value('id'),
                ]);
            }))->each(function (Component  $component, $key) use ($componentsData) {
                $component->paths()->attach(collect(Arr::get($componentsData, "{$key}.paths", []))->map(function ($item) use ($component) {
                    return array_merge(Arr::only($item, ComponentPath::make()->getFillable()), [
                        'target_id' => $component->scenario->components()->where('name', Arr::get($item, 'target_id'))->value('id'),
                    ]);
                }));
            });

            $useCasesData = Arr::get($item, 'use_cases', []);
            $scenario->useCases()->createMany(collect($useCasesData)->map(function ($item) {
                return Arr::only($item, UseCase::make()->getFillable());
            }))->each(function (UseCase $useCase, $key) use ($useCasesData) {
                $testCasesData = Arr::get($useCasesData, "{$key}.test_cases", []);
                $useCase->testCases()
                    ->createMany(collect($testCasesData)->map(function ($item) {
                        return Arr::only($item, TestCase::make()->getFillable());
                    }))
                    ->each(function (TestCase $testCase, $key) use ($testCasesData) {
                        $testStepsData = Arr::get($testCasesData, "{$key}.test_steps", []);
                        $testCase->testSteps()
                            ->createMany(collect($testStepsData)->map(function ($item) use ($testCase) {
                                return array_merge(Arr::only($item, TestStep::make()->getFillable()), [
                                    'source_id' => $testCase->useCase->scenario->components()->where('name', Arr::get($item, 'source_id'))->value('id'),
                                    'target_id' => $testCase->useCase->scenario->components()->where('name', Arr::get($item, 'target_id'))->value('id'),
                                ]);
                            }))
                            ->each(function (TestStep $testStep, $key) use ($testStepsData) {
                                $testStep->testRequestSetups()
                                    ->createMany(collect(Arr::get($testStepsData, "{$key}.test_request_setups", []))->map(function ($item) {
                                        return Arr::only($item, TestRequestSetup::make()->getFillable());
                                    }));
                                $testStep->testResponseSetups()
                                    ->createMany(collect(Arr::get($testStepsData, "{$key}.test_response_setups", []))->map(function ($item) {
                                        return Arr::only($item, TestResponseSetup::make()->getFillable());
                                    }));

                                $testStep->testRequestScripts()
                                    ->createMany(collect(Arr::get($testStepsData, "{$key}.test_request_scripts", []))->map(function ($item) {
                                        return Arr::only($item, TestRequestScript::make()->getFillable());
                                    }));
                                $testStep->testResponseScripts()
                                    ->createMany(collect(Arr::get($testStepsData, "{$key}.test_response_scripts", []))->map(function ($item) {
                                        return Arr::only($item, TestResponseScript::make()->getFillable());
                                    }));
                            });
                    });
            });
        }
    }
}
