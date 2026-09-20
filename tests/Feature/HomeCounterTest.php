<?php

use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\followingRedirects;

it('shows Hello stranger to guests', function () {
    get('/')
        ->assertStatus(200)
        ->assertSee('Hello stranger')
        ->assertSee('data-counter-absent');
});

it('prevents guests from incrementing the counter', function () {
    Livewire::test(App\Livewire\Home::class)
        ->call('increment')
        ->assertForbidden();
});

it('allows a user to log in, see Hello world, increment the counter, then log out', function () {
    // Create a user with a known password
    $user = User::factory()->create([
        'password' => bcrypt('secret'),
    ]);

    // Log in via the POST /login route
    $loginResponse = post('/login', [
        'email'    => $user->email,
        'password' => 'secret',
    ]);
    $loginResponse->assertRedirect('/');

    // After login, the home page should greet the user
    followingRedirects()->get('/')
        ->assertSee('Hello world')
        ->assertDontSee('data-counter-absent');

    // Authenticated user can increment the Livewire counter
    Livewire::actingAs($user)
        ->test(App\Livewire\Home::class)
        ->call('increment')
        ->assertSet('count', 1)
        ->assertSeeHtml('data-count="1"');

    // Log out via the POST /logout route
    $logoutResponse = post('/logout');
    $logoutResponse->assertRedirect('/');

    // After logout, the guest view should be restored
    followingRedirects()->get('/')
        ->assertSee('Hello stranger')
        ->assertSee('data-counter-absent');
});
