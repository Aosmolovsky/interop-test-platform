<?php declare(strict_types=1);

use App\Models\Component;
use App\Models\Specification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ComponentsTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        factory(Component::class)
            ->createMany($this->getComponentsData())
            ->each(function (Component $component, $key) {
                $component->connections()->attach(Arr::get($this->getConnectionsData(), $key, []));
            });
    }

    /**
     * @return array
     */
    protected function getComponentsData()
    {
        return [
            [
                'uuid' => '114511be-74e9-49d5-b93e-b4a461e01626',
                'name' => 'Service Provider',
                'description' => '',
                'base_url' => env('API_SERVICE_SP_SIMULATOR_URL'),
            ],
            [
                'uuid' => 'e5f5e817-94d6-4a43-a7ec-f7274b6d85c2',
                'name' => 'Mobile Money Operator 1',
                'description' => '',
                'base_url' => env('API_SERVICE_MM_SIMULATOR_URL'),
            ],
            [
                'uuid' => 'b2a85076-b748-4d93-8df1-2b39844e6d4b',
                'name' => 'Mojaloop',
                'description' => '',
                'base_url' => env('API_SERVICE_MOJALOOP_HUB_URL'),
            ],
            [
                'uuid' => 'e602a859-a25f-4d37-9abe-0ac09fb734af',
                'name' => 'Mobile Money Operator 2',
                'description' => '',
                'base_url' => env('API_SERVICE_MOJALOOP_SIMULATOR_URL'),
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getConnectionsData()
    {
        return [
            [
                [
                    'target_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'specification_id' => Specification::where('name', 'MM v1.1.2')->value('id'),
                ],
            ],
            [

                [
                    'target_id' => Component::where('name', 'Service Provider')->value('id'),
                    'specification_id' => Specification::where('name', 'SP v1.0')->value('id'),
                ],

                [
                    'target_id' => Component::where('name', 'Mojaloop')->value('id'),
                    'specification_id' => Specification::where('name', 'Mojaloop v1.0')->value('id'),
                ],
            ],
            [

                [
                    'target_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'specification_id' => Specification::where('name', 'Mojaloop v1.0')->value('id'),
                ],

                [
                    'target_id' => Component::where('name', 'Mobile Money Operator 2')->value('id'),
                    'specification_id' => Specification::where('name', 'Mojaloop v1.0')->value('id'),
                ],
            ],
            [

                [
                    'target_id' => Component::where('name', 'Mojaloop')->value('id'),
                    'specification_id' => Specification::where('name', 'Mojaloop v1.0')->value('id'),
                ],
            ],
        ];
    }
}
