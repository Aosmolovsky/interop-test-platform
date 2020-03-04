<?php

use App\Models\TestCase;
use App\Models\Component;
use App\Models\TestScenario;
use Illuminate\Database\Seeder;
use Symfony\Component\Yaml\Yaml;

class TestScenariosTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $data = Yaml::parseFile(database_path('seeds/data/test-scenarios.yaml'));

        foreach ($data as $item) {
            $item = collect($item);
            $scenario = TestScenario::create($item->only(['name', 'description'])->all());
//            $scenario->components()->createMany(collect($item)->only('components', []))->each(function ($component) {
//
//            });
            dd($scenario);
        }

//        foreach ($this->getData() as $key => $data) {
//            $scenario = TestScenario::create($data);
//            $scenario->components()->createMany(Arr::get($this->getComponentsData(), $key))->each(function (TestComponent $component, $key) {
//                $component->platform()->createMany(Arr::get($this->getPlatformsData(), $key, []));
//                $component->connections()->attach(Arr::get($this->getConnectionsData(), $key, []));
//            });
//            $scenario->suites()->createMany(Arr::get($this->getSuitesData(), $key, []))->each(function (TestSuite $suite, $key) {
//                $suite->cases()->createMany(Arr::get($this->getCasesData(), $key, []))->each(function (TestCase $case, $key) {
//                    $case->steps()->createMany(Arr::get($this->getStepsData(), $key, []));
//                });
//            });
//        }
    }

    /**
     * @return array
     */
    protected function getConnectionsData()
    {
        return [
            /**
             * Payer
             */
            [
                [
                    'target_id' => Component::where('name', 'Service Provider')->value('id'),
                    'simulated' => false,
                ],
            ],
            /**
             * Service Provider
             */
            [
                [
                    'target_id' => Component::where('name', 'Payer')->value('id'),
                    'simulated' => false,
                ],
                [
                    'target_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'simulated' => true,
                ],
            ],
            /**
             * Mobile Money Operator 1
             */
            [
                [
                    'target_id' => Component::where('name', 'Service Provider')->value('id'),
                    'simulated' => true,
                ],
                [
                    'target_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'simulated' => true,
                ],
            ],
            /**
             * Mojaloop System
             */
            [
                [
                    'target_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'simulated' => true,
                ],
                [
                    'target_id' => Component::where('name', 'Mobile Money Operator 2')->value('id'),
                    'simulated' => true,
                ],
            ],
            /**
             * Mobile Money Operator 2
             */
            [
                [
                    'target_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'simulated' => true,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getSuitesData()
    {
        return [
            [
                [
                    'name' => 'Merchant-Initiated Merchant Payment',
                    'description' => 'A Merchant-Initiated Merchant Payment is typically a receive amount, where the Payer FSP is not disclosing any fees to the Payee FSP. Please refer to 5.1.6.8 in "Open API for FSP Interoperability Specification" for more details.',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getCasesData()
    {
        return [
            [
                [
                    'name' => 'Authorized Transaction',
                    'description' => '**Description**

The Payer would like to buy goods or services worth {amount} USD from a Merchant (the Payee) in the Payee MMO system. {amount} USD is transferred from the Payer MMO to the Payee MMO.

---

**Pre-conditions**

- Payee and Payer MMOs in Mojaloop as Participants.
- Payee and Payer exist in Mojaloop as Parties.

---

**Test data headers**

	{
	    "Accept": "application/json",
	    "Content-Type": "application/json",
	    "X-Callback-URL": "http://example.com/example",
	    "X-Date": "2020-02-20T10:28:44.466Z"
    }

---

**Test data body**

	{
        "amount":"100.00",
        "currency":"USD",
        "type":"merchantpay",
        "debitParty": [{"key":"msisdn", "value":"+33555123456"}],
        "creditParty": [{"key":"msisdn", "value":"+33555123457"}]
    }',
                    'behavior' => TestCase::BEHAVIOR_POSITIVE,
                ],
                [
                    'name' => 'Refused Transaction by MMO1',
                    'description' => '**Description**

The Payer would like to buy goods or services worth {amount} USD from a Merchant (the Payee) in the Payee MMO system. Payee sends the Transaction amount in wrong format to his MMO, so that MMO denies to process transaction.

---

**Pre-conditions**

- Transaction amount should be any float with .00, e.g. 50.00

---

**Test data headers**

	{
	    "Accept": "application/json",
	    "Content-Type": "application/json",
	    "X-Callback-URL": "http://example.com/example",
	    "X-Date": "2020-02-20T10:28:44.466Z"
    }

---

**Test data body**

	{
        "amount":"50.00",
        "currency":"USD",
        "type":"merchantpay",
        "debitParty": [{"key":"msisdn", "value":"+33555123456"}],
        "creditParty": [{"key":"msisdn", "value":"+33555123457"}]
    }',
                    'behavior' => TestCase::BEHAVIOR_NEGATIVE,
                ],
                [
                    'name' => 'Refused Transaction by Mojaloop',
                    'description' => '**Description**

The Payer would like to buy goods or services worth {amount} USD from a Merchant (the Payee) in the Payee MMO system. Payee\'s MMO misses to transfer partyIdentifier to Mojaloop and gets an error.

---

**Pre-conditions**

- Send "creditParty":"16135551213" within the initial quesry to MMO1.
- MMO1 misses to send Payee\'s "partyIdentifier":"16135551213" to Mojaloop.

**Test data headers**

	{
		"Accept": "application/json",
		"Content-Type": "application/json",
		"X-Callback-URL": "http://example.com/example",
		"X-Date": "2020-02-20T10:28:44.466Z"
	}

---

**Test data body**

	{
		"amount":"50.00",
		"currency":"USD",
		"type":"merchantpay",
		"debitParty": [{"key":"msisdn", "value":"33555123456"}],
		"creditParty": [{"key":"msisdn", "value":"16135551213"}]
	}',
                    'behavior' => TestCase::BEHAVIOR_NEGATIVE,
                ],
                [
                    'name' => 'Transaction amount matches requested amount',
                    'description' => '**Description**

The Payer would like to buy goods or services worth 70 USD from a Merchant (the Payee) in the Payee MMO system. Payer\'s MMO runs transaction for 70.00 USD as it was requested.

---

**Pre-conditions**

- Payee and Payer MMOs in Mojaloop as Participants.
- Payee and Payer exist in Mojaloop as Parties.

**Test data headers**

	{
		"Accept": "application/json",
		"Content-Type": "application/json",
		"X-Callback-URL": "http://example.com/example",
		"X-Date": "2020-02-20T10:28:44.466Z"
	}

---

**Test data body**

	{
		"amount":"70.00",
		"currency":"USD",
		"type":"merchantpay",
		"debitParty": [{"key":"msisdn", "value":"+33555123456"}],
		"creditParty": [{ "key":"msisdn", "value":"+33555123457"}]
	}',
                    'behavior' => TestCase::BEHAVIOR_POSITIVE,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getStepsData()
    {
        return [
            [
                [
                    'path' => 'transactions',
                    'method' => 'POST',
                    'source_id' => Component::where('name', 'Service Provider')->value('id'),
                    'target_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:202',
                    ],
                ],
                [
                    'path' => 'transactionRequests',
                    'method' => 'POST',
                    'source_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'target_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:202',
                    ],
                ],
                [
                    'path' => 'transactionRequests',
                    'method' => 'POST',
                    'source_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'target_id' => Component::where('name', 'Mobile Money Operator 2')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:202',
                    ],
                ],
                [
                    'path' => 'transactionRequests/%',
                    'method' => 'PUT',
                    'source_id' => Component::where('name', 'Mobile Money Operator 2')->value('id'),
                    'target_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:200',
                    ],
                ],
                [
                    'path' => 'transactionRequests/%',
                    'method' => 'PUT',
                    'source_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'target_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:200',
                    ],
                ],
                [
                    'path' => 'quotes',
                    'method' => 'POST',
                    'source_id' => Component::where('name', 'Mobile Money Operator 2')->value('id'),
                    'target_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:202',
                    ],
                ],
                [
                    'path' => 'quotes',
                    'method' => 'POST',
                    'source_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'target_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:202',
                    ],
                ],
                [
                    'path' => 'quotes/%',
                    'method' => 'PUT',
                    'source_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'target_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:200',
                    ],
                ],
                [
                    'path' => '%/quotes/%',
                    'method' => 'PUT',
                    'source_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'target_id' => Component::where('name', 'Mobile Money Operator 2')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:200',
                    ],
                ],
                [
                    'path' => 'transfers',
                    'method' => 'POST',
                    'source_id' => Component::where('name', 'Mobile Money Operator 2')->value('id'),
                    'target_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:202',
                    ],
                ],
                [
                    'path' => 'transfers',
                    'method' => 'POST',
                    'source_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'target_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:202',
                    ],
                ],
                [
                    'path' => 'transfers/%',
                    'method' => 'PUT',
                    'source_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'target_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:200',
                    ],
                ],
                [
                    'path' => '%/transfers/%',
                    'method' => 'PUT',
                    'source_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'target_id' => Component::where('name', 'Mobile Money Operator 2')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:200',
                    ],
                ],
            ],
            [
                [
                    'path' => 'transactions',
                    'method' => 'POST',
                    'source_id' => Component::where('name', 'Service Provider')->value('id'),
                    'target_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:400',
                    ],
                ],
            ],
            [
                [
                    'path' => 'transactions',
                    'method' => 'POST',
                    'source_id' => Component::where('name', 'Service Provider')->value('id'),
                    'target_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:202',
                    ],
                ],
                [
                    'path' => 'transactionRequests',
                    'method' => 'POST',
                    'source_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'target_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:500',
                    ],
                ],
            ],
            [
                [
                    'path' => 'transactions',
                    'method' => 'POST',
                    'source_id' => Component::where('name', 'Service Provider')->value('id'),
                    'target_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:202',
                    ],
                ],
                [
                    'path' => 'transactionRequests',
                    'method' => 'POST',
                    'source_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'target_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:202',
                    ],
                ],
                [
                    'path' => 'transactionRequests',
                    'method' => 'POST',
                    'source_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'target_id' => Component::where('name', 'Mobile Money Operator 2')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:202',
                    ],
                ],
                [
                    'path' => 'transactionRequests/%',
                    'method' => 'PUT',
                    'source_id' => Component::where('name', 'Mobile Money Operator 2')->value('id'),
                    'target_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:200',
                    ],
                ],
                [
                    'path' => 'transactionRequests/%',
                    'method' => 'PUT',
                    'source_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'target_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:200',
                    ],
                ],
                [
                    'path' => 'quotes',
                    'method' => 'POST',
                    'source_id' => Component::where('name', 'Mobile Money Operator 2')->value('id'),
                    'target_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:202',
                    ],
                ],
                [
                    'path' => 'quotes',
                    'method' => 'POST',
                    'source_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'target_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:202',
                    ],
                ],
                [
                    'path' => 'quotes/%',
                    'method' => 'PUT',
                    'source_id' => Component::where('name', 'Mobile Money Operator 1')->value('id'),
                    'target_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:200',
                    ],
                ],
                [
                    'path' => '%/quotes/%',
                    'method' => 'PUT',
                    'source_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'target_id' => Component::where('name', 'Mobile Money Operator 2')->value('id'),
                    'expected_request' => [],
                    'expected_response' => [
                        'status' => 'in:200',
                    ],
                ],
                [
                    'path' => 'transfers',
                    'method' => 'POST',
                    'source_id' => Component::where('name', 'Mobile Money Operator 2')->value('id'),
                    'target_id' => Component::where('name', 'Mojaloop System')->value('id'),
                    'expected_request' => [
                        'body.amount.amount' => 'in:70'
                    ],
                    'expected_response' => [],
                ],
            ],
        ];
    }
}
