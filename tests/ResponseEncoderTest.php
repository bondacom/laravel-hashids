<?php

namespace Bondacom\LaravelHashids\Tests;

use Bondacom\LaravelHashids\ResponseEncoder;
use Illuminate\Http\Response;

class ResponseEncoderTest extends TestCase
{
    /**
     * @var \Bondacom\LaravelHashids\ResponseEncoder
     */
    public $encoder;

    public function setUp()
    {
        parent::setUp();

        $this->encoder = app(ResponseEncoder::class);
    }

    /**
     * @test
     */
    public function it_does_not_change_status_code_and_json_structure_from_response()
    {
        $data = [
            'id' => 1,
            'name' => 'John',
            'email' => 'johndoe@gmail.com',
            'role_id' => '3',
            'orders' => [
                'id' => 321,
                'description' => 'Testing',
                'type' => 'multiple',
            ]
        ];
        $response = response(compact('data'), Response::HTTP_OK);

        $responseEncoded = $this->encoder->handle($response);

        $this->assertEquals(Response::HTTP_OK, $responseEncoded->getStatusCode());
        $content = json_decode($responseEncoded->getContent(), true);

        $this->assertEquals(array_keys($content['data']), [
            'id',
            'name',
            'email',
            'role_id',
            'orders'
        ]);
        $this->assertEquals(array_keys($content['data']['orders']), [
            'id',
            'description',
            'type'
        ]);
    }

    /**
     * @test
     */
    public function it_encode_ids_from_response_content()
    {
        $data = [
            'id' => 1,
            'name' => 'John',
            'email' => 'johndoe@gmail.com',
            'role_id' => 3,
            'provider_id' => null,
            'orders' => [
                'id' => 11,
                'cupon_id' => 2,
                'description' => 'Testing'
            ]
        ];
        $response = response(compact('data'), Response::HTTP_OK);

        $responseEncoded = $this->encoder->handle($response);
        $content = json_decode($responseEncoded->getContent(), true);

        $this->assertEquals($data['name'], $content['data']['name']);
        $this->assertEquals($data['email'], $content['data']['email']);
        $this->assertEquals($data['provider_id'], $content['data']['provider_id']);
        $this->assertNotEquals($data['id'], $content['data']['id']);
        $this->assertNotEquals($data['role_id'], $content['data']['role_id']);
        $this->assertNotEquals($data['orders']['id'], $content['data']['orders']['id']);
        $this->assertNotEquals($data['orders']['cupon_id'], $content['data']['orders']['cupon_id']);
        $this->assertEquals($data['orders']['description'], $content['data']['orders']['description']);
    }

    /**
     * @test
     */
    public function it_does_not_encode_arrays_without_ids()
    {
        $data = [
            'data' => [
                'First data',
                'Second data',
            ],
            'meta' => [
                'First meta'
            ]
        ];

        $response = response($data, Response::HTTP_OK);

        $responseEncoded = $this->encoder->handle($response);
        $content = json_decode($responseEncoded->getContent(), true);

        $this->assertEquals($data, $content);
    }

    /**
     * @test
     */
    public function it_encode_arrays_with_ids()
    {
        $data = [
            'data' => [
                'genders_id' => [
                    '1',
                    '2',
                ],
            ],
            'meta' => [
                'First meta'
            ]
        ];

        $response = response($data, Response::HTTP_OK);

        $responseEncoded = $this->encoder->handle($response);
        $content = json_decode($responseEncoded->getContent(), true);

        $this->assertEquals($data['meta'], $content['meta']);
        $this->assertNotEquals($data['data']['genders_id'], $content['data']['genders_id']);
        $this->assertTrue(is_array($content['data']['genders_id']));
    }

    /**
     * @test
     */
    public function it_works_when_response_does_not_have_content()
    {
        $response = response('', Response::HTTP_NO_CONTENT);

        $responseEncoded = $this->encoder->handle($response);
        $content = json_decode($responseEncoded->getContent(), true);

        $this->assertNull($content);

    }
}