<?php

use App\Models\Specification;
use Illuminate\Database\Seeder;
use Symfony\Component\Yaml\Yaml;

class SpecificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getData() as $key => $data) {
            $specification = Specification::create($data);

            foreach (Arr::get($this->getVersionsData(), $key, []) as $versionData) {
                $specification->versions()->create($versionData);
            }
        }
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return [
            [
                'name' => 'Mobile Money API',
            ],
            [
                'name' => 'Mojaloop Hub API',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getVersionsData()
    {
        return [
            [
                [
                    'name' => '1.0',
                    'schema' => Yaml::parse(file_get_contents('https://raw.githubusercontent.com/OAI/OpenAPI-Specification/master/examples/v3.0/petstore.yaml')),
                ],
            ],
            [
                [
                    'name' => '1.0',
                    'schema' => Yaml::parse(file_get_contents('https://raw.githubusercontent.com/OAI/OpenAPI-Specification/master/examples/v3.0/petstore.yaml')),
                ],
            ],
        ];
    }
}
