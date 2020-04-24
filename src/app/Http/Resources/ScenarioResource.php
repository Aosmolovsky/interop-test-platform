<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScenarioResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'components' => ComponentResource::collection($this->whenLoaded('components')),
            'useCases' => UseCaseResource::collection($this->whenLoaded('useCases')),
            'testCases' => TestCaseResource::collection($this->whenLoaded('testCases')),
        ];
    }
}
