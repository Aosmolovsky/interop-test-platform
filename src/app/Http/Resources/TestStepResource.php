<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestStepResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => implode(' ', [$this->forward, $this->backward]),
            'request_example' => $this->request_example->toArray(),
            'response_example' => $this->response_example->toArray(),
            'position' => $this->position,
            'source' => new ComponentResource($this->whenLoaded('source')),
            'target' => new ComponentResource($this->whenLoaded('target')),
            'apiScheme' => new ApiSchemeResource($this->whenLoaded('apiScheme')),
            'testScripts' => TestScriptResource::collection($this->whenLoaded('testScripts')),
        ];
    }
}
