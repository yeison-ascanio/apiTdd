<?php

namespace Tests\Feature;

use App\Http\Middleware\ValidateJsonApiHeaders;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ValidateJsonApiHeadersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::any("test", fn () => 'ok')->middleware(ValidateJsonApiHeaders::class);
    }

    /** @test */

    public function accept_header_must_be_present_in_all_request()
    {
        $this->get("test")->assertStatus(406);

        $this->get("test", [
            'accept' => 'application/vnd.api+json'
        ])->assertSuccessful();
    }

    /** @test */
    public function accept_header_must_be_present_on_all_post_request()
    {
        $this->post("test", [], [
            'accept' => 'application/vnd.api+json'
        ])->assertStatus(415);

        $this->post("test", [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertSuccessful();
    }

    /** @test */
    public function accept_header_must_be_present_on_all_patch_request()
    {
        $this->patch("test", [], [
            'accept' => 'application/vnd.api+json'
        ])->assertStatus(415);

        $this->patch("test", [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertSuccessful();
    }

    /** @test */
    public function content_type_header_must_be_present_in_responses()
    {
        $this->get("test", [
            'accept' => 'application/vnd.api+json'
        ])->assertHeader('content-type', 'application/vnd.api+json');

        $this->post("test", [],  [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertHeader('content-type', 'application/vnd.api+json');

        $this->patch("test", [],  [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertHeader('content-type', 'application/vnd.api+json');
    }

    /** @test */
    public function content_type_header_must_not_prensent_in_empty_responses()
    {
        Route::any('empty_response', fn () =>
            response()->noContent())->middleware(ValidateJsonApiHeaders::class);

        $this->get('empty_response', [
            'accept' => 'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');

        $this->post('empty_response', [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');

        $this->patch('empty_response', [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');

        $this->delete('empty_response', [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');
    }
}
