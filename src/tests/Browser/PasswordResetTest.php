<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Browser\Pages\PasswordResetPage;

class PasswordResetTest extends DuskTestCase
{
    private $user;

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->user();
    }

    /**
     * @test
     * Can navigate to login page.
     * @return void
     */
    public function canNavigateToLoginPage()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new PasswordResetPage)
                ->click('@loginLink')
                ->waitForLocation('/login')
                ->assertSee('Login to your account');
        });
    }

    /**
     * @test
     * Can not receive password reset link with empty email field.
     * @return void
     */
    public function canNotReceivePasswordResetLinkWithEmptyEmailField()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new PasswordResetPage)
                ->clear('@email')
                ->click('@submitButton')
                ->waitFor('@invalidFormField')
                ->assertSeeIn('@email + .invalid-feedback', 'The email field is required.');
        });
    }

    /**
     * @test
     * Can not receive password reset link with invalid email.
     * @return void
     */
    public function canNotReceivePasswordResetLinkWithInvalidEmail()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new PasswordResetPage)
                ->type('@email', self::$userEmailInvalid)
                ->click('@submitButton')
                ->waitFor('@invalidFormField')
                ->assertSeeIn('@email + .invalid-feedback', 'We can\'t find a user with that e-mail address.');
        });
    }

    /**
     * @test
     * Can receive password reset link with valid email.
     * @return void
     */
    public function canReceivePasswordResetLinkWithValidEmail()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new PasswordResetPage)
                ->type('@email', $this->user->email)
                ->press('@submitButton')
                ->waitForLocation('/login')
                ->waitForText('We have e-mailed your password reset link!')
                ->assertVisible('@notificationBox');
        });
    }
}
